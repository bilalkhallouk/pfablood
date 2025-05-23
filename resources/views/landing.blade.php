@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
    <!-- Hero Section -->
    <div class="hero-section position-relative">
        <div class="hero-content">
        <div class="container">
            <div class="row align-items-center">
                    <div class="col-lg-6 text-white">
                        <h1 class="display-3 fw-bold mb-3">Bienvenue sur PFA Blood</h1>
                        <p class="lead mb-4">Connectez-vous en temps réel avec des donneurs et des receveurs de sang.
                          <br/>  Chaque goutte compte pour sauver une vie.</p>
                        @guest
                            <a href="{{ route('register') }}" class="btn btn-light btn-lg px-4">S'inscrire maintenant</a>
                        @else
                            @php
                                $dashboardRoute = auth()->user()->role === 'admin' 
                                    ? route('admin.dashboard')
                                    : (auth()->user()->role === 'patient' 
                                        ? route('patient.dashboard') 
                                        : route('donor.dashboard'));
                            @endphp
                            <a href="{{ $dashboardRoute }}" class="btn btn-light btn-lg px-4">Aller au tableau de bord</a>
                        @endguest
                    </div>
                    <div class="col-lg-6">
                        <div class="hero-image">
                            <img src="{{ asset('images/blood-donation-hero.jpg') }}" style="max-width: 450px; height: auto;" alt="Blood Donation" class="img-fluid rounded-3 shadow-lg">
                        </div>
                    </div>
                </div>
            </div>
        </div>
            </div>
            
    <!-- Features Section -->
    <div class="container py-5">
            <div class="row g-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon bg-danger bg-opacity-10 text-danger rounded-circle mb-3 mx-auto">
                            <i class="fas fa-search fa-2x"></i>
                        </div>
                        <h3 class="h4 mb-3">Trouver des donneurs</h3>
                        <p class="text-muted mb-0">Localisez rapidement les donneurs de sang près de chez vous en fonction du groupe sanguin et de la localisation.</p>
                    </div>
                </div>
                                </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon bg-danger bg-opacity-10 text-danger rounded-circle mb-3 mx-auto">
                            <i class="fas fa-bell fa-2x"></i>
                        </div>
                        <h3 class="h4 mb-3">Alertes d'urgence</h3>
                        <p class="text-muted mb-0">Envoyez des demandes urgentes de sang aux donneurs à proximité en cas d'urgence.</p>
                    </div>
                </div>
                                </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon bg-danger bg-opacity-10 text-danger rounded-circle mb-3 mx-auto">
                            <i class="fas fa-hospital fa-2x"></i>
                        </div>
                        <h3 class="h4 mb-3">Centres de don de sang</h3>
                        <p class="text-muted mb-0">Trouvez les centres de don les plus proches et suivez les unités de sang disponibles.</p>
                    </div>
                </div>
            </div>
        </div>
            </div>
            
    <!-- Statistics Section -->
    <div class="bg-light py-5">
        <div class="container">
            <div class="row text-center g-4">
                <div class="col-md-3">
                    <h2 class="display-4 fw-bold text-danger mb-0">
                        @php
                            try {
                                echo \App\Models\User::where('role', 'donor')->count();
                            } catch (\Exception $e) {
                                echo '0';
                            }
                        @endphp
                    </h2>
                    <p class="text-muted">Donneurs enregistrés</p>
                </div>
                <div class="col-md-3">
                    <h2 class="display-4 fw-bold text-danger mb-0">
                        @php
                            try {
                                echo \App\Models\BloodRequest::where('status', 'approved')->count();
                            } catch (\Exception $e) {
                                echo '0';
                            }
                        @endphp
                    </h2>
                    <p class="text-muted">Dons réussis</p>
                </div>
                <div class="col-md-3">
                    <h2 class="display-4 fw-bold text-danger mb-0">
                        @php
                            try {
                                echo \App\Models\User::where('role', 'patient')->count();
                            } catch (\Exception $e) {
                                echo '0';
                            }
                        @endphp
                    </h2>
                    <p class="text-muted">Patients enregistrés</p>
                                </div>
                <div class="col-md-3">
                    <h2 class="display-4 fw-bold text-danger mb-0">
                        @php
                            try {
                                echo \App\Models\Center::count();
                            } catch (\Exception $e) {
                                echo '0';
                            }
                        @endphp
                    </h2>
                    <p class="text-muted">Centres de don de sang</p>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
        <div class="container py-5">
            <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <h2 class="display-5 mb-4">Prêt à sauver des vies ?</h2>
                <p class="lead mb-4">Rejoignez notre communauté de donneurs de sang et contribuez à sauver des vies dès aujourd’hui.</p>
                @guest
                    <a href="{{ route('register') }}" class="btn btn-danger btn-lg">Get Started</a>
                @endguest
            </div>
        </div>
                    </div>
                </div>
                
<style>
.hero-section {
    background: linear-gradient(to right, #dc3545 50%, #f8f9fa 50%);
    min-height: 480px;
    overflow: hidden;
}

.hero-content {
    padding: 60px 0;
    background: linear-gradient(135deg, rgba(220, 53, 69, 0.95) 50%, rgba(248, 249, 250, 0.95) 50%);
}

.hero-image {
    position: relative;
    z-index: 1;
    padding: 15px;
    display: flex;
    justify-content: center;
}

.hero-image img {
    border-radius: 20px;
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
    transition: transform 0.3s ease;
}

.hero-image img:hover {
    transform: translateY(-5px);
}

.feature-icon {
    width: 80px;
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.card {
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-5px);
}

@media (max-width: 991.98px) {
    .hero-section {
        background: #dc3545;
    }
    
    .hero-content {
        background: rgba(220, 53, 69, 0.95);
        padding: 60px 0;
    }
    
    .hero-image {
        margin-top: 40px;
    }
}
</style>
@endsection