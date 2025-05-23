@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Profile Sidebar -->
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-user-circle fa-5x text-primary"></i>
                    </div>
                    <h5 class="card-title mb-0">{{ $user->name }}</h5>
                    <p class="text-muted">{{ ucfirst($user->role) }}</p>
                    <div class="mt-3">
                        <p class="mb-1">
                            <i class="fas fa-tint me-2"></i>Blood Type: 
                            <span class="fw-bold">{{ $user->blood_type ?? 'Not Set' }}</span>
                        </p>
                        @if($user->isDonor())
                            <p class="mb-1">
                                <i class="fas fa-calendar me-2"></i>Last Donation: 
                                <span class="fw-bold">{{ $user->last_donation_at ? $user->last_donation_at->format('M d, Y') : 'Never' }}</span>
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Form -->
        <div class="col-md-9">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="card-title mb-4">Edit Profile</h4>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                id="name" name="name" value="{{ old('name', $user->name) }}">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                id="email" name="email" value="{{ old('email', $user->email) }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="blood_type" class="form-label">Blood Type</label>
                            <select class="form-select @error('blood_type') is-invalid @enderror" 
                                id="blood_type" name="blood_type">
                                <option value="">Select Blood Type</option>
                                @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $type)
                                    <option value="{{ $type }}" 
                                        {{ old('blood_type', $user->blood_type) === $type ? 'selected' : '' }}>
                                        {{ $type }}
                                    </option>
                                @endforeach
                            </select>
                            @error('blood_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="location" class="form-label">Location</label>
                            <div class="input-group">
                                <input type="text" class="form-control @error('location') is-invalid @enderror" 
                                    id="location" name="location" value="{{ old('location', $user->location) }}">
                                <button type="button" class="btn btn-outline-secondary" onclick="getCurrentLocation()">
                                    <i class="fas fa-map-marker-alt"></i>
                                </button>
                            </div>
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude', $user->latitude) }}">
                        <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude', $user->longitude) }}">

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function getCurrentLocation() {
    if ("geolocation" in navigator) {
        navigator.geolocation.getCurrentPosition(function(position) {
            document.getElementById('latitude').value = position.coords.latitude;
            document.getElementById('longitude').value = position.coords.longitude;
            
            // Use reverse geocoding to get address
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${position.coords.latitude}&lon=${position.coords.longitude}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('location').value = data.display_name;
                })
                .catch(error => {
                    console.error('Error getting location:', error);
                    alert('Could not get address from coordinates');
                });
        }, function(error) {
            console.error('Error getting location:', error);
            alert('Could not get your current location');
        });
    } else {
        alert('Geolocation is not supported by your browser');
    }
}
</script>
@endpush
@endsection 