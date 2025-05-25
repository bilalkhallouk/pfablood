@extends('layouts.app')
@section('content')
<style>
    body {
        background: #f8f9fa;
    }
    .dashboard-card {
        border-radius: 1rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        transition: transform 0.2s;
    }
    .dashboard-card:hover {
        transform: translateY(-5px);
    }
    .stat-card {
        background: linear-gradient(45deg, #4e73df, #224abe);
        color: white;
    }
    .stat-card.warning {
        background: linear-gradient(45deg, #f6c23e, #dda20a);
    }
    .stat-card.danger {
        background: linear-gradient(45deg, #e74a3b, #be2617);
    }
    .stat-card.success {
        background: linear-gradient(45deg, #1cc88a, #13855c);
    }
    .stock-table th, .stock-table td {
        padding: 1rem;
        vertical-align: middle;
    }
    .progress {
        height: 8px;
        border-radius: 4px;
    }
    .chart-container {
        position: relative;
        height: 300px;
    }
    .search-box {
        border-radius: 2rem;
        padding: 0.5rem 1.5rem;
        border: 1px solid #e3e6f0;
    }
    .filter-dropdown {
        border-radius: 1rem;
        padding: 0.5rem 1rem;
    }
    .alert-badge {
        position: absolute;
        top: -5px;
        right: -5px;
        padding: 0.25rem 0.5rem;
        border-radius: 1rem;
        font-size: 0.75rem;
    }
</style>

<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 mb-0 text-gray-800">Gestion du Stock de Sang</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.blood-stocks.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Nouveau Stock
            </a>
            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
                <i class="fas fa-filter me-2"></i>Filtres
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card dashboard-card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Total Stock</h6>
                            <h3 class="mb-0 text-white">{{ $stocks->sum('units') }}</h3>
                        </div>
                        <i class="fas fa-tint fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card dashboard-card stat-card warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Stock Bas</h6>
                            <h3 class="mb-0 text-white">{{ $stocks->filter(function($stock) { 
                                return ($stock->units ?? $stock->units_available) < ($stock->min_threshold ?? $stock->minimum_threshold); 
                            })->count() }}</h3>
                        </div>
                        <i class="fas fa-exclamation-triangle fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card dashboard-card stat-card success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Centres Actifs</h6>
                            <h3 class="mb-0 text-white">{{ $centers->count() }}</h3>
                        </div>
                        <i class="fas fa-hospital fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card dashboard-card stat-card danger">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Urgences</h6>
                            <h3 class="mb-0 text-white">{{ $stocks->filter(function($stock) { 
                                return ($stock->units ?? $stock->units_available) < (($stock->min_threshold ?? $stock->minimum_threshold) * 0.5); 
                            })->count() }}</h3>
                        </div>
                        <i class="fas fa-heartbeat fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Bar -->
    <div class="card dashboard-card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control search-box" id="searchInput" placeholder="Rechercher...">
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-select filter-dropdown" id="bloodTypeFilter">
                        <option value="">Tous les groupes</option>
                        @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $type)
                            <option value="{{ $type }}">{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select filter-dropdown" id="statusFilter">
                        <option value="">Tous les statuts</option>
                        <option value="low">Stock Bas</option>
                        <option value="critical">Stock Critique</option>
                        <option value="normal">Stock Normal</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select filter-dropdown" id="sortFilter">
                        <option value="stock_asc">Stock ↑</option>
                        <option value="stock_desc">Stock ↓</option>
                        <option value="type_asc">Groupe A-Z</option>
                        <option value="type_desc">Groupe Z-A</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row g-4 mb-4">
        <div class="col-xl-8">
            <div class="card dashboard-card">
                <div class="card-body">
                    <h5 class="card-title mb-4">Distribution des Stocks par Groupe</h5>
                    <div class="chart-container">
                        <canvas id="stockDistributionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card dashboard-card">
                <div class="card-body">
                    <h5 class="card-title mb-4">Statut des Stocks</h5>
                    <div class="chart-container">
                        <canvas id="stockStatusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stock Table -->
    <div class="card dashboard-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table stock-table">
                    <thead>
                        <tr>
                            <th>Centre</th>
                            <th>Groupe Sanguin</th>
                            <th>Stock</th>
                            <th>Seuil</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stocks as $stock)
                            @php
                                $current = $stock->units ?? $stock->units_available;
                                $min = $stock->min_threshold ?? $stock->minimum_threshold;
                                $percent = $min > 0 ? ($current / $min) * 100 : 0;
                                $status = $percent < 50 ? 'critical' : ($percent < 100 ? 'low' : 'normal');
                                $statusClass = [
                                    'critical' => 'danger',
                                    'low' => 'warning',
                                    'normal' => 'success'
                                ][$status];
                            @endphp
                            <tr>
                                <td>{{ $stock->center->name }}</td>
                                <td>
                                    <span class="badge bg-primary">{{ $stock->blood_type }}</span>
                                </td>
                                <td style="width: 30%">
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1 me-2">
                                            <div class="progress-bar bg-{{ $statusClass }}" 
                                                 role="progressbar" 
                                                 style="width: {{ min($percent, 100) }}%">
                                            </div>
                                        </div>
                                        <span>{{ $current }}</span>
                                    </div>
                                </td>
                                <td>{{ $min }}</td>
                                <td>
                                    <span class="badge bg-{{ $statusClass }}">
                                        @if($status === 'critical')
                                            <i class="fas fa-exclamation-circle me-1"></i>Critique
                                        @elseif($status === 'low')
                                            <i class="fas fa-exclamation-triangle me-1"></i>Bas
                                        @else
                                            <i class="fas fa-check-circle me-1"></i>Normal
                                        @endif
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.blood-stocks.edit', $stock->id) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-danger"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteModal{{ $stock->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    Affichage de {{ $stocks->firstItem() ?? 0 }} à {{ $stocks->lastItem() ?? 0 }} 
                    sur {{ $stocks->total() }} entrées
                </div>
                {{ $stocks->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Filter Modal -->
<div class="modal fade" id="filterModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Filtres Avancés</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="advancedFilterForm">
                    <div class="mb-3">
                        <label class="form-label">Centre</label>
                        <select class="form-select" name="center_id">
                            <option value="">Tous les centres</option>
                            @foreach($centers as $center)
                                <option value="{{ $center->id }}">{{ $center->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Groupe Sanguin</label>
                        <select class="form-select" name="blood_type">
                            <option value="">Tous les groupes</option>
                            @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $type)
                                <option value="{{ $type }}">{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Statut</label>
                        <select class="form-select" name="status">
                            <option value="">Tous les statuts</option>
                            <option value="critical">Critique</option>
                            <option value="low">Bas</option>
                            <option value="normal">Normal</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Stock Minimum</label>
                        <input type="number" class="form-control" name="min_stock" min="0">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary" id="applyFilters">Appliquer</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Stock Distribution Chart
    const stockCtx = document.getElementById('stockDistributionChart').getContext('2d');
    new Chart(stockCtx, {
        type: 'bar',
        data: {
            labels: ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'],
            datasets: [{
                label: 'Stock Disponible',
                data: @json($stocks->groupBy('blood_type')->map->sum('units')),
                backgroundColor: [
                    '#e74a3b', '#6c757d', '#4e73df', '#1cc88a',
                    '#f6c23e', '#858796', '#36b9cc', '#6c757d'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Stock Status Chart
    const statusCtx = document.getElementById('stockStatusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Normal', 'Bas', 'Critique'],
            datasets: [{
                data: [
                    @json($stocks->filter(function($stock) {
                        return ($stock->units ?? $stock->units_available) >= ($stock->min_threshold ?? $stock->minimum_threshold);
                    })->count()),
                    @json($stocks->filter(function($stock) {
                        $current = $stock->units ?? $stock->units_available;
                        $min = $stock->min_threshold ?? $stock->minimum_threshold;
                        return $current < $min && $current >= ($min * 0.5);
                    })->count()),
                    @json($stocks->filter(function($stock) {
                        $current = $stock->units ?? $stock->units_available;
                        $min = $stock->min_threshold ?? $stock->minimum_threshold;
                        return $current < ($min * 0.5);
                    })->count())
                ],
                backgroundColor: ['#1cc88a', '#f6c23e', '#e74a3b'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Search and Filter Functionality
    const searchInput = document.getElementById('searchInput');
    const bloodTypeFilter = document.getElementById('bloodTypeFilter');
    const statusFilter = document.getElementById('statusFilter');
    const sortFilter = document.getElementById('sortFilter');

    function applyFilters() {
        const searchTerm = searchInput.value.toLowerCase();
        const bloodType = bloodTypeFilter.value;
        const status = statusFilter.value;
        const sort = sortFilter.value;

        const rows = document.querySelectorAll('tbody tr');
        rows.forEach(row => {
            const center = row.cells[0].textContent.toLowerCase();
            const bloodTypeCell = row.cells[1].textContent;
            const stock = parseInt(row.cells[2].textContent.trim());
            const statusCell = row.cells[4].textContent.toLowerCase();

            const matchesSearch = center.includes(searchTerm);
            const matchesBloodType = !bloodType || bloodTypeCell.includes(bloodType);
            const matchesStatus = !status || statusCell.includes(status);

            row.style.display = matchesSearch && matchesBloodType && matchesStatus ? '' : 'none';
        });

        // Apply sorting
        const tbody = document.querySelector('tbody');
        const rowsArray = Array.from(rows);
        rowsArray.sort((a, b) => {
            if (sort === 'stock_asc') {
                return parseInt(a.cells[2].textContent.trim()) - parseInt(b.cells[2].textContent.trim());
            } else if (sort === 'stock_desc') {
                return parseInt(b.cells[2].textContent.trim()) - parseInt(a.cells[2].textContent.trim());
            } else if (sort === 'type_asc') {
                return a.cells[1].textContent.localeCompare(b.cells[1].textContent);
            } else if (sort === 'type_desc') {
                return b.cells[1].textContent.localeCompare(a.cells[1].textContent);
            }
        });
        rowsArray.forEach(row => tbody.appendChild(row));
    }

    searchInput.addEventListener('input', applyFilters);
    bloodTypeFilter.addEventListener('change', applyFilters);
    statusFilter.addEventListener('change', applyFilters);
    sortFilter.addEventListener('change', applyFilters);

    // Advanced Filter Form
    document.getElementById('applyFilters').addEventListener('click', function() {
        const form = document.getElementById('advancedFilterForm');
        const formData = new FormData(form);
        // Add your advanced filter logic here
        $('#filterModal').modal('hide');
    });
});
</script>
@endpush
@endsection 