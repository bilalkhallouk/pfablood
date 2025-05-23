<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'PFA Blood') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .navbar-brand {
            font-weight: 700;
            color: #dc3545 !important;
        }
        .nav-link {
            font-weight: 500;
        }
        .dropdown-item.active, 
        .dropdown-item:active {
            background-color: #dc3545;
        }
        /* Hide sidebar */
        .sidebar {
            display: none !important;
        }
        /* Ensure main content takes full width */
        .col-md-9,
        .col-lg-10,
        .ms-sm-auto {
            flex: 0 0 100% !important;
            max-width: 100% !important;
            margin-left: 0 !important;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="fas fa-tint text-danger me-2"></i>PFA Blood
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav me-auto">
                    
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ms-auto">
                    <!-- Authentication Links -->
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Register</a>
                        </li>
                    @else
                        @if(Auth::check() && Auth::user()->role === 'patient')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('patient.dashboard') }}">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('donors.search') }}">Find Donors</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('centers.nearby') }}">Blood Centers</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('patient.notifications') }}">
                                    <i class="fas fa-bell me-1"></i>Notifications
                                </a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link position-relative" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-bell"></i>
                                    @php
                                        $unreadCount = \App\Models\Notification::where('user_id', Auth::id())->where('read', false)->count();
                                        $notifications = \App\Models\Notification::where('user_id', Auth::id())->orderByDesc('created_at')->take(10)->get();
                                    @endphp
                                    @if($unreadCount > 0)
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                            {{ $unreadCount }}
                                        </span>
                                    @endif
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown" style="min-width: 350px; max-width: 400px;">
                                    <li class="dropdown-header">Notifications</li>
                                    @forelse($notifications as $notification)
                                        <li class="px-3 py-2 border-bottom {{ $notification->read ? '' : 'bg-light' }}">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span>{!! $notification->message !!}</span>
                                                <small class="text-muted ms-2" style="font-size: 0.8em;">{{ $notification->created_at->diffForHumans() }}</small>
                                            </div>
                                        </li>
                                    @empty
                                        <li class="px-3 py-2 text-muted">Aucune notification.</li>
                                    @endforelse
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-center view-all-notifications" href="#">Voir tout</a></li>
                                </ul>
                            </li>
                        @elseif(Auth::check() && Auth::user()->role === 'donor')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('donor.dashboard') }}">
                                    <i class="fas fa-home me-1"></i>Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('donor.appointments') }}">
                                    <i class="fas fa-calendar-alt me-1"></i>Appointments
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('centers.nearby') }}">
                                    <i class="fas fa-hospital me-1"></i>Donation Centers
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('donor.rewards') }}">
                                    <i class="fas fa-award me-1"></i>Rewards
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link position-relative" href="{{ route('donor.emergency-requests') }}">
                                    <i class="fas fa-heartbeat me-1"></i>Emergency Requests
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                        {{ Auth::user()->unreadEmergencyRequests()->count() }}
                                    </span>
                                </a>
                            </li>
                        @elseif(Auth::check() && Auth::user()->role === 'admin')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                    <i class="fas fa-chart-line me-1"></i>Tableau de bord
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.users') }}">
                                    <i class="fas fa-users me-1"></i>Gestion des utilisateurs
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.centers') }}">
                                    <i class="fas fa-hospital me-1"></i>Centres de don
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.events') }}">
                                    <i class="fas fa-calendar-alt me-1"></i>Événements
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.statistics') }}">
                                    <i class="fas fa-chart-bar me-1"></i>Statistiques
                                </a>
                            </li>
                        @endif
                        
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile.show') }}">
                                        <i class="fas fa-user me-2"></i>Profile
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('settings.show') }}">
                                        <i class="fas fa-cog me-2"></i>Settings
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="py-4">
        @if(session('success'))
            <div class="container">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="container">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-light mt-auto py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0">&copy; {{ date('Y') }} PFA Blood. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <p class="mb-0">Made with <i class="fas fa-heart text-danger"></i> to save lives</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
    @if(Auth::check() && Auth::user()->role === 'patient')
    @push('scripts')
    <script>
    document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('view-all-notifications')) {
            e.preventDefault();
            fetch("{{ route('patient.notifications.markRead') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
            }).finally(function() {
                window.location.href = "{{ route('patient.notifications') }}";
            });
        }
    });
    </script>
    @endpush
    @endif
</body>
</html> 