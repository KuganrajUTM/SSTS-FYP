<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment;
use App\Models\Ann;
use App\Models\SosMessage;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        View::composer('*', function ($view) {
            $view->with('userRole', Session::get('user_role'));
            $view->with('userName', Session::get('user_name'));

            $navDots = ['payment' => false, 'announcements' => false, 'sos' => false];

            if (Auth::check()) {
                $role = Auth::user()->role;

                if ($role === 'P') {
                    $parent = Auth::user()->parent;
                    if ($parent) {
                        $navDots['payment'] = Payment::where('parent_id', $parent->id)
                            ->whereIn('pay_status', ['Pending', 'Overdue'])
                            ->exists();
                    }
                    $navDots['announcements'] = Ann::where('created_at', '>=', now()->subDays(7))->exists();
                }

                if ($role === 'D') {
                    $navDots['announcements'] = Ann::where('created_at', '>=', now()->subDays(7))->exists();
                }

                if ($role === 'A') {
                    $navDots['sos'] = SosMessage::where('deleted_by_admin', false)
                        ->where('created_at', '>=', now()->subHours(24))
                        ->exists();
                }
            }

            $view->with('navDots', $navDots);
        });
    }
}
