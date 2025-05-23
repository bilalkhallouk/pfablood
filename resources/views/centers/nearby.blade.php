@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h2 mb-0">Nearby Blood Centers</h1>
            <p class="text-muted">Find blood donation centers in your area</p>
        </div>
        <div class="col-md-4 text-md-end">
            <button class="btn btn-outline-danger" onclick="getCurrentLocation()">
                <i class="fas fa-location-dot me-2"></i>Use My Location
            </button>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0">
                    <i class="fas fa-search text-muted"></i>
                </span>
                <input type="text" class="form-control border-start-0" id="searchInput" 
                       placeholder="Search centers..." onkeyup="filterCenters()">
            </div>
        </div>
        <div class="col-md-3">
            <select class="form-select" id="radiusSelect" onchange="updateRadius()">
                <option value="5">Within 5 km</option>
                <option value="10">Within 10 km</option>
                <option value="20" selected>Within 20 km</option>
                <option value="50">Within 50 km</option>
                <option value="100">Within 100 km</option>
            </select>
        </div>
        <div class="col-md-3">
            <select class="form-select" id="bloodTypeFilter" onchange="filterCenters()">
                <option value="">All Blood Types</option>
                <option value="A+">A+</option>
                <option value="A-">A-</option>
                <option value="B+">B+</option>
                <option value="B-">B-</option>
                <option value="O+">O+</option>
                <option value="O-">O-</option>
                <option value="AB+">AB+</option>
                <option value="AB-">AB-</option>
            </select>
        </div>
    </div>

    <div class="row">
        <!-- Map Section -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div id="map" style="height: 600px; width: 100%; border-radius: 0.5rem;"></div>
                </div>
            </div>
        </div>

        <!-- Centers List -->
        <div class="col-lg-6">
            <div class="row g-4" id="centersList">
                @if(is_iterable($centers) && count($centers))
                    @foreach($centers as $center)
                        <div class="col-12 center-card" 
                             data-blood-types='{{ json_encode($center->available_blood_types) }}'
                             data-search-text="{{ strtolower($center->name . ' ' . $center->city . ' ' . $center->address) }}">
                            <div class="card h-100 border-0 shadow-sm hover-card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-danger bg-opacity-10 p-3 rounded-circle me-3">
                                                <i class="fas fa-hospital text-danger"></i>
                                            </div>
                                            <div>
                                                <h5 class="card-title mb-1">{{ $center->name }}</h5>
                                                <p class="text-muted mb-0">
                                                    <i class="fas fa-location-dot me-1"></i>{{ $center->city }}
                                                </p>
                                            </div>
                                        </div>
                                        <span class="badge bg-success" id="distance-{{ $center->id }}">Calculating...</span>
                                    </div>

                                    <div class="mb-3">
                                        <p class="card-text text-muted mb-1">
                                            <i class="fas fa-map-marker-alt me-2"></i>{{ $center->address }}
                                        </p>
                                        <p class="card-text text-muted mb-1">
                                            <i class="fas fa-phone me-2"></i>{{ $center->phone }}
                                            @if($center->emergency_contact)
                                                <span class="ms-2 text-danger">
                                                    <i class="fas fa-ambulance me-1"></i>Emergency: {{ $center->emergency_contact }}
                                                </span>
                                            @endif
                                        </p>
                                        @if($center->website)
                                            <p class="card-text text-muted mb-1">
                                                <i class="fas fa-globe me-2"></i>
                                                <a href="{{ $center->website }}" target="_blank" class="text-primary">Visit Website</a>
                                            </p>
                                        @endif
                                    </div>

                                    <!-- Blood Types -->
                                    <div class="mb-3">
                                        <h6 class="mb-2">Available Blood Types:</h6>
                                        @if(is_iterable($center->available_blood_types))
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach($center->available_blood_types as $type)
                                                    <span class="badge bg-danger bg-opacity-10 text-danger">{{ $type }}</span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Operating Hours -->
                                    <div class="mb-3">
                                        <h6 class="mb-2">
                                            <i class="fas fa-clock me-2"></i>Operating Hours
                                            <button class="btn btn-link btn-sm p-0 ms-2" 
                                                    data-bs-toggle="collapse" 
                                                    data-bs-target="#hours-{{ $center->id }}">
                                                Show
                                            </button>
                                        </h6>
                                        <div class="collapse" id="hours-{{ $center->id }}">
                                            <div class="card card-body bg-light border-0 p-2">
                                                @if(is_iterable($center->formatted_operating_hours))
                                                    @foreach($center->formatted_operating_hours as $hours)
                                                        <div class="small">{{ $hours }}</div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center">
                                        <button class="btn btn-primary btn-sm" 
                                                onclick="getDirections({{ $center->latitude }}, {{ $center->longitude }}, '{{ $center->name }}')">
                                            <i class="fas fa-directions me-1"></i>Get Directions
                                        </button>
                                        <button class="btn btn-outline-danger btn-sm" 
                                                onclick="showCenterDetails('{{ $center->id }}')">
                                            <i class="fas fa-info-circle me-1"></i>More Info
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-12">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>No blood centers found in your area.
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.hover-card {
    transition: transform 0.2s ease-in-out;
}
.hover-card:hover {
    transform: translateY(-5px);
}
.center-card.hidden {
    display: none;
}
</style>
@endpush

@push('scripts')
<!-- Google Maps JavaScript API -->
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&callback=initMap" async defer></script>

<script>
let map;
let userMarker;
let centerMarkers = [];
let userPosition = null;
let currentRadius = 20;

