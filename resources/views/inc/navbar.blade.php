<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-3 mb-4">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center fw-bold fs-4" href="{{ route('dashboard') }}">
            <span class="me-2" style="display:inline-block;vertical-align:middle;">
                <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="36" height="36" rx="8" fill="#1976d2"/>
                    <text x="50%" y="55%" text-anchor="middle" fill="#fff" font-size="18" font-family="Arial, sans-serif" dy=".3em">IM</text>
                </svg>
            </span>
            {{ env('APP_NAME', 'Inventory Management System') }}
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                @auth
                    <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('item') }}">Items</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('supplier') }}">Suppliers</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('category') }}">Category</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('department') }}">Department</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('borrower') }}">Borrower</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('depreciation.report') }}">Depreciation Report</a></li>
                @endauth
            </ul>
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                @guest
                    <li class="nav-item"><a class="nav-link" href="{{ route('index') }}">Sign in</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Sign up</a></li>
                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle me-1"></i>{{ Auth::user() ? Auth::user()->name : '' }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="{{ route('profile') }}"><i class="bi bi-person"></i> Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('logout') }}"><i class="bi bi-box-arrow-right"></i> Log out</a></li>
                        </ul>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
