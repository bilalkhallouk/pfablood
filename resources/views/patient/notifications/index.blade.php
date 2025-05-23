@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4"><i class="fas fa-bell me-2 text-warning"></i>Mes notifications</h2>
    <div class="card">
        <div class="card-body p-0">
            <ul class="list-group list-group-flush">
                @forelse($notifications as $notification)
                    <li class="list-group-item d-flex justify-content-between align-items-center {{ $notification->read ? '' : 'bg-light' }}">
                        <div>
                            {!! $notification->message !!}
                            <br>
                            <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                        </div>
                        @if(!$notification->read)
                            <span class="badge bg-warning text-dark">Non lu</span>
                        @endif
                    </li>
                @empty
                    <li class="list-group-item text-center text-muted py-4">Aucune notification trouvée.</li>
                @endforelse
            </ul>
        </div>
        <div class="card-footer">
            {{ $notifications->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.mark-as-read').forEach(function(link) {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        var id = this.dataset.id;
        fetch('/patient/notifications/' + id + '/mark-read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        }).then(response => {
            if (response.ok) {
                location.reload();
            }
        });
    });
});
</script>
@endpush 