const mapStyle = [
    {
        "featureType": "water",
        "elementType": "geometry",
        "stylers": [{"color": "#e9e9e9"}, {"lightness": 17}]
    },
    {
        "featureType": "landscape",
        "elementType": "geometry",
        "stylers": [{"color": "#f5f5f5"}, {"lightness": 20}]
    },
    {
        "featureType": "road.highway",
        "elementType": "geometry.fill",
        "stylers": [{"color": "#ffffff"}, {"lightness": 17}]
    },
    {
        "featureType": "road.highway",
        "elementType": "geometry.stroke",
        "stylers": [{"color": "#ffffff"}, {"lightness": 29}, {"weight": 0.2}]
    },
    {
        "featureType": "road.arterial",
        "elementType": "geometry",
        "stylers": [{"color": "#ffffff"}, {"lightness": 18}]
    },
    {
        "featureType": "road.local",
        "elementType": "geometry",
        "stylers": [{"color": "#ffffff"}, {"lightness": 16}]
    },
    {
        "featureType": "poi",
        "elementType": "geometry",
        "stylers": [{"color": "#f5f5f5"}, {"lightness": 21}]
    },
    {
        "featureType": "poi.park",
        "elementType": "geometry",
        "stylers": [{"color": "#dedede"}, {"lightness": 21}]
    }
];

function initMap() {
    map = new google.maps.Map(document.getElementById('map'), {
        center: { lat: 31.7917, lng: -7.0926 },
        zoom: 6,
        styles: mapStyle
    });

    @if(is_iterable($centers) && count($centers))
        @foreach($centers as $center)
        addCenterMarker(
            {{ $center->latitude }},
            {{ $center->longitude }},
            "{{ $center->name }}",
            "{{ $center->address }}, {{ $center->city }}"
        );
        @endforeach
    @endif
}

function addCenterMarker(lat, lng, title, address) {
    const marker = new google.maps.Marker({
        position: { lat: lat, lng: lng },
        map: map,
        title: title,
        icon: {
            url: 'https://maps.google.com/mapfiles/ms/icons/red-dot.png'
        }
    });

    const infoWindow = new google.maps.InfoWindow({
        content: `
            <div class="p-2">
                <h6 class="mb-1">${title}</h6>
                <p class="mb-1 small text-muted">${address}</p>
                <button class="btn btn-sm btn-primary" onclick="getDirections(${lat}, ${lng}, '${title}')">
                    Get Directions
                </button>
            </div>
        `
    });

    marker.addListener('click', () => {
        infoWindow.open(map, marker);
    });

    centerMarkers.push(marker);
}

function getCurrentLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            position => {
                userPosition = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };

                if (userMarker) userMarker.setMap(null);
                userMarker = new google.maps.Marker({
                    position: userPosition,
                    map: map,
                    title: 'Your Location',
                    icon: {
                        url: 'https://maps.google.com/mapfiles/ms/icons/blue-dot.png'
                    }
                });

                map.setCenter(userPosition);
                map.setZoom(11);

                updateDistances();
                filterCenters();
            },
            error => {
                alert('Error getting your location. Please try again.');
            }
        );
    } else {
        alert('Geolocation is not supported by your browser.');
    }
}

function updateRadius() {
    currentRadius = parseFloat(document.getElementById('radiusSelect').value);
    filterCenters();
}

function filterCenters() {
    const searchText = document.getElementById('searchInput').value.toLowerCase();
    const bloodType = document.getElementById('bloodTypeFilter').value;
    const cards = document.querySelectorAll('.center-card');

    cards.forEach(card => {
        const cardSearchText = card.dataset.searchText;
        const cardBloodTypes = JSON.parse(card.dataset.bloodTypes);
        const distanceElement = card.querySelector('[id^="distance-"]');
        const distance = distanceElement ? parseFloat(distanceElement.textContent) : 0;

        let show = cardSearchText.includes(searchText);
        
        if (bloodType && show) {
            show = cardBloodTypes.includes(bloodType);
        }

        if (userPosition && show) {
            show = distance <= currentRadius;
        }

        card.classList.toggle('hidden', !show);
        
        // Update marker visibility
        const markerId = card.querySelector('[id^="distance-"]').id.split('-')[1];
        const marker = centerMarkers[markerId - 1];
        if (marker) {
            marker.setVisible(show);
        }
    });
}

function updateDistances() {
    @if(is_iterable($centers) && count($centers))
        @foreach($centers as $center)
        if ({{ $center->latitude }} && {{ $center->longitude }}) {
            const distance = calculateDistance(
                userPosition.lat,
                userPosition.lng,
                {{ $center->latitude }},
                {{ $center->longitude }}
            );
            document.getElementById('distance-{{ $center->id }}').textContent = 
                distance.toFixed(1) + ' km';
        }
        @endforeach
    @endif
}

function getDirections(lat, lng, centerName) {
    if (userPosition) {
        window.open(`https://www.google.com/maps/dir/${userPosition.lat},${userPosition.lng}/${lat},${lng}`);
    } else {
        window.open(`https://www.google.com/maps/dir//${lat},${lng}`);
    }
}

function calculateDistance(lat1, lon1, lat2, lon2) {
    const R = 6371;
    const dLat = toRad(lat2 - lat1);
    const dLon = toRad(lon2 - lon1);
    const a = 
        Math.sin(dLat/2) * Math.sin(dLat/2) +
        Math.cos(toRad(lat1)) * Math.cos(toRad(lat2)) * 
        Math.sin(dLon/2) * Math.sin(dLon/2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    return R * c;
}

function toRad(value) {
    return value * Math.PI / 180;
}

function showCenterDetails(centerId) {
    // You can implement a modal or expand the card to show more details
    const hoursElement = document.getElementById(`hours-${centerId}`);
    if (hoursElement) {
        hoursElement.classList.toggle('show');
    }
}
</script>
@endpush
@endsection 