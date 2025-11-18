<x-layouts.dashboard_layout>

    <x-slot name="title">
        My Profile
    </x-slot>

    <h2 class="h4 mb-4">Account Management & Stats</h2>

    <div class="row">
        <div class="col-lg-7 mb-4"> 
            <div class="card recent-posts-card h-100">
                <div class="card-header bg-white profile-card-header border-bottom py-3">
                    <h5 class="mb-0">Personal Information</h5>
                </div>
                <div class="card-body d-flex flex-column">
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="d-flex flex-column flex-grow-1">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="form-label d-block">Current Avatar</label>
                            <div class="d-flex align-items-center">
                                <img src="{{ $user->avatar_path ? asset('storage/' . $user->avatar_path) : asset('images/undraw_developer-avatar_f6ac.svg') }}" 
                                    alt="User Avatar" class="rounded-circle me-4 avatar-preview-lg">
                                <div>
                                    <div class="d-flex align-items-center">
                                        <label class="custom-file-input-wrapper me-3">
                                            <i class="bi bi-upload me-1"></i> Upload New Avatar
                                            <input type="file" name="avatar" id="avatarInput" placeholder="Choose a file" accept="image/png, image/jpeg">
                                        </label>
                                    </div>
                                    <p class="text-muted small mt-2 mb-0">PNG or JPG (Max 2MB)</p>
                                    <span id="avatarFileName" class="text-success small fw-bold mt-1"></span> 
                                    @if ($user->avatar_path)
                                        <div class="form-check form-check-inline mt-2">
                                            <input class="form-check-input" type="checkbox" name="remove_avatar" value="1" id="removeAvatar">
                                            <label class="form-check-label small" for="removeAvatar">Remove Current Avatar</label>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" placeholder="Your Full Name" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" placeholder="your.email@example.com" required>
                            <p class="text-muted small mt-1">Email is used for logging in and verification.</p>
                        </div>

                        <div class="d-flex justify-content-end mt-auto"> 
                            <button type="submit" class="btn btn-primary">Update Profile</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-5 mb-4">
            <div class="card recent-posts-card h-100">
                <div class="card-header bg-white profile-card-header border-bottom py-3">
                    <h5 class="mb-0">Change Password</h5>
                </div>
                <div class="card-body d-flex flex-column">
                    <form action="{{ route('profile.update') }}" method="POST" class="d-flex flex-column flex-grow-1">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="current_password" class="form-label">Current Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="current_password" name="current_password" placeholder="Enter current password" required>
                                <span class="input-group-text password-toggle-icon">
                                    <i class="bi bi-eye-slash"></i>
                                </span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Enter new password" required>
                                <span class="input-group-text password-toggle-icon">
                                    <i class="bi bi-eye-slash"></i>
                                </span>
                            </div>
                            <p class="text-muted small mt-1">Minimum 8 characters.</p>
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm New Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm new password" required>
                                <span class="input-group-text password-toggle-icon">
                                    <i class="bi bi-eye-slash"></i>
                                </span>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-auto">
                            <button type="submit" class="btn btn-primary">Change Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="profile-stat-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon stat-icon-total me-3">
                            <i class="bi bi-file-earmark-text-fill"></i>
                        </div>
                        <div>
                            <div class="stat-number">{{ $totalPosts ?? 0 }}</div>
                            <div class="stat-label">Total Posts Created</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="profile-stat-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon stat-icon-published me-3">
                            <i class="bi bi-chat-quote-fill"></i>
                        </div>
                        <div>
                            <div class="stat-number">{{ $totalComments ?? 0 }}</div>
                            <div class="stat-label">Total Comments (on your posts)</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="profile-stat-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon stat-icon-drafts me-3">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <div>
                            <div class="stat-number">{{ $pendingComments ?? 0 }}</div>
                            <div class="stat-label">Pending Comments (on your posts)</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-7 mb-4">
            <div class="card recent-posts-card h-100">
                <div class="card-header bg-white profile-card-header border-bottom-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Your Post Activity</h5>
                        <form action="{{ route('profile.edit') }}" method="GET" class="d-flex align-items-center mb-0">
                            <label for="yearFilterInput" class="form-label mb-0 me-2 small text-muted">Year:</label>
                            <input type="number" 
                                   class="form-control form-control-sm" 
                                   id="yearFilterInput"
                                   name="year" 
                                   value="{{ $selectedYear }}" 
                                   style="width: 100px;"
                                   min="2020" 
                                   max="{{ date('Y') + 1 }}"
                                   placeholder="Year">
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="postsChart" style="max-height: 300px;" 
                            data-chart-data="{{ $postChartDataJson ?? '[]' }}">
                    </canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-5 mb-4">
            <div class="card recent-posts-card h-100">
                <div class="card-header bg-white profile-card-header border-bottom-0 py-3">
                    <h5 class="mb-0">Comment Status ({{ $selectedYear }})</h5>
                </div>
                <div class="card-body d-flex justify-content-center align-items-center">
                    <canvas id="commentsChart" style="max-height: 280px;"
                            data-chart-data="{{ $commentStatsJson ?? '[]' }}">
                    </canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 mb-4">
            <div class="card recent-posts-card border-danger">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 text-danger">Delete Account</h5>
                </div>
                <div class="card-body">
                    <p>Once your account is deleted, all of its resources and data will be permanently erased. Please confirm you wish to permanently delete your account.</p>
                    <form action="{{ route('profile.destroy') }}" method="POST" onsubmit="return confirm('WARNING: Are you sure you want to permanently delete your account and ALL associated posts?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete Account</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</x-layouts.dashboard_layout>