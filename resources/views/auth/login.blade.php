<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood PFA | Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
   
    <style>
        .blood-gradient {
            background: linear-gradient(135deg, #e63946 0%, #d00000 100%);
        }
        .blood-text {
            color: #e63946;
        }
        .blood-btn {
            background-color: #e63946;
            color: white;
        }
        .blood-btn:hover {
            background-color: #d00000;
            color: white;
        }
        .password-toggle {
            cursor: pointer;
        }
        .blood-type-btn {
            transition: all 0.3s ease;
        }
        .blood-type-btn:hover {
            transform: translateY(-2px);
        }
        .blood-type-btn.selected {
            background-color: #e63946 !important;
            color: white !important;
            border-color: #e63946 !important;
        }
        .form-switch {
            animation: fadeIn 0.4s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="container-fluid min-vh-100">
        <div class="row min-vh-100">
            <div class="col-lg-6 d-none d-lg-flex blood-gradient text-white justify-content-center align-items-center p-5">
                <div class="text-center">
                    <h1 class="display-3 fw-bold mb-4">
                        <i class="fas fa-tint me-2"></i>Faire un don
                    </h1>
                    <p class="fs-5 mb-5">Un seul don peut sauver jusqu'à 3 vies.<br> Rejoignez dès aujourd'hui notre communauté de héros.</p>

                    &nbsp;&nbsp;&nbsp;<img src="https://cdn-icons-png.flaticon.com/128/2167/2167191.png" alt="Blood Donation" class="img-fluid" style="max-height: 300px; margin-left: 190px;">                 
                   
                    <div class="mt-5">
                        <div class="d-flex justify-content-center gap-4">
                            <div class="text-center">
                                <div class="fs-2 fw-bold">10K+</div>
                                <div class="text-white-50">Dons</div>
                            </div>
                            <div class="text-center">
                                <div class="fs-2 fw-bold">5K+</div>
                                <div class="text-white-50">Vies sauvées</div>
                            </div>
                            <div class="text-center">
                                <div class="fs-2 fw-bold">100+</div>
                                <div class="text-white-50">Partenaires</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

           
            <div class="col-lg-6 d-flex justify-content-center align-items-center p-4">
                <div class="w-100" style="max-width: 450px;">
                   
                    <div id="login-form" class="bg-white rounded-4 shadow-sm p-4 p-md-5" style="display: {{ session('show_signup') ? 'none' : 'block' }}">
                        <div class="text-center mb-4">
                            <h2 class="blood-text fw-bold mb-1">
                                <i class="fas fa-tint me-2"></i>Blood PFA
                            </h2>
                            <p class="text-muted">Connectez-vous à votre compte donneur</p>
                        </div>

                        <h4 class="text-center mb-4 fw-bold">Entrez vos identifiants de connexion</h4>

                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form id="loginForm" method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="login-email" class="form-label fw-medium">Adresse e-mail</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-transparent">
                                        <i class="far fa-envelope text-muted"></i>
                                    </span>
                                    <input type="email" class="form-control border-start-0 @error('email') is-invalid @enderror" 
                                        id="login-email" name="email" value="{{ old('email') }}" 
                                        placeholder="votre@pfablood.com" required>
                                </div>
                                @error('email')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="login-password" class="form-label fw-medium">Mot de passe</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-transparent">
                                        <i class="fas fa-lock text-muted"></i>
                                    </span>
                                    <input type="password" class="form-control border-start-0 pe-5 @error('password') is-invalid @enderror" 
                                        id="login-password" name="password" placeholder="Mot de passe" required>
                                    <span class="password-toggle position-absolute end-0 top-50 translate-middle-y me-3 text-muted">
                                        <i class="far fa-eye" id="login-toggle-icon"></i>
                                    </span>
                                </div>
                                @error('password')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                                <div class="text-end mt-2">
                                    <a href="#" class="text-decoration-none blood-text small">Mot de passe oublié ?</a>
                                </div>
                            </div>

                            <button type="submit" class="btn blood-btn w-100 py-2 fw-medium mt-3">
                                Log In
                            </button>
                            <div class="text-center mt-4">
                                <p class="text-muted">Pas encore de compte ?
                                    <a href="{{ route('register') }}" class="text-decoration-none blood-text fw-medium" id="show-signup">Rejoignez-nous</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
 
    <script>
        function setupPasswordToggle(fieldId, toggleIconId) {
            const passwordField = document.getElementById(fieldId);
            const toggleIcon = document.getElementById(toggleIconId);
            
            if (passwordField && toggleIcon) {
                toggleIcon.addEventListener('click', function() {
                    if (passwordField.type === 'password') {
                        passwordField.type = 'text';
                        toggleIcon.classList.remove('fa-eye');
                        toggleIcon.classList.add('fa-eye-slash');
                    } else {
                        passwordField.type = 'password';
                        toggleIcon.classList.remove('fa-eye-slash');
                        toggleIcon.classList.add('fa-eye');
                    }
                });
            }
        }

        setupPasswordToggle('login-password', 'login-toggle-icon');
    </script>
</body>
</html>