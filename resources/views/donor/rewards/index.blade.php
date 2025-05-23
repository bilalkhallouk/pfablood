@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Rewards & Achievements</h2>

    <!-- Stats Overview -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <i class="fas fa-tint fa-2x mb-2"></i>
                    <h3 class="mb-0">{{ $totalDonations }}</h3>
                    <p class="mb-0">Total Donations</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body text-center">
                    <i class="fas fa-heartbeat fa-2x mb-2"></i>
                    <h3 class="mb-0">{{ $emergencyDonations }}</h3>
                    <p class="mb-0">Emergency Donations</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <i class="fas fa-fire fa-2x mb-2"></i>
                    <h3 class="mb-0">{{ $donationStreak }}</h3>
                    <p class="mb-0">Donation Streak</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <i class="fas fa-users fa-2x mb-2"></i>
                    <h3 class="mb-0">{{ $referralCount }}</h3>
                    <p class="mb-0">Referrals</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Next Milestone -->
    @if($nextMilestone)
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div class="me-4">
                    <i class="fas fa-trophy fa-3x text-warning"></i>
                </div>
                <div>
                    <h4 class="mb-1">Next Milestone: {{ $nextMilestone['donations'] }} Donations</h4>
                    <p class="mb-0">You need {{ $nextMilestone['remaining'] }} more donation(s) to reach this milestone!</p>
                    <div class="progress mt-2" style="height: 10px;">
                        @php
                            $progress = (($nextMilestone['donations'] - $nextMilestone['remaining']) / $nextMilestone['donations']) * 100;
                        @endphp
                        <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $progress }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row">
        <!-- Earned Badges -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-award me-2"></i>Your Achievements</h5>
                </div>
                <div class="card-body">
                    @if($earnedBadges->isEmpty())
                        <p class="text-muted text-center py-4">
                            <i class="fas fa-award fa-3x mb-3 d-block"></i>
                            You haven't earned any badges yet. Start donating to unlock achievements!
                        </p>
                    @else
                        <div class="row g-3">
                            @foreach($earnedBadges as $badge)
                                <div class="col-md-6">
                                    <div class="achievement-card">
                                        <div class="d-flex align-items-center">
                                            <div class="achievement-icon me-3">
                                                <i class="{{ $badge->icon }} fa-2x"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-1">{{ $badge->name }}</h6>
                                                <p class="mb-1 text-muted small">{{ $badge->description }}</p>
                                                <small class="text-muted">Earned {{ $badge->pivot->earned_at->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Available Badges -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-lock me-2"></i>Available Badges</h5>
                </div>
                <div class="card-body">
                    @if($availableBadges->isEmpty())
                        <p class="text-muted text-center py-4">
                            <i class="fas fa-check-circle fa-3x mb-3 d-block"></i>
                            Congratulations! You've earned all available badges!
                        </p>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach($availableBadges as $badge)
                                <div class="list-group-item">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <i class="{{ $badge->icon }} fa-lg text-muted"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1">{{ $badge->name }}</h6>
                                            <small class="text-muted">{{ $badge->description }}</small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .achievement-card {
        padding: 1rem;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    .achievement-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .achievement-icon {
        width: 50px;
        height: 50px;
        background-color: #f8f9fa;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
@endpush
@endsection 