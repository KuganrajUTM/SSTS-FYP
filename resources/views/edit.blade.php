@extends('layout.main-template')

@section('title', 'Edit Profile')
@section('content')
<style>
    /* Theme Colors & Modern Variables */
    :root {
        --emerald: #2ecc71;
        --emerald-dark: #27ae60;
        --navy: #2c3e50;
        --light-gray: #f8f9fa;
        --border-color: #dee2e6;
    }

    /* Notification Overlay */
    .overlay-notification {
        position: fixed;
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
        background-color: var(--emerald);
        color: #fff;
        padding: 15px 25px;
        border-radius: 50px;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        z-index: 2000;
        display: none;
        font-weight: 600;
        animation: fadeInOut 5s forwards;
    }

    /* Card & Container Styling */
    .profile-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .profile-card .card-header {
        background-color: var(--navy);
        color: white;
        padding: 20px;
        border: none;
    }

    .section-title {
        color: var(--navy);
        font-weight: 700;
        border-bottom: 2px solid var(--emerald);
        display: inline-block;
        padding-bottom: 5px;
        margin-bottom: 25px;
        text-transform: uppercase;
        font-size: 0.9rem;
        letter-spacing: 1px;
    }

    /* Form Elements */
    .form-label {
        font-weight: 600;
        color: var(--navy);
    }

    .form-control {
        border-radius: 8px;
        padding: 10px 15px;
        border: 1px solid var(--border-color);
        transition: all 0.3s;
    }

    .form-control:focus {
        border-color: var(--emerald);
        box-shadow: 0 0 0 0.2rem rgba(46, 204, 113, 0.15);
    }

    /* Child Entry Styling */
    .child-entry {
        background-color: var(--light-gray);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        transition: transform 0.2s;
    }

    .child-entry:hover {
        transform: translateY(-2px);
    }

    /* Buttons */
    .btn-emerald {
        background-color: var(--emerald);
        color: white;
        border-radius: 8px;
        padding: 10px 25px;
        font-weight: 600;
        border: none;
        transition: background 0.3s;
    }

    .btn-emerald:hover {
        background-color: var(--emerald-dark);
        color: white;
    }

    .btn-outline-navy {
        border: 2px solid var(--navy);
        color: var(--navy);
        border-radius: 8px;
        font-weight: 600;
    }

    .btn-outline-navy:hover {
        background-color: var(--navy);
        color: white;
    }

    @keyframes fadeInOut {
        0% { opacity: 0; transform: translateY(-20px) translateX(-50%); }
        10% { opacity: 1; transform: translateY(0) translateX(-50%); }
        90% { opacity: 1; transform: translateY(0) translateX(-50%); }
        100% { opacity: 0; transform: translateY(-20px) translateX(-50%); }
    }

    gmp-placeautocomplete { display: block; width: 100%; }
    gmp-placeautocomplete::part(input) {
        width: 100%; border-radius: 8px; padding: 10px 15px;
        border: 1px solid var(--border-color); font-family: inherit;
        font-size: 1rem; color: var(--navy); background: white;
        transition: border-color 0.3s, box-shadow 0.3s; box-sizing: border-box;
    }
    gmp-placeautocomplete::part(input):focus {
        border-color: var(--emerald);
        box-shadow: 0 0 0 0.2rem rgba(46, 204, 113, 0.15);
        outline: none;
    }
</style>

