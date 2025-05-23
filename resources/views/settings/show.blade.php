@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-3">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">Settings</h5>
                    <div class="nav flex-column nav-pills">
                        <a class="nav-link active" href="#password" data-bs-toggle="pill">
                            <i class="fas fa-key me-2"></i>Password
                        </a>
                        <a class="nav-link" href="#notifications" data-bs-toggle="pill">
                            <i class="fas fa-bell me-2"></i>Notifications
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="tab-content">
                <!-- Password Change -->
                <div class="tab-pane fade show active" id="password">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Change Password</h4>

                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <form action="{{ route('settings.password.update') }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label for="current_password" class="form-label">Current Password</label>
                                    <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                        id="current_password" name="current_password">
                                    @error('current_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">New Password</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                        id="password" name="password">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                    <input type="password" class="form-control" 
                                        id="password_confirmation" name="password_confirmation">
                                </div>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Update Password
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Notification Settings -->
                <div class="tab-pane fade" id="notifications">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Notification Preferences</h4>

                            <form action="{{ route('settings.notifications.update') }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" 
                                            id="email_notifications" name="email_notifications" value="1"
                                            {{ $user->settings?->email_notifications ? 'checked' : '' }}>
                                        <label class="form-check-label" for="email_notifications">
                                            Email Notifications
                                        </label>
                                    </div>
                                    <small class="text-muted">Receive notifications about donation requests and updates via email.</small>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" 
                                            id="sms_notifications" name="sms_notifications" value="1"
                                            {{ $user->settings?->sms_notifications ? 'checked' : '' }}>
                                        <label class="form-check-label" for="sms_notifications">
                                            SMS Notifications
                                        </label>
                                    </div>
                                    <small class="text-muted">Receive urgent notifications via SMS (requires verified phone number).</small>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" 
                                            id="emergency_alerts" name="emergency_alerts" value="1"
                                            {{ $user->settings?->emergency_alerts ? 'checked' : '' }}>
                                        <label class="form-check-label" for="emergency_alerts">
                                            Emergency Alerts
                                        </label>
                                    </div>
                                    <small class="text-muted">Receive immediate alerts for urgent blood donation requests in your area.</small>
                                </div>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Save Preferences
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 