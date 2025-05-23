@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h2 mb-0">Find Blood Donors</h1>
            <p class="text-muted">Search for compatible blood donors in your area</p>
        </div>
        <div class="col-md-4 text-md-end">
            <button class="btn btn-outline-danger" onclick="getCurrentLocation()">
                <i class="fas fa-location-dot me-2"></i>Use My Location
            </button>
        </div>
    </div>

    <!-- Search Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('donors.search') }}" method="GET" id="searchForm">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Blood Type</label>
                        <select class="form-select" name="blood_type" onchange="this.form.submit()">
                            <option value="">All Blood Types</option>
                            @foreach($bloodTypes as $type)
                                <option value="{{ $type }}" {{ request('blood_type') == $type ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Search Radius</label>
                        <select class="form-select" name="radius" onchange="this.form.submit()">
                            <option value="5" {{ request('radius') == 5 ? 'selected' : '' }}>Within 5 km</option>
                            <option value="10" {{ request('radius') == 10 ? 'selected' : '' }}>Within 10 km</option>
                            <option value="20" {{ request('radius', 20) == 20 ? 'selected' : '' }}>Within 20 km</option>
                            <option value="50" {{ request('radius') == 50 ? 'selected' : '' }}>Within 50 km</option>
                            <option value="100" {{ request('radius') == 100 ? 'selected' : '' }}>Within 100 km</option>
                        </select>
                    </div>
                </div>
                <input type="hidden" name="latitude" id="latitude">
                <input type="hidden" name="longitude" id="longitude">
            </form>
        </div>
    </div>

    <!-- Results -->
    <div class="row g-4">
        @forelse($donors as $donor)
            <div class="col-md-6">
                <div class="card h-100 border-0 shadow-sm hover-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-danger bg-opacity-10 p-3 rounded-circle me-3">
                                    <i class="fas fa-user text-danger"></i>
                                </div>
                                <div>
                                    <h5 class="card-title mb-1">{{ $donor->name }}</h5>
                                    <span class="badge bg-danger">{{ $donor->blood_type }}</span>
                                </div>
                            </div>
                            @if(isset($donor->distance))
                                <span class="badge bg-success">{{ number_format($donor->distance, 1) }} km away</span>
                            @endif
                        </div>

                        <div class="mb-3">
                            <p class="card-text text-muted mb-1">
                                <i class="fas fa-map-marker-alt me-2"></i>{{ $donor->location ?? 'Location not specified' }}
                            </p>
                            <p class="card-text text-muted mb-1">
                                <i class="fas fa-phone me-2"></i>{{ $donor->phone ?? 'Phone not available' }}
                            </p>
                            @if($donor->last_donation_at)
                                <p class="card-text text-muted mb-0">
                                    <i class="fas fa-clock me-2"></i>Last donation: {{ $donor->last_donation_at->diffForHumans() }}
                                </p>
                            @endif
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <button class="btn btn-outline-danger btn-sm" onclick="contactDonor('{{ $donor->id }}')">
                                <i class="fas fa-envelope me-1"></i>Contact Donor
                            </button>
                            @if(isset($donor->distance))
                                <button class="btn btn-link btn-sm text-muted" onclick="showDirections({{ $donor->latitude }}, {{ $donor->longitude }})">
                                    <i class="fas fa-directions me-1"></i>Get Directions
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>No donors found matching your criteria.
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $donors->links() }}
    </div>
</div>

@push('scripts')
<script>
function getCurrentLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            position => {
                document.getElementById('latitude').value = position.coords.latitude;
                document.getElementById('longitude').value = position.coords.longitude;
                document.getElementById('searchForm').submit();
            },
            error => {
                alert('Error getting your location. Please try again.');
            }
        );
    } else {
        alert('Geolocation is not supported by your browser.');
    }
}

function contactDonor(donorId) {
    // Implement donor contact functionality
    alert('Contact functionality will be implemented soon!');
}

function showDirections(lat, lng) {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            position => {
                const userLat = position.coords.latitude;
                const userLng = position.coords.longitude;
                window.open(`https://www.google.com/maps/dir/${userLat},${userLng}/${lat},${lng}`);
            },
            error => {
                window.open(`https://www.google.com/maps/dir//${lat},${lng}`);
            }
        );
    } else {
        window.open(`https://www.google.com/maps/dir//${lat},${lng}`);
    }
}
</script>
@endpush

@push('styles')
<style>
.hover-card {
    transition: transform 0.2s ease-in-out;
}
.hover-card:hover {
    transform: translateY(-5px);
}
</style>
@endpush
@endsection 