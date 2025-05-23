@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>My Appointments</h2>
        <a href="{{ route('donor.appointments.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Schedule New Appointment
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            @if($appointments->isEmpty())
                <div class="text-center py-4">
                    <i class="fas fa-calendar fa-3x text-muted mb-3"></i>
                    <p class="mb-0">You don't have any appointments scheduled.</p>
                    <a href="{{ route('donor.appointments.create') }}" class="btn btn-primary mt-3">
                        Schedule Your First Appointment
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Date & Time</th>
                                <th>Center</th>
                                <th>Status</th>
                                <th>Notes</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($appointments as $appointment)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="calendar-date text-center me-3">
                                                <div class="month">{{ $appointment->scheduled_at->format('M') }}</div>
                                                <div class="day">{{ $appointment->scheduled_at->format('d') }}</div>
                                            </div>
                                            <div>{{ $appointment->scheduled_at->format('g:i A') }}</div>
                                        </div>
                                    </td>
                                    <td>{{ $appointment->center->name }}</td>
                                    <td>
                                        @switch($appointment->status)
                                            @case('pending')
                                                <span class="badge bg-warning">Pending</span>
                                                @break
                                            @case('confirmed')
                                                <span class="badge bg-success">Confirmed</span>
                                                @break
                                            @case('completed')
                                                <span class="badge bg-info">Completed</span>
                                                @break
                                            @case('cancelled')
                                                <span class="badge bg-danger">Cancelled</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $appointment->notes ?? 'No notes' }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('donor.appointments.show', $appointment) }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($appointment->status !== 'cancelled' && $appointment->scheduled_at->isFuture())
                                                <form action="{{ route('donor.appointments.cancel', $appointment) }}" 
                                                      method="POST" 
                                                      onsubmit="return confirm('Are you sure you want to cancel this appointment?');">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $appointments->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
    .calendar-date {
        width: 50px;
        border: 1px solid #dee2e6;
        border-radius: 4px;
    }
    .calendar-date .month {
        background-color: #dc3545;
        color: white;
        text-align: center;
        padding: 2px;
        font-size: 12px;
        text-transform: uppercase;
    }
    .calendar-date .day {
        font-size: 18px;
        font-weight: bold;
        padding: 4px;
        text-align: center;
    }
</style>
@endpush
@endsection 