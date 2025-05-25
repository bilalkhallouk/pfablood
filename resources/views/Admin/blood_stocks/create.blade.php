@extends('layouts.app')
@section('content')
<style>
    body {
        background: #f8f9fa;
    }
    .create-card {
        border-radius: 1rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        transition: transform 0.2s;
    }
    .create-card:hover {
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
    .preview-card {
        background: linear-gradient(45deg, #4e73df, #224abe);
        color: white;
        border-radius: 1rem;
        padding: 2rem;
    }
    .blood-type-select {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
    }
    .blood-type-option {
        border: 2px solid #e3e6f0;
        border-radius: 0.5rem;
        padding: 1rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
    }
    .blood-type-option:hover {
        border-color: #4e73df;
        background-color: #f8f9fc;
    }
    .blood-type-option.selected {
        border-color: #4e73df;
        background-color: #4e73df;
        color: white;
    }
    .blood-type-option input {
        display: none;
    }
</style>

<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-xl-8">
            <div class="card create-card">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="h3 mb-0 text-gray-800">Nouveau Stock</h2>
                        <a href="{{ route('admin.blood-stocks.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Retour
                        </a>
                    </div>

                    <form action="{{ route('admin.blood-stocks.store') }}" method="POST">
                        @csrf

                        <div class="row g-4">
                            <!-- Center Selection -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Centre</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0">
                                            <i class="fas fa-hospital text-muted"></i>
                                        </span>
                                        <select name="center_id" 
                                                class="form-select @error('center_id') is-invalid @enderror" 
                                                required>
                                            <option value="">Sélectionner un centre</option>
                                            @foreach($centers as $center)
                                                <option value="{{ $center->id }}" 
                                                        {{ old('center_id') == $center->id ? 'selected' : '' }}>
                                                    {{ $center->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('center_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Blood Type Selection -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Groupe Sanguin</label>
                                    <div class="blood-type-select">
                                        @foreach($bloodTypes as $type)
                                            <label class="blood-type-option {{ old('blood_type') == $type ? 'selected' : '' }}">
                                                <input type="radio" 
                                                       name="blood_type" 
                                                       value="{{ $type }}"
                                                       {{ old('blood_type') == $type ? 'checked' : '' }}
                                                       required>
                                                {{ $type }}
                                            </label>
                                        @endforeach
                                    </div>
                                    @error('blood_type')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
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
                                               value="{{ old('units') }}"
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
                                               value="{{ old('min_threshold') }}"
                                               min="0"
                                               required>
                                    </div>
                                    @error('min_threshold')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Preview Card -->
                            <div class="col-12">
                                <div class="preview-card">
                                    <h5 class="text-white-50 mb-3">Aperçu</h5>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <small class="text-white-50">Centre</small>
                                                <div id="previewCenter">-</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <small class="text-white-50">Groupe Sanguin</small>
                                                <div id="previewBloodType">-</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <small class="text-white-50">Stock</small>
                                                <div id="previewUnits">-</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="col-12">
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary btn-save">
                                        <i class="fas fa-save me-2"></i>Créer le Stock
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
    // Blood Type Selection
    const bloodTypeOptions = document.querySelectorAll('.blood-type-option');
    bloodTypeOptions.forEach(option => {
        option.addEventListener('click', function() {
            bloodTypeOptions.forEach(opt => opt.classList.remove('selected'));
            this.classList.add('selected');
            this.querySelector('input').checked = true;
            updatePreview();
        });
    });

    // Form Inputs
    const centerSelect = document.querySelector('select[name="center_id"]');
    const unitsInput = document.querySelector('input[name="units"]');
    const thresholdInput = document.querySelector('input[name="min_threshold"]');

    // Preview Elements
    const previewCenter = document.getElementById('previewCenter');
    const previewBloodType = document.getElementById('previewBloodType');
    const previewUnits = document.getElementById('previewUnits');

    function updatePreview() {
        const selectedCenter = centerSelect.options[centerSelect.selectedIndex];
        const selectedBloodType = document.querySelector('input[name="blood_type"]:checked');
        
        previewCenter.textContent = selectedCenter.value ? selectedCenter.text : '-';
        previewBloodType.textContent = selectedBloodType ? selectedBloodType.value : '-';
        previewUnits.textContent = unitsInput.value ? `${unitsInput.value} unités` : '-';
    }

    centerSelect.addEventListener('change', updatePreview);
    unitsInput.addEventListener('input', updatePreview);
    thresholdInput.addEventListener('input', updatePreview);

    // Initial preview update
    updatePreview();
});
</script>
@endpush
@endsection 