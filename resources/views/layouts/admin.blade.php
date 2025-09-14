<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Aplikasi Sewa Alat Berat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @yield('styles')
</head>
<body>
    <div class="d-flex flex-column flex-lg-row">
        <!-- Sidebar -->
        <nav id="sidebar" class="bg-dark text-white" style="width: 250px; min-height: 100vh; position: fixed; left: 0; top: 0; overflow-y: auto; transition: transform 0.3s ease;">
            <div class="p-3 d-flex justify-content-between align-items-center">
                <h5 class="text-center mb-4">Admin Dashboard</h5>
                <button class="btn btn-outline-light d-lg-none" id="sidebarToggle" aria-label="Toggle navigation">
                    <i class="bi bi-list"></i>
                </button>
            </div>
            <ul class="nav flex-column px-3">
                <li class="nav-item mb-2">
                    <a class="nav-link text-white" href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-house-door"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link text-white" href="{{ route('admin.equipments') }}">
                        <i class="bi bi-tools"></i> Equipments
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link text-white" href="{{ route('admin.transportations.index') }}">
                        <i class="bi bi-truck"></i> Transportasi
                    </a>
                </li>
                @if(Auth::user()->role === 'super_admin')
                <li class="nav-item mb-2">
                    <a class="nav-link text-white" href="{{ route('admin.users') }}">
                        <i class="bi bi-people"></i> Users
                    </a>
                </li>
                @endif
                @if(Auth::user()->role === 'kepala_dinas')
                <li class="nav-item mb-2">
                    <a class="nav-link text-white" href="{{ route('kepala-dinas.assignments.index') }}">
                        <i class="bi bi-person-check"></i> Penugasan Operator
                    </a>
                </li>
                @endif
                <li class="nav-item mb-2">
                    <a class="nav-link text-white" href="{{ route('admin.operators') }}">
                        <i class="bi bi-person-gear"></i> Operator Alat Berat
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link text-white" href="{{ route('admin.payments') }}">
                        <i class="bi bi-cash"></i> Pembayaran
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link text-white" href="{{ route('admin.jenis-sewa.index') }}">
                        <i class="bi bi-list-check"></i> Jenis Sewa Alat Berat
                    </a>
                </li>
                <hr class="my-3">
                <li class="nav-item">
                    <div class="dropdown">
                        <a class="nav-link text-white dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu bg-dark" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item text-white" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
                        </ul>
                    </div>
                </li>
            </ul>
        </nav>

        <!-- Main Content -->
        <main class="flex-grow-1" style="margin-left: 250px;">
            <div class="container-fluid py-4">
                @yield('content')
            </div>
        </main>
    </div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('sidebar');
            const toggleButton = document.getElementById('sidebarToggle');

            toggleButton.addEventListener('click', function () {
                if (sidebar.style.transform === 'translateX(-100%)') {
                    sidebar.style.transform = 'translateX(0)';
                } else {
                    sidebar.style.transform = 'translateX(-100%)';
                }
            });

            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function (event) {
                if (!sidebar.contains(event.target) && !toggleButton.contains(event.target) && window.innerWidth < 992) {
                    sidebar.style.transform = 'translateX(-100%)';
                }
            });

            // Reset sidebar style on window resize
            window.addEventListener('resize', function () {
                if (window.innerWidth >= 992) {
                    sidebar.style.transform = 'translateX(0)';
                } else {
                    sidebar.style.transform = 'translateX(-100%)';
                }
            });

            // Initialize sidebar state
            if (window.innerWidth < 992) {
                sidebar.style.transform = 'translateX(-100%)';
            }
        });
    </script>
    @yield('scripts')
</body>
</html>
