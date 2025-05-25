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
    .center-table th, .center-table td {
        padding: 1rem;
        vertical-align: middle;
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
    .center-card {
        border: none;
        border-radius: 1rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }
    .center-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .center-card .card-body {
        padding: 1.5rem;
    }
    .center-status {
        position: absolute;
        top: 1rem;
        right: 1rem;
        width: 12px;
        height: 12px;
        border-radius: 50%;
    }
    .center-status.active {
        background-color: #1cc88a;
        box-shadow: 0 0 0 4px rgba(28, 200, 138, 0.2);
    }
    .center-status.inactive {
        background-color: #e74a3b;
        box-shadow: 0 0 0 4px rgba(231, 74, 59, 0.2);
    }
    .view-toggle {
        border-radius: 1rem;
        padding: 0.5rem;
        background: #f8f9fc;
    }
    .view-toggle .btn {
        border-radius: 0.5rem;
        padding: 0.5rem 1rem;
    }
    .view-toggle .btn.active {
        background: #4e73df;
        color: white;
    }
</style>

<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 mb-0 text-gray-800">Gestion des Centres</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.centers.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Nouveau Centre
            </a>
            <div class="view-toggle">
                <button class="btn active" data-view="grid">
                    <i class="fas fa-th-large"></i>
                </button>
                <button class="btn" data-view="list">
                    <i class="fas fa-list"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card dashboard-card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Total Centres</h6>
                            <h3 class="mb-0 text-white">{{ isset($centers) ? $centers->count() : 0 }}</h3>
                        </div>
                        <i class="fas fa-hospital fa-2x text-white-50"></i>
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
                            <h3 class="mb-0 text-white">{{ isset($centers) ? $centers->where('is_active', true)->count() : 0 }}</h3>
                        </div>
                        <i class="fas fa-check-circle fa-2x text-white-50"></i>
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
                            <h3 class="mb-0 text-white">
                                {{ isset($centers) ? $centers->filter(function($center) {
                                    return isset($center->bloodStocks) && $center->bloodStocks->where('units', '<', 'min_threshold')->count() > 0;
                                })->count() : 0 }}
                            </h3>
                        </div>
                        <i class="fas fa-exclamation-triangle fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card dashboard-card stat-card danger">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Centres Inactifs</h6>
                            <h3 class="mb-0 text-white">{{ isset($centers) ? $centers->where('is_active', false)->count() : 0 }}</h3>
                        </div>
                        <i class="fas fa-times-circle fa-2x text-white-50"></i>
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
                        <input type="text" class="form-control search-box" id="searchInput" placeholder="Rechercher un centre...">
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-select filter-dropdown" id="statusFilter">
                        <option value="">Tous les statuts</option>
                        <option value="active">Actifs</option>
                        <option value="inactive">Inactifs</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select filter-dropdown" id="cityFilter">
                        <option value="">Toutes les villes</option>
                        @foreach($centers->pluck('city')->unique() as $city)
                            <option value="{{ $city }}">{{ $city }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select filter-dropdown" id="sortFilter">
                        <option value="name_asc">Nom A-Z</option>
                        <option value="name_desc">Nom Z-A</option>
                        <option value="city_asc">Ville A-Z</option>
                        <option value="city_desc">Ville Z-A</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Grid View -->
    <div class="row g-4" id="gridView">
        @forelse($centers as $center)
            <div class="col-xl-3 col-md-6">
                <div class="card center-card">
                    <div class="card-body">
                        <div class="center-status {{ $center->is_active ? 'active' : 'inactive' }}"></div>
                        <h5 class="card-title mb-3">{{ $center->name }}</h5>
                        <div class="mb-3">
                            <small class="text-muted d-block mb-1">
                                <i class="fas fa-map-marker-alt me-2"></i>Adresse
                            </small>
                            <p class="mb-0">{{ $center->address }}, {{ $center->city }}</p>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted d-block mb-1">
                                <i class="fas fa-phone me-2"></i>Contact
                            </small>
                            <p class="mb-0">{{ $center->phone ?? 'Non spécifié' }}</p>
                            <p class="mb-0">{{ $center->email ?? 'Non spécifié' }}</p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.centers.edit', $center->id) }}" 
                               class="btn btn-sm btn-outline-primary flex-grow-1">
                                <i class="fas fa-edit me-1"></i>Éditer
                            </a>
                            <a href="{{ route('admin.blood-stocks.index', ['center_id' => $center->id]) }}" 
                               class="btn btn-sm btn-outline-success flex-grow-1">
                                <i class="fas fa-tint me-1"></i>Stock
                            </a>
                            <button type="button" 
                                    class="btn btn-sm btn-outline-danger"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteModal{{ $center->id }}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delete Modal -->
            <div class="modal fade" id="deleteModal{{ $center->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Confirmer la suppression</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            Êtes-vous sûr de vouloir supprimer le centre "{{ $center->name }}" ?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <form action="{{ route('admin.centers.destroy', $center->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Supprimer</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center py-5">
                    <i class="fas fa-hospital fa-2x mb-2"></i><br>
                    Aucun centre trouvé.
                </div>
            </div>
        @endforelse
    </div>

    <!-- List View (Hidden by default) -->
    <div class="card dashboard-card d-none" id="listView">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table center-table mb-0">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Adresse</th>
                            <th>Ville</th>
                            <th>Contact</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($centers as $center)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="center-status {{ $center->is_active ? 'active' : 'inactive' }} me-2"></div>
                                        {{ $center->name }}
                                    </div>
                                </td>
                                <td>{{ $center->address }}</td>
                                <td>{{ $center->city }}</td>
                                <td>
                                    <div>{{ $center->phone ?? 'Non spécifié' }}</div>
                                    <small class="text-muted">{{ $center->email ?? 'Non spécifié' }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $center->is_active ? 'success' : 'danger' }}">
                                        {{ $center->is_active ? 'Actif' : 'Inactif' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.centers.edit', $center->id) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('admin.blood-stocks.index', ['center_id' => $center->id]) }}" 
                                           class="btn btn-sm btn-outline-success">
                                            <i class="fas fa-tint"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-danger"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteModal{{ $center->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="fas fa-hospital fa-2x mb-2"></i><br>
                                    Aucun centre trouvé.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // View Toggle
    const viewButtons = document.querySelectorAll('.view-toggle .btn');
    const gridView = document.getElementById('gridView');
    const listView = document.getElementById('listView');

    viewButtons.forEach(button => {
        button.addEventListener('click', function() {
            viewButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');

            if (this.dataset.view === 'grid') {
                gridView.classList.remove('d-none');
                listView.classList.add('d-none');
            } else {
                gridView.classList.add('d-none');
                listView.classList.remove('d-none');
            }
        });
    });

    // Search and Filter Functionality
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const cityFilter = document.getElementById('cityFilter');
    const sortFilter = document.getElementById('sortFilter');

    function applyFilters() {
        const searchTerm = searchInput.value.toLowerCase();
        const status = statusFilter.value;
        const city = cityFilter.value;
        const sort = sortFilter.value;

        // Grid View Filtering
        const gridCards = document.querySelectorAll('#gridView .col-xl-3');
        gridCards.forEach(card => {
            const name = card.querySelector('.card-title').textContent.toLowerCase();
            const centerStatus = card.querySelector('.center-status').classList.contains('active') ? 'active' : 'inactive';
            const centerCity = card.querySelector('p').textContent.split(',')[1].trim();

            const matchesSearch = name.includes(searchTerm);
            const matchesStatus = !status || centerStatus === status;
            const matchesCity = !city || centerCity === city;

            card.style.display = matchesSearch && matchesStatus && matchesCity ? '' : 'none';
        });

        // List View Filtering
        const listRows = document.querySelectorAll('#listView tbody tr');
        listRows.forEach(row => {
            const name = row.cells[0].textContent.toLowerCase();
            const centerStatus = row.cells[4].textContent.trim().toLowerCase();
            const centerCity = row.cells[2].textContent.trim();

            const matchesSearch = name.includes(searchTerm);
            const matchesStatus = !status || centerStatus === status;
            const matchesCity = !city || centerCity === city;

            row.style.display = matchesSearch && matchesStatus && matchesCity ? '' : 'none';
        });

        // Apply sorting
        const sortFunction = (a, b) => {
            if (sort === 'name_asc') {
                return a.querySelector('.card-title').textContent.localeCompare(b.querySelector('.card-title').textContent);
            } else if (sort === 'name_desc') {
                return b.querySelector('.card-title').textContent.localeCompare(a.querySelector('.card-title').textContent);
            } else if (sort === 'city_asc') {
                return a.querySelector('p').textContent.split(',')[1].trim().localeCompare(b.querySelector('p').textContent.split(',')[1].trim());
            } else if (sort === 'city_desc') {
                return b.querySelector('p').textContent.split(',')[1].trim().localeCompare(a.querySelector('p').textContent.split(',')[1].trim());
            }
        };

        const gridContainer = document.querySelector('#gridView .row');
        const gridCardsArray = Array.from(gridCards);
        gridCardsArray.sort(sortFunction);
        gridCardsArray.forEach(card => gridContainer.appendChild(card));
    }

    searchInput.addEventListener('input', applyFilters);
    statusFilter.addEventListener('change', applyFilters);
    cityFilter.addEventListener('change', applyFilters);
    sortFilter.addEventListener('change', applyFilters);
});
</script>
@endpush
@endsection 