@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Title -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Créer un événement</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <a href="{{ route('admin.events') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>
        </div>
    </div>

    <!-- Event Creation Form -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-8">
                        <!-- Basic Information -->
                        <div class="mb-4">
                            <h5>Informations de base</h5>
                            <div class="mb-3">
                                <label for="title" class="form-label">Titre de l'événement</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                    id="title" name="title" value="{{ old('title') }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                    id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Date and Time -->
                        <div class="mb-4">
                            <h5>Date et heure</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="date" class="form-label">Date</label>
                                        <input type="date" class="form-control @error('date') is-invalid @enderror" 
                                            id="date" name="date" value="{{ old('date') }}" required>
                                        @error('date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="time" class="form-label">Heure</label>
                                        <input type="time" class="form-control @error('time') is-invalid @enderror" 
                                            id="time" name="time" value="{{ old('time') }}" required>
                                        @error('time')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Location -->
                        <div class="mb-4">
                            <h5>Lieu</h5>
                            <div class="mb-3">
                                <label for="center_id" class="form-label">Centre de don</label>
                                <select class="form-select @error('center_id') is-invalid @enderror" 
                                    id="center_id" name="center_id" required>
                                    <option value="">Sélectionner un centre</option>
                                    @foreach($centers ?? [] as $center)
                                        <option value="{{ $center->id }}" {{ old('center_id') == $center->id ? 'selected' : '' }}>
                                            {{ $center->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('center_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <!-- Event Settings -->
                        <div class="card border-0 bg-light">
                            <div class="card-body">
                                <h5>Paramètres de l'événement</h5>
                                
                                <div class="mb-3">
                                    <label for="capacity" class="form-label">Capacité maximale</label>
                                    <input type="number" class="form-control @error('capacity') is-invalid @enderror" 
                                        id="capacity" name="capacity" value="{{ old('capacity') }}" min="1" required>
                                    @error('capacity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="image" class="form-label">Image de l'événement</label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                        id="image" name="image" accept="image/*">
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Format recommandé : JPG, PNG (max 2MB)</div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" 
                                            {{ old('is_featured') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_featured">
                                            Mettre en avant sur la page d'accueil
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="mt-4">
                            <button type="submit" class="btn btn-danger w-100 mb-2">
                                <i class="fas fa-save me-2"></i>Créer l'événement
                            </button>
                            <a href="{{ route('admin.events') }}" class="btn btn-outline-secondary w-100">
                                Annuler
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 