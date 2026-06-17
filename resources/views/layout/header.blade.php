<nav class="sb-topnav navbar navbar-expand navbar-dark" style="background-color: var(--navy); border-bottom: 2px solid var(--emerald);">
    <a class="navbar-brand ps-3 d-flex align-items-center gap-2" href="{{ route('home') }}" style="font-family: 'Syne', sans-serif; font-weight: 800;">
        <img src="{{ asset('assets/img/photo_2024-10-22_11-35-22-Photoroom.png') }}" 
             alt="logo" 
             style="width: 40px; height: auto; filter: drop-shadow(0 0 4px rgba(0,184,148,0.4));">
        <span>SS<span style="color: var(--emerald);">TS</span></span>
    </a>

    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!" style="color: var(--emerald);">
        <i class="fas fa-bars"></i>
    </button>

    <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
        <div class="input-group">
            <input class="form-control" type="text" placeholder="Search..." 
                   style="background: rgba(255,255,255,0.1); border: 1px solid rgba(0,184,148,0.2); color: white;">
            <button class="btn" type="button" style="background-color: var(--emerald); color: white;">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </form>
    
    <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: var(--emerald);">
                <i class="fas fa-user fa-fw"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow-lg" aria-labelledby="userDropdown" style="border: 1px solid var(--emerald); background-color: #ffffff; min-width: 150px; margin-top: 10px;">
                <li>
                    <a class="dropdown-item py-2" href="{{ route('profile.show') }}">
                        <i class="fas fa-user-circle me-2" style="color: var(--navy);"></i> Profile
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item py-2" href="{{ route('login') }}" style="color: #e74c3c;">
                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var dropdownElement = document.getElementById('userDropdown');
        if (dropdownElement) {
            dropdownElement.addEventListener('click', function (e) {
                e.preventDefault();
                var menu = this.nextElementSibling;
                // Check if Bootstrap's JS is working, if not, manually toggle
                if (!menu.classList.contains('show')) {
                    menu.classList.add('show');
                    menu.setAttribute('data-bs-popper', 'static');
                } else {
                    menu.classList.remove('show');
                }
            });

            // Close when clicking outside
            document.addEventListener('click', function (e) {
                if (!dropdownElement.contains(e.target)) {
                    dropdownElement.nextElementSibling.classList.remove('show');
                }
            });
        }
    });
</script>