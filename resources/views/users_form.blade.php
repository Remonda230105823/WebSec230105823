@extends('layouts.master')
@section('title', $action == 'create' ? 'Add New User' : 'Edit User')
@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">{{ $action == 'create' ? 'Add New User' : 'Edit User' }}</h4>
                </div>
                <div class="card-body">
                    <form id="userForm" method="POST" action="{{ $action == 'create' ? route('users_list') : route('users_list') }}">
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $user ? $user['name'] : '' }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ $user ? $user['email'] : '' }}" required>
                        </div>
                        
                        @if($action == 'create')
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>
                        @endif
                        
                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="">Select Role</option>
                                <option value="Admin" {{ ($user && $user['role'] == 'Admin') ? 'selected' : '' }}>Admin</option>
                                <option value="Manager" {{ ($user && $user['role'] == 'Manager') ? 'selected' : '' }}>Manager</option>
                                <option value="User" {{ ($user && $user['role'] == 'User') ? 'selected' : '' }}>User</option>
                            </select>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('users_list') }}" class="btn btn-secondary me-md-2">Cancel</a>
                            <button type="submit" class="btn btn-primary" id="saveButton">Save User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const userForm = document.getElementById('userForm');
    
    userForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // In a real application, we would do validation here
        // Since this is a demo with static routes, we'll simulate success
        
        // Show success message using SweetAlert or alert
        alert('User {{ $action == "create" ? "created" : "updated" }} successfully!');
        
        // Redirect back to users list
        window.location.href = "{{ route('users_list') }}";
    });
    
    @if($action == 'create')
    // Password confirmation validation
    const password = document.getElementById('password');
    const passwordConfirm = document.getElementById('password_confirmation');
    
    passwordConfirm.addEventListener('input', function() {
        if (password.value !== passwordConfirm.value) {
            passwordConfirm.setCustomValidity("Passwords do not match");
        } else {
            passwordConfirm.setCustomValidity("");
        }
    });
    
    password.addEventListener('input', function() {
        if (passwordConfirm.value !== '') {
            if (password.value !== passwordConfirm.value) {
                passwordConfirm.setCustomValidity("Passwords do not match");
            } else {
                passwordConfirm.setCustomValidity("");
            }
        }
    });
    @endif
});
</script>
@endsection 