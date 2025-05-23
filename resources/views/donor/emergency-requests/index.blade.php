@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-heartbeat text-danger me-2"></i>Emergency Blood Requests</h2>
        <div class="blood-type-filter">
            <select class="form-select" id="bloodTypeFilter">
                <option value="">All Blood Types</option>
                <option value="A+">A+</option>
                <option value="A-">A-</option>
                <option value="B+">B+</option>
                <option value="B-">B-</option>
                <option value="AB+">AB+</option>
                <option value="AB-">AB-</option>
                <option value="O+">O+</option>
                <option value="O-">O-</option>
            </select>
        </div>
    </div>

    <!-- Blood Stock Overview -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Current Blood Stock Status</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bloodType)
                    <div class="col-md-3 col-sm-6">
                        <div class="blood-stock-card">
                            <div class="blood-type">{{ $bloodType }}</div>
                            @php
                                $stock = $bloodStock[$bloodType] ?? 0;
                                $status = $stock < 10 ? 'critical' : ($stock < 20 ? 'warning' : 'good');
                            @endphp
                            <div class="stock-level {{ $status }}">
                                <span class="units">{{ $stock }}</span>
                                <span class="label">units</span>
                            </div>
                            <div class="status-text {{ $status }}">
                                @if($status === 'critical')
                                    Critical Level
                                @elseif($status === 'warning')
                                    Low Stock
                                @else
                                    Sufficient
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Emergency Requests List -->
    <div class="row">
        <!-- Active Requests -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Active Emergency Requests</h5>
                    <span class="badge bg-white text-danger">{{ $emergencyRequests->where('status', 'active')->count() }} Active</span>
                </div>
                <div class="card-body">
                    @forelse($emergencyRequests as $request)
                        <div class="emergency-request-card mb-3 {{ !$request->pivot->read_at ? 'unread' : '' }}">
                            <div class="row g-0">
                                <div class="col-auto d-flex align-items-center px-3">
                                    <div class="blood-type-badge {{ strtolower(str_replace('+', 'pos', str_replace('-', 'neg', $request->blood_type))) }}">
                                        {{ $request->blood_type }}
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h5 class="card-title mb-1">
                                                    {{ $request->hospital_name }}
                                                    @if(!$request->pivot->read_at)
                                                        <span class="badge bg-warning text-dark ms-2">New</span>
                                                    @endif
                                                </h5>
                                                <p class="card-text">
                                                    <small class="text-muted">
                                                        <i class="fas fa-clock me-1"></i>{{ $request->created_at->diffForHumans() }} •
                                                        <i class="fas fa-map-marker-alt me-1"></i>{{ number_format($request->distance, 1) }} km away
                                                    </small>
                                                </p>
                                            </div>
                                            <div class="text-end">
                                                <span class="badge bg-danger">Urgent</span>
                                                <div class="mt-2">
                                                    <span class="text-muted">Needed:</span>
                                                    <span class="fw-bold">{{ $request->units_needed }} units</span>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="card-text mt-2">{{ $request->description }}</p>
                                        <div class="d-flex justify-content-between align-items-center mt-3">
                                            <div class="progress flex-grow-1 me-3" style="height: 8px;">
                                                @php
                                                    $progress = ($request->units_collected / $request->units_needed) * 100;
                                                @endphp
                                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $progress }}%"></div>
                                            </div>
                                            <span class="text-muted">{{ $request->units_collected }}/{{ $request->units_needed }} units collected</span>
                                        </div>
                                        <div class="mt-3">
                                            <a href="{{ route('donor.emergency-requests.show', $request) }}" class="btn btn-outline-danger">
                                                View Details & Respond
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="fas fa-heart text-muted fa-3x mb-3"></i>
                            <p class="mb-0">No emergency requests at the moment.</p>
                        </div>
                    @endforelse

                    {{ $emergencyRequests->links() }}
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- Your Responses -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Your Response History</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <div>Total Responses</div>
                        <div class="fw-bold">{{ $responseStats['total'] }}</div>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <div>Accepted</div>
                        <div class="text-success fw-bold">{{ $responseStats['accepted'] }}</div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div>Response Rate</div>
                        <div class="fw-bold">{{ $responseStats['rate'] }}%</div>
                    </div>
                </div>
            </div>

            <!-- Information Card -->
            <div class="card bg-light">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-info-circle text-primary me-2"></i>About Emergency Requests
                    </h5>
                    <p class="card-text">Emergency requests are urgent calls for blood donation. Your quick response can help save lives.</p>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>Immediate response needed
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>Priority processing at donation centers
                        </li>
                        <li>
                            <i class="fas fa-check-circle text-success me-2"></i>Special recognition for emergency donors
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .blood-stock-card {
        padding: 1rem;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        text-align: center;
    }
    .blood-type {
        font-size: 1.5rem;
        font-weight: bold;
        margin-bottom: 0.5rem;
    }
    .stock-level {
        margin-bottom: 0.5rem;
    }
    .stock-level .units {
        font-size: 1.8rem;
        font-weight: bold;
    }
    .stock-level .label {
        font-size: 0.9rem;
        color: #6c757d;
    }
    .stock-level.critical { color: #dc3545; }
    .stock-level.warning { color: #ffc107; }
    .stock-level.good { color: #198754; }
    .status-text {
        font-size: 0.8rem;
        font-weight: 500;
    }
    .status-text.critical { color: #dc3545; }
    .status-text.warning { color: #ffc107; }
    .status-text.good { color: #198754; }
    .blood-type-badge {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: white;
        font-size: 1.2rem;
    }
    .blood-type-badge.apos { background-color: #dc3545; }
    .blood-type-badge.aneg { background-color: #e35d6a; }
    .blood-type-badge.bpos { background-color: #0d6efd; }
    .blood-type-badge.bneg { background-color: #3d8bfd; }
    .blood-type-badge.abpos { background-color: #6610f2; }
    .blood-type-badge.abneg { background-color: #8540f5; }
    .blood-type-badge.opos { background-color: #198754; }
    .blood-type-badge.oneg { background-color: #28a745; }
    .emergency-request-card {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    .emergency-request-card:hover {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }
    .emergency-request-card.unread {
        background-color: #fff8f8;
        border-left: 4px solid #dc3545;
    }
</style>
@endpush

@push('scripts')
<script>
document.getElementById('bloodTypeFilter').addEventListener('change', function() {
    const bloodType = this.value;
    const url = new URL(window.location.href);
    if (bloodType) {
        url.searchParams.set('blood_type', bloodType);
    } else {
        url.searchParams.delete('blood_type');
    }
    window.location.href = url.toString();
});

// Set selected blood type from URL
const urlParams = new URLSearchParams(window.location.search);
const bloodType = urlParams.get('blood_type');
if (bloodType) {
    document.getElementById('bloodTypeFilter').value = bloodType;
}
</script>
@endpush
@endsection 