@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 d-md-block bg-danger sidebar collapse">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-chart-line me-2"></i>
                            Tableau de bord
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('admin.users') }}">
                            <i class="fas fa-users me-2"></i>
                            Gestion des utilisateurs
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('admin.centers') }}">
                            <i class="fas fa-hospital me-2"></i>
                            Centres de don
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('admin.events') }}">
                            <i class="fas fa-calendar-alt me-2"></i>
                            Événements
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white active" href="{{ route('admin.statistics') }}">
                            <i class="fas fa-chart-bar me-2"></i>
                            Statistiques
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main content -->
        <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Statistiques</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="window.print()">
                            <i class="fas fa-print"></i> Imprimer
                        </button>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <div class="bg-danger bg-opacity-10 p-3 rounded">
                                        <i class="fas fa-users fa-2x text-danger"></i>
                                    </div>
                                </div>
                                <div>
                                    <h6 class="card-title mb-0">Utilisateurs</h6>
                                    <h2 class="mb-0">{{ $stats['total_users'] }}</h2>
                                    <div class="small text-muted">
                                        <span class="text-success">
                                            <i class="fas fa-user"></i> {{ $stats['total_donors'] }} Donneurs
                                        </span>
                                        |
                                        <span class="text-primary">
                                            <i class="fas fa-user"></i> {{ $stats['total_patients'] }} Patients
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <div class="bg-success bg-opacity-10 p-3 rounded">
                                        <i class="fas fa-tint fa-2x text-success"></i>
                                    </div>
                                </div>
                                <div>
                                    <h6 class="card-title mb-0">Dons de sang</h6>
                                    <h2 class="mb-0">{{ $stats['total_donations'] }}</h2>
                                    <div class="small text-muted">
                                        Dons effectués
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <div class="bg-primary bg-opacity-10 p-3 rounded">
                                        <i class="fas fa-hospital fa-2x text-primary"></i>
                                    </div>
                                </div>
                                <div>
                                    <h6 class="card-title mb-0">Centres & Événements</h6>
                                    <h2 class="mb-0">{{ $stats['total_centers'] }}</h2>
                                    <div class="small text-muted">
                                        {{ $stats['total_events'] }} événements organisés
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detailed Statistics -->
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0">
                            <h5 class="card-title mb-0">Statistiques des donneurs</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="donorsChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0">
                            <h5 class="card-title mb-0">Dons par groupe sanguin</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="bloodTypeChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">Activité récente</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Utilisateur</th>
                                    <th>Action</th>
                                    <th>Détails</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Add your activity logs here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Sample charts - Replace with real data
const donorsCtx = document.getElementById('donorsChart').getContext('2d');
new Chart(donorsCtx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        datasets: [{
            label: 'Nouveaux donneurs',
            data: [12, 19, 3, 5, 2, 3],
            borderColor: '#dc3545',
            tension: 0.1
        }]
    }
});

const bloodTypeCtx = document.getElementById('bloodTypeChart').getContext('2d');
new Chart(bloodTypeCtx, {
    type: 'pie',
    data: {
        labels: ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'],
        datasets: [{
            data: [12, 19, 3, 5, 2, 3, 7, 4],
            backgroundColor: [
                '#dc3545', '#198754', '#0d6efd', '#ffc107',
                '#fd7e14', '#6f42c1', '#20c997', '#e83e8c'
            ]
        }]
    }
});
</script>
@endpush 