<div class="container my-5">
    @if (session('success'))
        <div class="overlay-notification" id="successNotification">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card profile-card">
                <div class="card-header text-center">
                    <h3 class="mb-0">Update Profile Information</h3>
                </div>
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        @if ($user->role === 'P')
                            <h5 class="section-title">Parent Settings</h5>
                            
                            <div class="row mb-3 align-items-center">
                                <div class="col-md-3">
                                    <label for="name" class="form-label">Full Name</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}">
                                    @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <div class="col-md-3">
                                    <label for="email" class="form-label">Email Address</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}">
                                    @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>

                            <div class="row mb-4 align-items-center">
                                <div class="col-md-3">
                                    <label for="phone" class="form-label">Mobile Number</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" name="phone" class="form-control" placeholder="e.g. 012-3456789" value="{{ old('phone', $parent->phone) }}">
                                    @error('phone') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>

                            <div class="row mb-4 align-items-center">
                                <div class="col-md-3">
                                    <label for="location" class="form-label">Location</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="hidden" id="edit-location" name="location" value="{{ old('location', $parent->location) }}">
                                    <div id="edit-location-pac"></div>
                                    @error('location') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>

                            <div class="row mb-4 align-items-center">
                                <div class="col-md-3">
                                    <label class="form-label">City <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" id="edit-city" name="city" class="form-control" placeholder="e.g. Johor Bahru" value="{{ old('city', $parent->city) }}" autocomplete="off">
                                    @error('city') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>

                            <div class="row mb-5 align-items-center">
                                <div class="col-md-3">
                                    <label class="form-label">District <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" id="edit-district" name="district" class="form-control" placeholder="e.g. Skudai" value="{{ old('district', $parent->district) }}" autocomplete="off">
                                    @error('district') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>

                            <h5 class="section-title">Children & Dependents</h5>
                            <div id="children-list">
                                @foreach ($children as $child)
                                    <div class="child-entry mb-4 shadow-sm">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <span class="fw-bold" style="color: var(--navy);">{{ $child->name ?: 'Child' }}</span>
                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeChild(this)">
                                                <i class="fas fa-trash-alt me-1"></i> Remove
                                            </button>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-3">
                                                <label class="form-label">Child Name</label>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="text" name="children[{{ $child->id }}][name]" class="form-control bg-white" value="{{ old('children.'.$child->id.'.name', $child->name) }}">
                                                @error('children.'.$child->id.'.name') <small class="text-danger">{{ $message }}</small> @enderror
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-3">
                                                <label class="form-label">School Name</label>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="text" name="children[{{ $child->id }}][school_name]" class="form-control bg-white" value="{{ old('children.'.$child->id.'.school_name', $child->school_name) }}">
                                                @error('children.'.$child->id.'.school_name') <small class="text-danger">{{ $message }}</small> @enderror
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-3">
                                                <label class="form-label">City</label>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="text" name="children[{{ $child->id }}][city]" class="form-control bg-white" placeholder="e.g. Port Dickson" value="{{ old('children.'.$child->id.'.city', $child->city) }}">
                                                @error('children.'.$child->id.'.city') <small class="text-danger">{{ $message }}</small> @enderror
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-3">
                                                <label class="form-label">District</label>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="text" name="children[{{ $child->id }}][district]" class="form-control bg-white" placeholder="e.g. Lukut" value="{{ old('children.'.$child->id.'.district', $child->district) }}">
                                                @error('children.'.$child->id.'.district') <small class="text-danger">{{ $message }}</small> @enderror
                                            </div>
                                        </div>

                                    </div>
                                @endforeach
                            </div>
                            <button type="button" id="add-child" class="btn btn-outline-navy btn-sm mt-2">
                                <i class="fas fa-plus-circle me-1"></i> Add Another Child
                            </button>
                        @endif

                        @if ($user->role === 'D')
                            <h5 class="section-title">Driver Settings</h5>
                            <div class="mb-3">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}">
                                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}">
                                @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Vehicle Information (VRN)</label>
                                <input type="text" name="vehicle_info" class="form-control" value="{{ old('vehicle_info', auth()->user()->driver->VRN) }}" />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">City</label>
                                <input type="hidden" id="driver-city" name="city" value="{{ old('city', $driver->city) }}">
                                <div id="driver-city-pac"></div>
                                @error('city') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">District</label>
                                <input type="text" id="driver-district" name="district" class="form-control" value="{{ old('district', $driver->district) }}" placeholder="e.g. Skudai" autocomplete="off">
                                @error('district') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Bank Name</label>
                                <select name="bank_name" class="form-control">
                                    <option value="">-- Select Bank --</option>
                                    @php
                                        $malaysianBanks = [
                                            'Maybank', 'CIMB Bank', 'Public Bank', 'RHB Bank', 'Hong Leong Bank',
                                            'AmBank', 'Bank Islam', 'Bank Rakyat', 'Bank Muamalat', 'Affin Bank',
                                            'Alliance Bank', 'OCBC Bank', 'Standard Chartered', 'HSBC Bank',
                                            'UOB Malaysia', 'Citibank', 'Bank Simpanan Nasional (BSN)',
                                            'Agrobank', 'MBSB Bank', 'Kuwait Finance House (KFH)',
                                        ];
                                    @endphp
                                    @foreach($malaysianBanks as $bank)
                                        <option value="{{ $bank }}" {{ old('bank_name', $driver->bank_name) === $bank ? 'selected' : '' }}>
                                            {{ $bank }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('bank_name') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Account Number</label>
                                <input type="text" name="bank_account_number" class="form-control" value="{{ old('bank_account_number', $driver->bank_account_number) }}" placeholder="e.g. 1234567890">
                                @error('bank_account_number') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        @endif

                        @if($user->role === 'A')
                            <h5 class="section-title">Admin Settings</h5>
                            <div class="mb-3">
                                <label class="form-label">👤 Name</label>
                                <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">📧 Email</label>
                                <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                            </div>
                        @endif

                        <hr class="my-4">
                        <div class="text-end">
                            <button type="submit" class="btn btn-emerald px-5 shadow-sm">
                                <i class="fas fa-save me-2"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const _addChild = document.getElementById('add-child');
    if (_addChild) { _addChild.addEventListener('click', function () {
        const container = document.getElementById('children-list');
        const childCount = container.children.length;

        const newChild = `
            <div class="child-entry shadow-sm border rounded p-4 mb-3" style="background: #fff; border-left: 5px solid var(--emerald) !important;">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="fw-bold" style="color: var(--navy);">New Child</span>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeChild(this)">
                        <i class="fas fa-trash-alt me-1"></i> Remove
                    </button>
                </div>
                <div class="row mb-3">
                    <div class="col-md-3"><label class="form-label">Child Name:</label></div>
                    <div class="col-md-9">
                        <input type="text" name="children[new_${childCount}][name]" class="form-control" value="">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-3"><label class="form-label">School Name:</label></div>
                    <div class="col-md-9">
                        <input type="text" name="children[new_${childCount}][school_name]" class="form-control" value="">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-3"><label class="form-label">City:</label></div>
                    <div class="col-md-9">
                        <input type="text" name="children[new_${childCount}][city]" class="form-control" placeholder="e.g. Port Dickson">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3"><label class="form-label">District:</label></div>
                    <div class="col-md-9">
                        <input type="text" name="children[new_${childCount}][district]" class="form-control" placeholder="e.g. Lukut">
                    </div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', newChild);
    }); }

    function removeChild(btn) {
        if (!confirm('Remove this child? This will be permanent when you save.')) return;
        btn.closest('.child-entry').remove();
    }

    document.addEventListener('DOMContentLoaded', function () {
        const notification = document.getElementById('successNotification');
        if (notification) {
            notification.style.display = 'block';
            setTimeout(() => {
                notification.style.opacity = '0';
                setTimeout(() => notification.style.display = 'none', 500);
            }, 5000);
        }
    });

    async function initEditMap() {
        const { PlaceAutocompleteElement } = await google.maps.importLibrary("places");

        // Parent: location → auto-fill city + district
        const locHidden = document.getElementById('edit-location');
        const locPac    = document.getElementById('edit-location-pac');
        if (locPac) {
            const pac = new PlaceAutocompleteElement({
                componentRestrictions: { country: 'my' },
                inputValue: locHidden ? locHidden.value : ''
            });
            locPac.appendChild(pac);
            pac.addEventListener('gmp-placeselect', async function ({ place }) {
                await place.fetchFields({ fields: ['addressComponents', 'formattedAddress'] });
                if (locHidden) locHidden.value = place.formattedAddress;
                let city = '', district = '';
                (place.addressComponents || []).forEach(function (c) {
                    if (c.types.includes('locality'))                    city     = c.longText;
                    if (c.types.includes('administrative_area_level_2')) district = c.longText;
                });
                const cityEl = document.getElementById('edit-city');
                const distEl = document.getElementById('edit-district');
                if (cityEl && city)     cityEl.value = city;
                if (distEl && district) distEl.value = district;
            });
        }

        // Driver: city → auto-fill district
        const driverCityHidden = document.getElementById('driver-city');
        const driverCityPac    = document.getElementById('driver-city-pac');
        if (driverCityPac) {
            const pac = new PlaceAutocompleteElement({
                componentRestrictions: { country: 'my' },
                types: ['(cities)'],
                inputValue: driverCityHidden ? driverCityHidden.value : ''
            });
            driverCityPac.appendChild(pac);
            pac.addEventListener('gmp-placeselect', async function ({ place }) {
                await place.fetchFields({ fields: ['addressComponents', 'formattedAddress'] });
                if (driverCityHidden) {
                    let city = '';
                    (place.addressComponents || []).forEach(function (c) {
                        if (c.types.includes('locality')) city = c.longText;
                    });
                    driverCityHidden.value = city || place.formattedAddress;
                }
                let district = '';
                (place.addressComponents || []).forEach(function (c) {
                    if (c.types.includes('administrative_area_level_2')) district = c.longText;
                });
                const distEl = document.getElementById('driver-district');
                if (distEl && district) distEl.value = district;
            });
        }
    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&callback=initEditMap&loading=async" async defer></script>
@endsection