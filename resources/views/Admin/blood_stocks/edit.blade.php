@extends('layouts.app')
@section('content')
<style>
    body {
        background: #f8f9fa;
    }
    .edit-card {
        border-radius: 1rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        transition: transform 0.2s;
    }
    .edit-card:hover {
        transform: translateY(-5px);
    }
    .form-control, .form-select {
        border-radius: 0.5rem;
        padding: 0.75rem 1rem;
        border: 1px solid #e3e6f0;
    }
    .form-control:focus, .form-select:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.2rem rgba(78,115,223,0.25);
    }
    .btn-save {
        border-radius: 0.5rem;
        padding: 0.75rem 2rem;
        font-weight: 600;
    }
    .stock-preview {
        background: linear-gradient(45deg, #4e73df, #224abe);
        color: white;
        border-radius: 1rem;
        padding: 2rem;
    }
    .progress {
        height: 8px;
        border-radius: 4px;
        background-color: rgba(255,255,255,0.2);
    }
    .progress-bar {
        background-color: white;
    }
</style>

<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-xl-8">
            <div class="card edit-card">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="h3 mb-0 text-gray-800">Modifier le Stock</h2>
                        <a href="{{ route('admin.blood-stocks.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Retour
                        </a>
                    </div>

                    <form action="{{ route('admin.blood-stocks.update', $stock->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-4">
                            <!-- Stock Preview -->
                            <div class="col-12">
                                <div class="stock-preview">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div>
                                            <h5 class="text-white-50 mb-1">Centre</h5>
                                            <h4 class="mb-0 text-white">{{ $stock->center->name }}</h4>
                                        </div>
                                        <span class="badge bg-light text-primary fs-5 px-3 py-2">
                                            {{ $stock->blood_type }}
                                        </span>
                                    </div>
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="text-white-50">Stock Actuel</span>
                                            <span class="text-white">{{ $stock->units }}</span>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar" 
                                                 style="width: {{ min(($stock->units / $stock->min_threshold) * 100, 100) }}%">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Stock Units -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Unités de Sang</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0">
                                            <i class="fas fa-tint text-muted"></i>
                                        </span>
                                        <input type="number" 
                                               name="units" 
                                               class="form-control @error('units') is-invalid @enderror" 
                                               value="{{ old('units', $stock->units) }}"
                                               min="0"
                                               required>
                                    </div>
                                    @error('units')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Minimum Threshold -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Seuil Minimum</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0">
                                            <i class="fas fa-exclamation-triangle text-muted"></i>
                                        </span>
                                        <input type="number" 
                                               name="min_threshold" 
                                               class="form-control @error('min_threshold') is-invalid @enderror" 
                                               value="{{ old('min_threshold', $stock->min_threshold) }}"
                                               min="0"
                                               required>
                                    </div>
                                    @error('min_threshold')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="col-12">
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary btn-save">
                                        <i class="fas fa-save me-2"></i>Enregistrer les Modifications
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const unitsInput = document.querySelector('input[name="units"]');
    const thresholdInput = document.querySelector('input[name="min_threshold"]');
    const progressBar = document.querySelector('.progress-bar');

    function updateProgressBar() {
        const units = parseInt(unitsInput.value) || 0;
        const threshold = parseInt(thresholdInput.value) || 1;
        const percentage = Math.min((units / threshold) * 100, 100);
        progressBar.style.width = `${percentage}%`;
    }

    unitsInput.addEventListener('input', updateProgressBar);
    thresholdInput.addEventListener('input', updateProgressBar);
});
</script>
@endpush
@endsection 