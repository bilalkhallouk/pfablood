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
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $request)
                        <tr>
                            <td>{{ $request->user->name ?? '-' }}</td>
                            <td>{{ $request->blood_type }}</td>
                            <td>{{ $request->units_needed }}</td>
                            <td>{{ ucfirst($request->urgency) }}</td>
                            <td>
                                <span class="badge bg-{{ $request->status == 'approved' ? 'success' : ($request->status == 'pending' ? 'warning' : ($request->status == 'rejected' ? 'danger' : 'secondary')) }}">
                                    {{ ucfirst($request->status) }}
                                </span>
                            </td>
                            <td>{{ $request->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                @if($request->status == 'pending')
                                <form action="{{ route('admin.blood-requests.accept', $request->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">Accepter</button>
                                </form>
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