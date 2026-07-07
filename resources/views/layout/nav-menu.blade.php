<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion" style="background-color: var(--navy);">
        <div class="sb-sidenav-menu">
            <div class="nav">

                <div class="sb-sidenav-menu-heading" style="color: var(--emerald); font-family: 'Syne', sans-serif; opacity: 0.8; letter-spacing: 1px; font-weight: 700;">Menu</div>
                
                <a class="nav-link ssts-nav-link" href="{{ route('main') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard
                </a>

                @if($userRole == 'P')
                    <a class="nav-link ssts-nav-link" href="{{ route('parent_pay') }}">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-sack-dollar"></i></div>
                        Payment
                    </a>
                    <a class="nav-link ssts-nav-link" href="{{ route('receipt') }}">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-file-invoice-dollar"></i></div>
                        Receipt
                    </a>
                    <a class="nav-link ssts-nav-link" href="{{ route('ann') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-bullhorn"></i></div>
                        Announcements
                    </a>
                    <a class="nav-link ssts-nav-link" href="{{ route('parent.sos') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-exclamation-triangle" style="color:#e74c3c !important;"></i></div>
                        SOS Messages
                    </a>
                    <a class="nav-link ssts-nav-link" href="{{ route('feedback.parent') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-comment-dots"></i></div>
                        Feedback
                    </a>
                @elseif($userRole == 'D')
                    <a class="nav-link ssts-nav-link" href="{{ route('ann') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-bullhorn"></i></div>
                        Announcements
                    </a>
                    <a class="nav-link ssts-nav-link" href="{{ route('driver.salary') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-money-bill-wave"></i></div>
                        My Salary
                    </a>
                    <a class="nav-link ssts-nav-link" href="{{ route('feedback.driver') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-comment-dots"></i></div>
                        Complaints
                    </a>
                @elseif($userRole == 'A')
                    <a class="nav-link ssts-nav-link" href="{{ route('driver_verification') }}">
                        <div class="sb-nav-link-icon"><i class="fa-regular fa-file"></i></div>
                        Verification
                    </a>
                    <a class="nav-link ssts-nav-link" href="{{ route('vehicles.index') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-bus"></i></div>
                        Vehicle List
                    </a>
                    <a class="nav-link ssts-nav-link" href="{{ route('driver-pay') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-credit-card"></i></div>
                        Issue Payment
                    </a>
                    <a class="nav-link ssts-nav-link" href="{{ route('admin.payments') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-file-invoice-dollar"></i></div>
                        Payment Records
                    </a>
                    <a class="nav-link ssts-nav-link" href="{{ route('admin.salary') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-money-bill-wave"></i></div>
                        Driver Salary
                    </a>
                    <a class="nav-link ssts-nav-link" href="{{ route('admin.sos') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-exclamation-triangle" style="color:#e74c3c !important;"></i></div>
                        SOS Inbox
                    </a>
                    <a class="nav-link ssts-nav-link" href="{{ route('admin.driver-keys') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-key"></i></div>
                        Driver Keys
                    </a>
                    <a class="nav-link ssts-nav-link" href="{{ route('admin.feedback') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-comment-dots"></i></div>
                        Feedback
                    </a>
                @endif
        
            </div>
        </div>
        
        <div class="sb-sidenav-footer" style="background-color: rgba(0,0,0,0.3); color: rgba(255,255,255,0.5); font-size: 0.75rem;">
            <div class="small">Logged in as:</div>
            <span style="color: var(--emerald); font-weight: 600;">{{ Auth::user()->username ?? 'User' }}</span>
        </div>
    </nav>
</div>

<style>
    .ssts-nav-link .sb-nav-link-icon {
        color: var(--emerald) !important;
        transition: transform 0.3s ease;
    }

    .ssts-nav-link {
        transition: all 0.2s ease !important;
        border-left: 3px solid transparent;
        color: rgba(255,255,255,0.7) !important;
    }

    .ssts-nav-link:hover {
        background-color: rgba(0, 184, 148, 0.1) !important;
        color: var(--white) !important;
        border-left: 3px solid var(--emerald);
    }

    /* Red styling specifically for Logout */
    .logout-link {
        color: #ff7675 !important;
        transition: all 0.2s ease !important;
        border-left: 3px solid transparent;
    }

    .logout-link .sb-nav-link-icon {
        color: #ff7675 !important;
    }

    .logout-link:hover {
        background-color: rgba(255, 118, 117, 0.1) !important;
        color: #fab1a0 !important;
        border-left: 3px solid #ff7675;
    }
</style>