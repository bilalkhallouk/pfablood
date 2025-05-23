@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Title -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Gestion des événements</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <a href="{{ route('admin.events.create') }}" class="btn btn-sm btn-outline-danger">
                    <i class="fas fa-plus"></i> Nouvel événement
                </a>
            </div>
        </div>
    </div>

    <!-- Events Grid -->
    <div class="row g-4">
        @foreach($events ?? [] as $event)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm">
                @if($event->image)
                    <img src="{{ Storage::url($event->image) }}" class="card-img-top" alt="{{ $event->title }}">
                @endif
                <div class="card-body">
                    <h5 class="card-title">{{ $event->title }}</h5>
                    <p class="card-text text-muted">
                        <i class="fas fa-calendar me-2"></i>{{ $event->formatted_date }}
                        <br>
                        <i class="fas fa-clock me-2"></i>{{ $event->formatted_time }}
                        <br>
                        <i class="fas fa-hospital me-2"></i>{{ $event->center->name }}
                    </p>
                    <p class="card-text">{{ Str::limit($event->description, 100) }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge bg-{{ $event->isFull() ? 'danger' : 'success' }}">
                            {{ $event->participants->count() }}/{{ $event->capacity }} participants
                        </span>
                        <div class="btn-group">
                            <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteEvent({{ $event->id }})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $events->links() ?? '' }}
    </div>
</div>

@endsection

@push('scripts')
<script>
function deleteEvent(eventId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cet événement ?')) {
        fetch(`/admin/events/${eventId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
        }).then(response => {
            if (response.ok) {
                location.reload();
            }
        });
    }
}
</script>
@endpush 