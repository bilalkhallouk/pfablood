@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="text-primary">Bienvenue, {{ Auth::user()->name }}!</h2>
            <p class="text-muted">Gérez vos demandes de sang et trouvez des donneurs facilement.</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card stat-card bg-light-primary border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                            <i class="fas fa-tint text-primary"></i>
                        </div>
                        <div>
                            <h6 class="card-title mb-0">Groupe sanguin</h6>
                            <h3 class="mb-0">{{ Auth::user()->blood_type ?? 'N/A' }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card bg-light-success border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                            <i class="fas fa-check-circle text-success"></i>
                        </div>
                        <div>
                            <h6 class="card-title mb-0">Demandes en attente</h6>
                            <h3 class="mb-0">{{ $activeRequests ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card bg-light-info border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-info bg-opacity-10 p-3 me-3">
                            <i class="fas fa-users text-info"></i>
                        </div>
                        <div>
                            <h6 class="card-title mb-0">Donneurs disponibles</h6>
                            <h3 class="mb-0">{{ $availableDonors ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card bg-light-warning border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3">
                            <i class="fas fa-bell text-warning"></i>
                        </div>
                        <div>
                            <h6 class="card-title mb-0">Notifications</h6>
                            <h3 class="mb-0">{{ $notifications ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Actions -->
    <div class="row mb-4">
        <!-- Request Blood Form -->
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0"><i class="fas fa-tint me-2"></i>Demander du sang</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('blood-requests.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Groupe sanguin requis</label>
                            <input type="text" class="form-control" value="{{ Auth::user()->blood_type }}" readonly style="background-color: #e0f7fa; color: #00796b; font-weight: bold;">
                            <input type="hidden" name="blood_type" value="{{ Auth::user()->blood_type }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ville</label>
                            <select name="city" id="city" class="form-select" required>
                                <option value="">Sélectionnez la ville</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city }}">{{ $city }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Centre de don</label>
                            <select name="center_id" id="center" class="form-select" required style="display:none;">
                                <option value="">Sélectionnez le centre</option>
                                @foreach($centers as $center)
                                    <option value="{{ $center->id }}" data-city="{{ $center->city }}">{{ $center->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Unités nécessaires</label>
                            <input type="number" class="form-control" name="units_needed" min="1" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Niveau d'urgence</label>
                            <select class="form-select" name="urgency" required>
                                    <option value="low">Faible – Délai de 2 semaines</option>
                                    <option value="medium">Moyen – Délai de 1 semaine</option>
                                    <option value="high">Élevé – Urgence</option>

                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Télécharger l'ordonnance</label>
                            <input type="file" class="form-control" name="prescription" accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Soumettre la demande</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Recent Requests -->
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-info text-white py-3">
                    <h5 class="mb-0"><i class="fas fa-history me-2"></i>Demandes récentes</h5>
                </div>
                <div class="card-body">
                    @if(isset($recentRequests) && count($recentRequests) > 0)
                        @foreach($recentRequests as $request)
                            <div class="d-flex align-items-center border-bottom py-3">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $request->blood_type }} - {{ $request->units_needed }} Units</h6>
                                    <p class="text-muted small mb-0">
                                        Status: 
                                        <span class="badge bg-{{ $request->status == 'approved' ? 'success' : ($request->status == 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($request->status) }}
                                        </span>
                                    </p>
                                </div>
                                <div class="text-end">
                                    <small class="text-muted">{{ $request->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-center text-muted my-4">No recent requests found</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-md-4 mb-3">
            <a href="{{ route('donors.search') }}" class="card border-0 shadow-sm text-decoration-none">
                <div class="card-body text-center py-4">
                    <i class="fas fa-search fa-2x text-primary mb-3"></i>
                    <h5 class="card-title">Find Donors</h5>
                    <p class="card-text text-muted">Search donors by location and blood type</p>
                </div>
            </a>
        </div>
        <div class="col-md-4 mb-3">
            <a href="#" class="card border-0 shadow-sm text-decoration-none" data-bs-toggle="modal" data-bs-target="#emergencyModal">
                <div class="card-body text-center py-4">
                    <i class="fas fa-exclamation-circle fa-2x text-danger mb-3"></i>
                    <h5 class="card-title">Emergency Alert</h5>
                    <p class="card-text text-muted">Send urgent blood request alerts</p>
                </div>
            </a>
        </div>
        <div class="col-md-4 mb-3">
            <a href="{{ route('centers.nearby') }}" class="card border-0 shadow-sm text-decoration-none">
                <div class="card-body text-center py-4">
                    <i class="fas fa-hospital fa-2x text-success mb-3"></i>
                    <h5 class="card-title">Nearby Centers</h5>
                    <p class="card-text text-muted">Find blood donation centers</p>
                </div>
            </a>
        </div>
    </div>
</div>

<!-- Emergency Alert Modal -->
<div class="modal fade" id="emergencyModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Emergency Blood Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('emergency.alert') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Blood Type Needed</label>
                        <select class="form-select" name="blood_type" required>
                            @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $type)
                                <option value="{{ $type }}">{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Units Required</label>
                        <input type="number" class="form-control" name="units" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Additional Message</label>
                        <textarea class="form-control" name="message" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Send Emergency Alert</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.card {
    transition: transform 0.2s ease-in-out;
}
.card:hover {
    transform: translateY(-5px);
}
.rounded-circle {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.fa-2x {
    font-size: 2em;
}
.bg-light-primary {
    background-color: rgba(13, 110, 253, 0.1);
}
.bg-light-success {
    background-color: rgba(25, 135, 84, 0.1);
}
.bg-light-info {
    background-color: rgba(13, 202, 240, 0.1);
}
.bg-light-warning {
    background-color: rgba(255, 193, 7, 0.1);
}
.stat-card {
    border-radius: 15px;
}
</style>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var citySelect = document.getElementById('city');
    var centerSelect = document.getElementById('center');
    var centerWrapper = centerSelect.closest('.mb-3');
    if(citySelect && centerSelect) {
        function filterCenters() {
            var city = citySelect.value.trim().toLowerCase();
            var hasCity = !!city;
            var hasVisible = false;
            Array.from(centerSelect.options).forEach(function(option) {
                if (!option.value) {
                    option.style.display = '';
                    return;
                }
                var optionCity = (option.getAttribute('data-city') || '').trim().toLowerCase();
                // Debug log
                console.log('Comparing selected city:', city, 'with center city:', optionCity, '| Center:', option.textContent);
                var show = hasCity && optionCity === city;
                option.style.display = show ? '' : 'none';
                if (show) hasVisible = true;
            });
            centerSelect.style.display = hasCity ? '' : 'none';
            let msg = centerWrapper.querySelector('.no-centers-msg');
            if (msg) msg.remove();
            if (hasCity && !hasVisible) {
                let m = document.createElement('div');
                m.className = 'no-centers-msg text-danger mt-2';
                m.textContent = 'Aucun centre disponible pour cette ville.';
                centerWrapper.appendChild(m);
            }
            centerSelect.value = '';
        }
        citySelect.addEventListener('change', filterCenters);
        // Initial filter on page load
        filterCenters();
    }
});
</script>
@endsection

@endsection 