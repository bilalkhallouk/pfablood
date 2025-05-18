<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood PFA | Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .blood-gradient { background: linear-gradient(135deg, #e63946 0%, #d00000 100%); }
        .blood-text { color: #e63946; }
        .blood-btn { background-color: #e63946; color: white; }
        .blood-btn:hover { background-color: #d00000; color: white; }
        .blood-type-btn { transition: all 0.3s ease; }
        .blood-type-btn:hover { transform: translateY(-2px); }
        .blood-type-btn.selected { background-color: #e63946 !important; color: white !important; border-color: #e63946 !important; }
        .spinner-border { display: inline-block; width: 1rem; height: 1rem; vertical-align: text-bottom; border: 0.15em solid currentColor; border-right-color: transparent; border-radius: 50%; animation: spinner-border .75s linear infinite; }
        @keyframes spinner-border { to { transform: rotate(360deg); } }
    </style>
</head>
<body class="bg-gray-50">
    <div class="container-fluid min-vh-100 d-flex justify-content-center align-items-center">
        <div class="col-lg-6 bg-white rounded-4 shadow-sm p-5">
            <div class="text-center mb-4">
                <h2 class="blood-text fw-bold mb-1"><i class="fas fa-tint me-2"></i>Blood PFA</h2>
                <p class="text-muted">Créer un nouveau compte</p>
            </div>
            <form id="registerForm" method="POST" action="{{ route('register') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Nom complet <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="name" required>
                    <div class="invalid-feedback" id="name-error"></div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" name="email" required>
                    <div class="invalid-feedback" id="email-error"></div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Mot de passe <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" name="password" required minlength="8">
                    <div class="invalid-feedback" id="password-error"></div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Confirmation du mot de passe <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" name="password_confirmation" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Téléphone</label>
                    <input type="text" class="form-control" name="phone" maxlength="20">
                    <div class="invalid-feedback" id="phone-error"></div>
                </div>
                <div class="mb-3">
                    <label class="form-label">CIN</label>
                    <input type="text" class="form-control" name="cin" maxlength="20">
                    <div class="invalid-feedback" id="cin-error"></div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Âge</label>
                    <input type="number" class="form-control" name="age" min="16" max="100">
                    <div class="invalid-feedback" id="age-error"></div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Rôle <span class="text-danger">*</span></label>
                    <select class="form-select" name="role" required>
                        <option value="">Sélectionner un rôle</option>
                        <option value="donor">Donneur</option>
                        <option value="patient">Patient</option>
                    </select>
                    <div class="invalid-feedback" id="role-error"></div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Date du dernier don</label>
                    <input type="datetime-local" class="form-control" name="last_donation_at">
                    <div class="invalid-feedback" id="last_donation_at-error"></div>
                </div>
                <button type="submit" class="btn blood-btn w-100 py-2 fw-medium">
                    <span id="submit-text">S'inscrire</span>
                    <span id="spinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                </button>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('registerForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const form = this;
            const submitBtn = form.querySelector('button[type="submit"]');
            const submitText = document.getElementById('submit-text');
            const spinner = document.getElementById('spinner');
            
            // Clear previous errors
            document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            document.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
            
            // Show loading state
            submitText.textContent = 'Enregistrement...';
            spinner.classList.remove('d-none');
            submitBtn.disabled = true;

            try {
                const formData = new FormData(form);
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    alert(result.message);
                    window.location.href = result.redirect;
                } else {
                    // Display validation errors
                    if (result.errors) {
                        for (const field in result.errors) {
                            const input = form.querySelector(`[name="${field}"]`);
                            const errorDiv = document.getElementById(`${field}-error`);
                            if (input && errorDiv) {
                                input.classList.add('is-invalid');
                                errorDiv.textContent = result.errors[field][0];
                            }
                        }
                    } else {
                        alert(result.message || "Erreur lors de l'enregistrement");
                    }
                }
            } catch (error) {
                console.error('Erreur:', error);
                alert("Une erreur réseau est survenue");
            } finally {
                submitText.textContent = 'S\'inscrire';
                spinner.classList.add('d-none');
                submitBtn.disabled = false;
            }
        });
    </script>
</body>
</html>