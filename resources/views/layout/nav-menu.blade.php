<!-- resources/views/layouts/sidebar.blade.php -->
<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">

                <div class="sb-sidenav-menu-heading">Menu</div>
                <a class="nav-link" href="{{ route('main') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard
                </a>

                @if($userRole == 'P')
                    <a class="nav-link" href="{{ route('parent_pay') }}">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-sack-dollar"></i></div>
                        Payment
                    </a>
                    <a class="nav-link" href="{{ route('receipt') }}">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-file-invoice-dollar"></i></div>
                        Receipt
                    </a>
                    <a class="nav-link" href="{{ route('ann') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-bullhorn"></i></div>
                        Announcements
                    </a>
                @elseif($userRole == 'D')
                    <a class="nav-link" href="{{ route('parent_pay') }}">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-sack-dollar"></i></div>
                        Payment
                    </a>
                    <a class="nav-link" href="{{ route('driver-pay') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-credit-card"></i></div>
                            Issue Payment
                    </a>
                    <a class="nav-link" href="{{ route('schedules.index') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-calendar-alt"></i></div>
                            Schedule
                    </a>
                    <a class="nav-link" href="{{ route('ann') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-bullhorn"></i></div>
                        Announcements
                    </a>
                @elseif($userRole == 'A')
                    <a class="nav-link" href="{{ route('driver_verification') }}">
                        <div class="sb-nav-link-icon"><i class="fa-regular fa-file"></i></div>
                        Verification
                    </a>
                @endif
        
            </div>
        </div>
    </nav>
</div>
