@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Schedule New Appointment</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('donor.appointments.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Find Centers</label>
                            <div class="d-flex gap-2 mb-2">
                                <button type="button" class="btn btn-outline-primary" id="useMyLocation">
                                    <i class="fas fa-location-arrow me-2"></i>Use My Location
                                </button>
                                <div class="position-relative flex-grow-1">
                                    <input type="text" 
                                           class="form-control" 
                                           id="citySearch" 
                                           placeholder="Enter city name"
                                           value="{{ old('city') }}">
                                    <button type="button" 
                                            class="btn btn-link position-absolute end-0 top-50 translate-middle-y"
                                            id="searchByCity">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="center_id" class="form-label">Donation Center</label>
                            <select class="form-select @error('center_id') is-invalid @enderror" 
                                id="center_id" name="center_id" required>
                                <option value="">Select a donation center</option>
                                @foreach($centers as $center)
                                    <option value="{{ $center->id }}" 
                                        {{ old('center_id') == $center->id ? 'selected' : '' }}
                                        data-location="{{ $center->location }}"
                                        data-latitude="{{ $center->latitude }}"
                                        data-longitude="{{ $center->longitude }}">
                                        {{ $center->name }} - {{ $center->city }}
                                        @if(isset($center->distance))
                                            ({{ number_format($center->distance, 1) }} km away)
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('center_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Center Location</label>
                            <div id="center_location" class="form-text mb-2"></div>
                            <div id="map" class="rounded" style="height: 300px;"></div>
                        </div>

                        <div class="mb-3">
                            <label for="scheduled_at" class="form-label">Appointment Date & Time</label>
                            <input type="datetime-local" class="form-control @error('scheduled_at') is-invalid @enderror" 
                                id="scheduled_at" name="scheduled_at" 
                                value="{{ old('scheduled_at') }}"
                                min="{{ now()->format('Y-m-d\TH:i') }}"
                                required>
                            @error('scheduled_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes (Optional)</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('donor.appointments') }}" class="btn btn-link">
                                <i class="fas fa-arrow-left me-2"></i>Back to Appointments
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-calendar-check me-2"></i>Schedule Appointment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&libraries=places"></script>
<script>
let map;
let marker;
let currentLocationMarker;

function initMap() {
    map = new google.maps.Map(document.getElementById('map'), {
        zoom: 12,
        center: { lat: 0, lng: 0 }
    });
    marker = new google.maps.Marker({
        map: map,
        icon: {
            url: 'https://maps.google.com/mapfiles/ms/icons/red-dot.png'
        }
    });
}

function updateMap(lat, lng, centerName) {
    const position = { lat, lng };
    map.setCenter(position);
    marker.setPosition(position);
    
    if (currentLocationMarker) {
        const bounds = new google.maps.LatLngBounds();
        bounds.extend(marker.getPosition());
        bounds.extend(currentLocationMarker.getPosition());
        map.fitBounds(bounds);
    }
}

document.getElementById('center_id').addEventListener('change', function() {
    const selected = this.options[this.selectedIndex];
    const location = selected.dataset.location;
    const lat = parseFloat(selected.dataset.latitude);
    const lng = parseFloat(selected.dataset.longitude);

    document.getElementById('center_location').textContent = location;

    if (lat && lng) {
        updateMap(lat, lng, selected.text);
    }
});

document.getElementById('useMyLocation').addEventListener('click', function() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            const pos = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };

            // Add marker for current location
            if (currentLocationMarker) {
                currentLocationMarker.setMap(null);
            }
            currentLocationMarker = new google.maps.Marker({
                position: pos,
                map: map,
                icon: {
                    url: 'https://maps.google.com/mapfiles/ms/icons/blue-dot.png'
                },
                title: 'Your Location'
            });

            // Search for centers near the current location
            fetch(`/centers/search?latitude=${pos.lat}&longitude=${pos.lng}`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                updateCentersList(data.centers);
                if (marker.getPosition()) {
                    const bounds = new google.maps.LatLngBounds();
                    bounds.extend(marker.getPosition());
                    bounds.extend(currentLocationMarker.getPosition());
                    map.fitBounds(bounds);
                } else {
                    map.setCenter(pos);
                }
            });
        });
    }
});

document.getElementById('searchByCity').addEventListener('click', function() {
    const city = document.getElementById('citySearch').value;
    if (city) {
        fetch(`/centers/search?city=${encodeURIComponent(city)}`, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            updateCentersList(data.centers);
        });
    }
});

function updateCentersList(centers) {
    const select = document.getElementById('center_id');
    select.innerHTML = '<option value="">Select a donation center</option>';
    
    centers.forEach(center => {
        const option = document.createElement('option');
        option.value = center.id;
        option.dataset.location = center.location;
        option.dataset.latitude = center.latitude;
        option.dataset.longitude = center.longitude;
        option.textContent = `${center.name} - ${center.city}${center.distance ? ` (${center.distance.toFixed(1)} km away)` : ''}`;
        select.appendChild(option);
    });
}

initMap();
</script>
@endpush
@endsection 