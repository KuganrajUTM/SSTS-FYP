<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Driver;
use App\Models\Child;
use App\Models\Parents;
use App\Models\Payment;
use App\Models\DriverSalary;
use App\Models\SosMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function viewUsers()
    {
        $parents = User::where('role', 'P')->get();
        $drivers = User::where('role', 'D')->get();

        return view('admin', compact('parents', 'drivers'));
    }

    public function userDetails($id)
    {
        $user = User::with(['parent.children.driver.user', 'driver.verification'])->find($id);

        $drivers = collect();
        if ($user->role === 'P') {
            $drivers = Driver::with('user')
                ->whereHas('verification', fn($q) => $q->where('ver_status', 'Approved'))
                ->get();
        }

        return view('user-details', compact('user', 'drivers'));
    }

    public function assignDriver(Request $request, $childId)
    {
        $request->validate([
            'driver_id' => 'required|exists:driver,id',
        ]);

        Child::findOrFail($childId)->update(['driver_id' => $request->driver_id]);

        return back()->with('success', 'Driver assigned successfully.');
    }

    public function showUsers()
    {
        $parents = User::where('role', 'P')->get();
        $drivers = User::where('role', 'D')->get();

        return view('admin', compact('parents', 'drivers'));
    }

    public function salaryIndex(Request $request)
    {
        $drivers = Driver::with('user')
            ->whereHas('verification', fn($q) => $q->where('ver_status', 'Approved'))
            ->get();

        $query = DriverSalary::with('driver.user')->orderBy('year', 'desc')->orderBy('month', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('driver_id')) {
            $query->where('driver_id', $request->driver_id);
        }

        $salaries = $query->get();

        return view('admin.salary', compact('drivers', 'salaries'));
    }

    public function storeSalary(Request $request)
    {
        $request->validate([
            'driver_id' => 'required|exists:driver,id',
            'amount'    => 'required|numeric|min:0.01',
            'month'     => 'required|integer|min:1|max:12',
            'year'      => 'required|integer|min:2020',
            'status'    => 'required|in:Paid,Pending',
            'notes'     => 'nullable|string|max:255',
        ]);

        DriverSalary::create([
            'driver_id' => $request->driver_id,
            'amount'    => $request->amount,
            'month'     => $request->month,
            'year'      => $request->year,
            'status'    => $request->status,
            'paid_at'   => $request->status === 'Paid' ? Carbon::now()->format('Y-m-d') : null,
            'notes'     => $request->notes,
        ]);

        return redirect()->route('admin.salary')->with('success', 'Salary record saved successfully.');
    }

    public function uploadSalaryReceipt(Request $request, int $id)
    {
        $request->validate(['receipt' => 'required|file|mimes:pdf|max:10240']);

        $sal = DriverSalary::findOrFail($id);

        if ($sal->receipt_pdf) {
            $old = public_path('salary-receipts/' . $sal->receipt_pdf);
            if (file_exists($old)) {
                @unlink($old);
            }
        }

        $file     = $request->file('receipt');
        $filename = time() . '_' . $sal->driver_id . '_receipt.pdf';
        $dir      = public_path('salary-receipts');

        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }

        $file->move($dir, $filename);

        $sal->receipt_pdf = $filename;
        $sal->save();

        return redirect()->route('admin.salary')->with('success', 'Receipt uploaded successfully.');
    }

    public function driverSalaryIndex()
    {
        $userId = Session::get('user_id') ?? Auth::id();

        if (!$userId) {
            return redirect()->route('login');
        }

        $user = User::with('driver')->find($userId);

        if (!$user || !$user->driver) {
            return redirect()->route('main');
        }

        $salaries = DriverSalary::where('driver_id', $user->driver->id)
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        return view('driver.salary', compact('salaries'));
    }

    public function downloadPayslip(int $id)
    {
        $sal = DriverSalary::with('driver.user')->findOrFail($id);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('payslip', ['sal' => $sal]);
        $filename = 'payslip_' . $sal->driver->user->name . '_' . \Carbon\Carbon::create($sal->year, $sal->month)->format('M_Y') . '.pdf';

        return $pdf->download($filename);
    }

    public function paymentRecords(Request $request)
    {
        $query = Payment::with(['child', 'parent.user', 'driver.user', 'receipt']);

        $paymentCounts = Payment::selectRaw('
            COUNT(CASE WHEN pay_status = "Pending" THEN 1 END) AS pending_count,
            COUNT(CASE WHEN pay_status = "Overdue" THEN 1 END) AS overdue_count,
            COUNT(CASE WHEN pay_status = "Paid" THEN 1 END) AS paid_count
        ')->first() ?? (object) ['pending_count' => 0, 'overdue_count' => 0, 'paid_count' => 0];

        if ($request->filled('status')) {
            $query->where('pay_status', $request->status);
        }

        if ($request->filled('month')) {
            $year = $request->filled('year') ? $request->year : now()->year;
            $query->whereMonth('issue_date', $request->month)->whereYear('issue_date', $year);
        } elseif ($request->filled('year')) {
            $query->whereYear('issue_date', $request->year);
        }

        if ($request->filled('driver_id')) {
            $query->where('driver_id', $request->driver_id);
        }

        $payments = $query->orderBy('issue_date', 'desc')->get();
        $drivers  = Driver::with('user')->get();

        // Find children with 3+ overdue incidents (currently overdue OR penalty already applied)
        $overdueByChild = Payment::selectRaw('child_id, COUNT(*) as overdue_count')
            ->where(function ($q) {
                $q->where('pay_status', 'Overdue')
                  ->orWhere('penalty_applied', true);
            })
            ->groupBy('child_id')
            ->having('overdue_count', '>=', 3)
            ->pluck('overdue_count', 'child_id');

        $repeatOffenders = collect();
        if ($overdueByChild->isNotEmpty()) {
            $repeatOffenders = Child::with(['parent.user'])
                ->whereIn('id', $overdueByChild->keys())
                ->get()
                ->map(function ($child) use ($overdueByChild) {
                    $child->overdue_count = $overdueByChild[$child->id];
                    return $child;
                });
        }

        return view('admin.payments', compact('payments', 'paymentCounts', 'drivers', 'repeatOffenders'));
    }

    public function unassignChild($id)
    {
        Child::findOrFail($id)->update(['driver_id' => null]);

        return redirect()->route('admin.payments')->with('success', 'Child has been removed from the driver.');
    }

    public function sosIndex()
    {
        $messages = SosMessage::with('driver.user')
            ->where('deleted_by_admin', false)
            ->where('created_at', '>=', now()->subHours(24))
            ->orderBy('created_at', 'desc')
            ->get();
        return view('admin.sos', compact('messages'));
    }

    public function sosDestroy($id)
    {
        $sos = SosMessage::findOrFail($id);
        $sos->deleted_by_admin = true;
        $sos->save();

        if ($sos->deleted_by_parent) {
            $filePath = public_path('sos-audio/' . $sos->audio_path);
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
            $sos->delete();
        }

        return redirect()->route('admin.sos')->with('success', 'SOS message deleted.');
    }

    public function recommendDriver($parentId)
    {
        $parent = Parents::with('children')->find($parentId);

        if (!$parent) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Parent not found.',
            ]);
        }

        // Collect unique (city, district) pairs from children (case-insensitive keys)
        $locationPairs = $parent->children
            ->filter(fn($c) => $c->city && $c->district)
            ->map(fn($c) => [
                'city_key'     => strtolower(trim($c->city)),
                'district_key' => strtolower(trim($c->district)),
                'city'         => trim($c->city),
                'district'     => trim($c->district),
            ])
            ->unique(fn($l) => $l['city_key'] . '|' . $l['district_key'])
            ->values();

        if ($locationPairs->isEmpty()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'No children have city and district set yet. Ask the parent to update each child\'s location in their profile.',
            ]);
        }

        $allDrivers = Driver::with('user')
            ->whereHas('verification', fn($q) => $q->where('ver_status', 'Approved'))
            ->get();

        // Match drivers using case-insensitive comparison (also tolerates city/district swapped entry)
        $matchingDrivers = $allDrivers->filter(function ($driver) use ($locationPairs) {
            if (!$driver->city || !$driver->district) return false;
            $dc = strtolower(trim($driver->city));
            $dd = strtolower(trim($driver->district));
            return $locationPairs->contains(function ($l) use ($dc, $dd) {
                return ($l['city_key'] === $dc && $l['district_key'] === $dd)
                    || ($l['city_key'] === $dd && $l['district_key'] === $dc);
            });
        });

        if ($matchingDrivers->isEmpty()) {
            $locations = $locationPairs->map(fn($l) => ucfirst($l['city']) . ', ' . ucfirst($l['district']))->implode(' / ');
            return response()->json([
                'status'  => 'none',
                'message' => "No approved drivers found in {$locations}. Try assigning manually.",
            ]);
        }

        $results = $matchingDrivers->values()->map(function ($driver) {
            return [
                'name'       => $driver->user->name,
                'vrn'        => $driver->VRN,
                'city'       => $driver->city,
                'district'   => $driver->district,
                'passengers' => Child::where('driver_id', $driver->id)->count(),
            ];
        });

        $locationStr = $locationPairs->map(fn($l) => ucfirst($l['city']) . ', ' . ucfirst($l['district']))->implode(' / ');

        return response()->json([
            'status'   => 'found',
            'location' => $locationStr,
            'drivers'  => $results,
        ]);
    }
}
