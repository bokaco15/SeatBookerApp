@extends('layouts.app')

@section('content')

    <div class="container mt-4">
        <h2 class="mb-4">Profile Settings</h2>

        {{-- SUCCESS MESSAGE --}}
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <div class="row">

            {{-- UPDATE PROFILE --}}
            <div class="col-md-6">
                <div class="card shadow mb-4">
                    <div class="card-header">Update Profile Information</div>
                    <div class="card-body">

                        <form method="POST" action="{{ route('admin.profile.update') }}">
                            @csrf
                            @method('PATCH')

                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text"
                                       name="name"
                                       value="{{ old('name', auth()->user()->name) }}"
                                       class="form-control"
                                       required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email"
                                       name="email"
                                       value="{{ old('email', auth()->user()->email) }}"
                                       class="form-control"
                                       required>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                Save Changes
                            </button>

                        </form>

                    </div>
                </div>
            </div>


            {{-- UPDATE PASSWORD --}}
            <div class="col-md-6">
                <div class="card shadow mb-4">
                    <div class="card-header">Update Password</div>
                    <div class="card-body">

                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label">Current Password</label>
                                <input type="password"
                                       name="current_password"
                                       class="form-control"
                                       required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">New Password</label>
                                <input type="password"
                                       name="password"
                                       class="form-control"
                                       required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Confirm Password</label>
                                <input type="password"
                                       name="password_confirmation"
                                       class="form-control"
                                       required>
                            </div>

                            <button type="submit" class="btn btn-warning">
                                Change Password
                            </button>

                        </form>

                    </div>
                </div>
            </div>

        </div>


        {{-- DELETE ACCOUNT --}}
        <div class="card shadow mt-4 border-danger">
            <div class="card-header bg-danger text-white">
                Delete Account
            </div>
            <div class="card-body">

                <form method="POST" action="{{ route('admin.profile.destroy') }}">
                    @csrf
                    @method('DELETE')

                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password"
                               name="password"
                               class="form-control"
                               required>
                    </div>

                    <button type="submit" class="btn btn-danger">
                        Delete My Account
                    </button>
                </form>

            </div>
        </div>

    </div>

@endsection
