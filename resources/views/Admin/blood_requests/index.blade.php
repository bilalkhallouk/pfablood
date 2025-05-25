@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Gérer les demandes de sang</h2>
    </div>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="card">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0">Demandes de sang en attente</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Patient</th>
                            <th>Groupe sanguin</th>
                            <th>Unités</th>
                            <th>Urgence</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Ordonnance</th>
                            <th>Stock</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $request)
                        @php
                            $stock = \App\Models\BloodStock::where('center_id', $request->center_id ?? null)
                                ->where('blood_type', $request->blood_type)
                                ->first();
                            $stockUnits = $stock->units ?? $stock->units_available ?? 0;
                            $canAccept = $stockUnits >= $request->units_needed;
                        @endphp
                        <tr>
                            <td>{{ $request->user->name ?? '-' }}</td>
                            <td>{{ $request->blood_type }}</td>
                            <td>
                                <span class="fw-bold">{{ $request->units_needed }} unités demandées</span>
                                <br>
                                @if($stock)
                                    <small>
                                        <span class="text-muted">Stock: {{ $stockUnits }}</span>
                                        <span class="badge {{ $canAccept ? 'bg-success' : 'bg-danger' }} ms-1">{{ $canAccept ? 'OK' : 'Bas' }}</span>
                                    </small>
                                @else
                                    <span class="text-danger">Aucune donnée</span>
                                @endif
                            </td>
                            <td>{{ ucfirst($request->urgency) }}</td>
                            <td>
                                <span class="badge bg-{{ $request->status == 'approved' ? 'success' : ($request->status == 'pending' ? 'warning' : ($request->status == 'rejected' ? 'danger' : 'secondary')) }}">
                                    {{ ucfirst($request->status) }}
                                </span>
                            </td>
                            <td>{{ $request->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                @if($request->prescription_file)
                                    <a href="{{ asset('storage/' . $request->prescription_file) }}" target="_blank">Voir l'ordonnance</a>
                                @else
                                    <span class="text-muted">Aucune</span>
                                @endif
                            </td>
                            <td>
                                @if($request->status == 'pending')
                                    <!-- Accept Button with Modal -->
                                    <button 
                                        class="btn btn-success btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#acceptModal{{ $request->id }}"
                                        @if(!$canAccept) disabled title="Stock insuffisant" @endif
                                    >
                                        Accepter
                                    </button>
                                    <!-- Modal -->
                                    <div class="modal fade" id="acceptModal{{ $request->id }}" tabindex="-1" aria-labelledby="acceptModalLabel{{ $request->id }}" aria-hidden="true">
                                      <div class="modal-dialog">
                                        <div class="modal-content">
                                          <form action="{{ route('admin.blood-requests.accept', $request->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-header">
                                              <h5 class="modal-title" id="acceptModalLabel{{ $request->id }}">Confirmer l'acceptation</h5>
                                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                            </div>
                                            <div class="modal-body">
                                              Voulez-vous vraiment accepter cette demande ?<br>
                                              <strong>Stock disponible :</strong> {{ $stockUnits }} unités<br>
                                              <strong>Demandé :</strong> {{ $request->units_needed }} unités
                                              @if(!$canAccept)
                                                <div class="alert alert-danger mt-2">Stock insuffisant !</div>
                                              @endif
                                            </div>
                                            <div class="modal-footer">
                                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                              <button type="submit" class="btn btn-success" @if(!$canAccept) disabled @endif>Confirmer</button>
                                            </div>
                                          </form>
                                        </div>
                                      </div>
                                    </div>
                                    <form action="{{ route('admin.blood-requests.reject', $request->id) }}" method="POST" class="d-inline ms-1">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm">Rejeter</button>
                                    </form>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Aucune demande trouvée.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $requests->links() }}
        </div>
    </div>
</div>
@endsection 