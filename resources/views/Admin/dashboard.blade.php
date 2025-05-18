<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Blood PFA</title>
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
        .sidebar {
            min-height: 100vh;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-2 col-md-3 px-0 blood-gradient sidebar text-white">
                <div class="d-flex flex-column">
                    <div class="py-4 px-3 mb-4 d-flex align-items-center">
                        <i class="fas fa-tint fs-2 me-2"></i>
                        <h4 class="m-0">Blood PFA</h4>
                    </div>
                    
                    <div class="px-3 mb-4">
                        <small class="text-uppercase text-white-50">Menu</small>
                        <ul class="nav flex-column mt-2">
                            <li class="nav-item">
                                <a href="#" class="nav-link text-white active">
                                    <i class="fas fa-tachometer-alt me-2"></i> Tableau de bord
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link text-white">
                                    <i class="fas fa-users me-2"></i> Gestion des utilisateurs
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link text-white">
                                    <i class="fas fa-hospital me-2"></i> Centres de don
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link text-white">
                                    <i class="fas fa-calendar-alt me-2"></i> Événements
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link text-white">
                                    <i class="fas fa-chart-bar me-2"></i> Statistiques
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="mt-auto px-3 mb-4">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-light w-100">
                                <i class="fas fa-sign-out-alt me-2"></i> Déconnexion
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-lg-10 col-md-9 p-4">
                <!-- Top navbar -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Tableau de bord administrateur</h2>
                    <div class="d-flex align-items-center">
                        <div class="dropdown">
                            <button class="btn btn-light dropdown-toggle" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle me-1"></i>
                                {{ auth()->user()->name }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                                <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i> Profil</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i> Paramètres</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt me-2"></i> Déconnexion
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Dashboard content -->
                <div class="row g-4">
                    <div class="col-xl-3 col-md-6">
                        <div class="card text-center h-100 border-0 shadow-sm">
                            <div class="card-body">
                                <div class="display-6 text-primary mb-3">
                                    <i class="fas fa-users"></i>
                                </div>
                                <h5 class="card-title">Total Utilisateurs</h5>
                                <h2 class="mb-0">6,382</h2>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xl-3 col-md-6">
                        <div class="card text-center h-100 border-0 shadow-sm">
                            <div class="card-body">
                                <div class="display-6 blood-text mb-3">
                                    <i class="fas fa-tint"></i>
                                </div>  
                                <h5 class="card-title">Donneurs</h5>
                                <h2 class="mb-0">4,129</h2>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xl-3 col-md-6">
                        <div class="card text-center h-100 border-0 shadow-sm">
                            <div class="card-body">
                                <div class="display-6 text-success mb-3">
                                    <i class="fas fa-user-injured"></i>
                                </div>
                                <h5 class="card-title">Patients</h5>
                                <h2 class="mb-0">2,253</h2>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xl-3 col-md-6">
                        <div class="card text-center h-100 border-0 shadow-sm">
                            <div class="card-body">
                                <div class="display-6 text-warning mb-3">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                                <h5 class="card-title">Dons ce mois</h5>
                                <h2 class="mb-0">187</h2>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-4 mt-4">
                    <div class="col-lg-8">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white">
                                <h5 class="mb-0">Activité récente</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Utilisateur</th>
                                                <th>Action</th>
                                                <th>Date</th>
                                                <th>Statut</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Jean Dupont</td>
                                                <td>Don de sang</td>
                                                <td>25 avril 2025</td>
                                                <td><span class="badge bg-success">Complété</span></td>
                                            </tr>
                                            <tr>
                                                <td>Marie Martin</td>
                                                <td>Inscription</td>
                                                <td>24 avril 2025</td>
                                                <td><span class="badge bg-info">Nouveau</span></td>
                                            </tr>
                                            <tr>
                                                <td>Ahmed Benali</td>
                                                <td>Demande de sang</td>
                                                <td>23 avril 2025</td>
                                                <td><span class="badge bg-warning">En attente</span></td>
                                            </tr>
                                            <tr>
                                                <td>Sophie Blanc</td>
                                                <td>Don de sang</td>
                                                <td>22 avril 2025</td>
                                                <td><span class="badge bg-success">Complété</span></td>
                                            </tr>
                                            <tr>
                                                <td>Omar Nabil</td>
                                                <td>Mise à jour profil</td>
                                                <td>21 avril 2025</td>
                                                <td><span class="badge bg-secondary">Modifié</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-white">
                                <h5 class="mb-0">Distribution des groupes sanguins</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>A+</span>
                                    <span>34%</span>
                                </div>
                                <div class="progress mb-3" style="height: 10px;">
                                    <div class="progress-bar blood-gradient" role="progressbar" style="width: 34%"></div>
                                </div>
                                
                                <div class="d-flex justify-content-between mb-2">
                                    <span>O+</span>
                                    <span>38%</span>
                                </div>
                                <div class="progress mb-3" style="height: 10px;">
                                    <div class="progress-bar blood-gradient" role="progressbar" style="width: 38%"></div>
                                </div>
                                
                                <div class="d-flex justify-content-between mb-2">
                                    <span>B+</span>
                                    <span>18%</span>
                                </div>
                                <div class="progress mb-3" style="height: 10px;">
                                    <div class="progress-bar blood-gradient" role="progressbar" style="width: 18%"></div>
                                </div>
                                
                                <div class="d-flex justify-content-between mb-2">
                                    <span>AB+</span>
                                    <span>4%</span>
                                </div>
                                <div class="progress mb-3" style="height: 10px;">
                                    <div class="progress-bar blood-gradient" role="progressbar" style="width: 4%"></div>
                                </div>
                                
                                <div class="d-flex justify-content-between mb-2">
                                    <span>A-</span>
                                    <span>2%</span>
                                </div>
                                <div class="progress mb-3" style="height: 10px;">
                                    <div class="progress-bar blood-gradient" role="progressbar" style="width: 2%"></div>
                                </div>
                                
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Autres</span>
                                    <span>4%</span>
                                </div>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar blood-gradient" role="progressbar" style="width: 4%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>