@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="mb-3">Welcome, {{ Auth::user()->name }}!</h2>
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-4">
                            <h4 class="mb-1">Donation Status</h4>
                            @if(Auth::user()->isEligibleToDonate())
                                <p class="mb-0"><i class="fas fa-check-circle me-2"></i>You are eligible to donate blood</p>
                            @else
                                <p class="mb-0"><i class="fas fa-clock me-2"></i>Next donation possible in {{ Auth::user()->last_donation_at->addMonths(3)->diffForHumans() }}</p>
                            @endif
                        </div>
                        <div class="ms-auto text-end">
                            <h5 class="mb-1">Blood Type</h5>
                            <span class="display-6">{{ Auth::user()->blood_type ?? 'Not Set' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <h5 class="card-title">Quick Actions</h5>
                    <div class="d-grid gap-2">
                        <a href="{{ route('donor.appointments.create') }}" class="btn btn-primary">
                            <i class="fas fa-calendar-plus me-2"></i>Schedule Donation
                        </a>
                        <a href="{{ route('centers.nearby') }}" class="btn btn-outline-primary">
                            <i class="fas fa-hospital me-2"></i>Find Centers
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Emergency Requests -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-heartbeat me-2"></i>Emergency Requests
                        @if(Auth::user()->unreadEmergencyRequests()->count() > 0)
                            <span class="badge bg-warning text-dark ms-2">{{ Auth::user()->unreadEmergencyRequests()->count() }} New</span>
                        @endif
                    </h5>
                    <a href="{{ route('donor.emergency-requests') }}" class="btn btn-sm btn-outline-light">View All</a>
                </div>
                <div class="card-body">
                    @forelse(Auth::user()->emergencyRequests()->latest()->take(3)->get() as $request)
                        <div class="d-flex align-items-center mb-3 {{ is_null($request->pivot->read_at) ? 'bg-light rounded p-2' : '' }}">
                            <div class="blood-type-badge me-3">{{ $request->blood_type }}</div>
                            <div>
                                <h6 class="mb-1">
                                    {{ $request->hospital_name }}
                                    @if(is_null($request->pivot->read_at))
                                        <span class="badge bg-warning text-dark ms-2">New</span>
                                    @endif
                                </h6>
                                <small class="text-muted">{{ $request->created_at->diffForHumans() }} • {{ $request->distance }} km away</small>
                            </div>
                            <a href="{{ route('donor.emergency-requests.show', $request) }}" class="btn btn-sm btn-outline-danger ms-auto">Respond</a>
                        </div>
                    @empty
                        <p class="text-muted mb-0">No emergency requests at the moment.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Upcoming Appointments -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Upcoming Appointments</h5>
                    <a href="{{ route('donor.appointments') }}" class="btn btn-sm btn-outline-light">View All</a>
                </div>
                <div class="card-body">
                    @forelse(Auth::user()->appointments()->upcoming()->take(3)->get() as $appointment)
                        <div class="d-flex align-items-center mb-3">
                            <div class="me-3">
                                <div class="calendar-date text-center">
                                    <div class="month">{{ $appointment->scheduled_at->format('M') }}</div>
                                    <div class="day">{{ $appointment->scheduled_at->format('d') }}</div>
                                </div>
                            </div>
                            <div>
                                <h6 class="mb-1">{{ $appointment->center->name }}</h6>
                                <small class="text-muted">{{ $appointment->scheduled_at->format('g:i A') }}</small>
                            </div>
                            <div class="ms-auto">
                                <span class="badge bg-primary">{{ $appointment->status }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted mb-0">No upcoming appointments.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Achievements -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-award me-2"></i>Achievements</h5>
                    <a href="{{ route('donor.rewards') }}" class="btn btn-sm btn-outline-light">View All</a>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach(Auth::user()->badges()->latest()->take(4)->get() as $badge)
                            <div class="col-6">
                                <div class="achievement-card text-center">
                                    <i class="{{ $badge->icon }} fa-2x mb-2"></i>
                                    <h6 class="mb-1">{{ $badge->name }}</h6>
                                    <small class="text-muted">{{ $badge->description }}</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Health Tips -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-heart me-2"></i>Health Tips</h5>
                    <button class="btn btn-sm btn-outline-light" id="refreshTips">Refresh</button>
                </div>
                <div class="card-body">
                    <div class="health-tip mb-3">
                        <h6><i class="fas fa-utensils me-2"></i>Pre-donation Diet</h6>
                        <p class="mb-0">Eat a healthy meal and drink plenty of water before donating blood.</p>
                    </div>
                    <div class="health-tip mb-3">
                        <h6><i class="fas fa-bed me-2"></i>Rest Well</h6>
                        <p class="mb-0">Get at least 8 hours of sleep before your donation appointment.</p>
                    </div>
                    <div class="health-tip">
                        <h6><i class="fas fa-walking me-2"></i>Post-donation Care</h6>
                        <p class="mb-0">Avoid strenuous physical activity for 24 hours after donation.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .blood-type-badge {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #dc3545;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }
    .calendar-date {
        width: 50px;
        border: 1px solid #dee2e6;
        border-radius: 4px;
    }
    .calendar-date .month {
        background-color: #dc3545;
        color: white;
        text-align: center;
        padding: 2px;
        font-size: 12px;
        text-transform: uppercase;
    }
    .calendar-date .day {
        font-size: 18px;
        font-weight: bold;
        padding: 4px;
    }
    .achievement-card {
        padding: 1rem;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    .achievement-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
</style>
@endpush

@push('scripts')
<script>
document.getElementById('refreshTips')?.addEventListener('click', function() {
    // Add functionality to refresh health tips
    // This could be an AJAX call to get new tips from the server
});
</script>
@endpush
@endsection 