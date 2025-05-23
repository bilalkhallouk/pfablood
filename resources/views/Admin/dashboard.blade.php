@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Title -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Tableau de bord administrateur</h1>
    </div>

    <!-- Quick Stats -->
    <div class="row g-4 mb-4">
        <!-- Users Stats -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="bg-danger bg-opacity-10 p-3 rounded">
                                <i class="fas fa-users fa-2x text-danger"></i>
                            </div>
                        </div>
                        <div>
                            <h6 class="card-title mb-0">Total Utilisateurs</h6>
                            <h2 class="mb-0">{{ $totalUsers ?? 0 }}</h2>
                            <small class="text-muted">Tous les utilisateurs</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Donors Stats -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="bg-success bg-opacity-10 p-3 rounded">
                                <i class="fas fa-user-plus fa-2x text-success"></i>
                            </div>
                        </div>
                        <div>
                            <h6 class="card-title mb-0">Donneurs</h6>
                            <h2 class="mb-0">{{ $totalDonors ?? 0 }}</h2>
                            <small class="text-muted">Donneurs actifs</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Patients Stats -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="bg-info bg-opacity-10 p-3 rounded">
                                <i class="fas fa-user-injured fa-2x text-info"></i>
                            </div>
                        </div>
                        <div>
                            <h6 class="card-title mb-0">Patients</h6>
                            <h2 class="mb-0">{{ $totalPatients ?? 0 }}</h2>
                            <small class="text-muted">Patients enregistrés</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Blood Centers Stats -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="bg-primary bg-opacity-10 p-3 rounded">
                                <i class="fas fa-hospital fa-2x text-primary"></i>
                            </div>
                        </div>
                        <div>
                            <h6 class="card-title mb-0">Centres</h6>
                            <h2 class="mb-0">{{ $totalCenters ?? 0 }}</h2>
                            <small class="text-muted">Centres de don</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Actions rapides</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <a href="{{ route('admin.users') }}" class="btn btn-outline-danger w-100 p-4">
                                <i class="fas fa-users mb-2 fa-2x"></i>
                                <br>
                                Gestion des utilisateurs
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.events') }}" class="btn btn-outline-danger w-100 p-4">
                                <i class="fas fa-calendar-alt mb-2 fa-2x"></i>
                                <br>
                                Événements
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.centers') }}" class="btn btn-outline-danger w-100 p-4">
                                <i class="fas fa-hospital mb-2 fa-2x"></i>
                                <br>
                                Centres de don
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.statistics') }}" class="btn btn-outline-danger w-100 p-4">
                                <i class="fas fa-chart-bar mb-2 fa-2x"></i>
                                <br>
                                Statistiques
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.blood-requests') }}" class="btn btn-outline-danger w-100 p-4">
                                <i class="fas fa-tint mb-2 fa-2x"></i>
                                <br>
                                Demandes de sang
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Derniers utilisateurs</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Email</th>
                                    <th>Rôle</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentUsers ?? [] as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'donor' ? 'success' : 'primary') }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Prochains événements</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Événement</th>
                                    <th>Centre</th>
                                    <th>Date</th>
                                    <th>Participants</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($upcomingEvents ?? [] as $event)
                                <tr>
                                    <td>{{ $event->title }}</td>
                                    <td>{{ $event->center->name }}</td>
                                    <td>{{ $event->formatted_date }}</td>
                                    <td>
                                        <span class="badge bg-{{ $event->isFull() ? 'danger' : 'success' }}">
                                            {{ $event->participants->count() }}/{{ $event->capacity }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection