@extends('layouts.master')
@section('title', 'Users List')
@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Users Management</h2>
        <a href="{{ route('users_new') }}" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Add New User
        </a>
    </div>
    
    @if(session('message'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('message') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    
    <!-- Filter Section -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Filter Users</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="nameFilter" class="form-label">Name</label>
                    <input type="text" class="form-control" id="nameFilter" placeholder="Filter by name">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="emailFilter" class="form-label">Email</label>
                    <input type="text" class="form-control" id="emailFilter" placeholder="Filter by email">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="roleFilter" class="form-label">Role</label>
                    <select class="form-select" id="roleFilter">
                        <option value="">All Roles</option>
                        <option value="Admin">Admin</option>
                        <option value="Manager">Manager</option>
                        <option value="User">User</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Users Table -->
    <div class="card">
        <div class="card-header bg-light">
            <h5 class="mb-0">Users List</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="usersTableBody">
                        @foreach($users as $user)
                        <tr>
                            <td>{{ $user['id'] }}</td>
                            <td>{{ $user['name'] }}</td>
                            <td>{{ $user['email'] }}</td>
                            <td><span class="badge bg-{{ $user['role'] == 'Admin' ? 'danger' : ($user['role'] == 'Manager' ? 'warning' : 'info') }}">{{ $user['role'] }}</span></td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('users_edit', $user['id']) }}" class="btn btn-sm btn-primary">Edit</a>
                                    <a href="{{ route('users_delete', $user['id']) }}" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameFilter = document.getElementById('nameFilter');
    const emailFilter = document.getElementById('emailFilter');
    const roleFilter = document.getElementById('roleFilter');
    
    // Filter function
    function filterUsers() {
        const nameValue = nameFilter.value.toLowerCase();
        const emailValue = emailFilter.value.toLowerCase();
        const roleValue = roleFilter.value;
        
        const rows = document.querySelectorAll('#usersTableBody tr');
        
        rows.forEach(row => {
            const name = row.cells[1].textContent.toLowerCase();
            const email = row.cells[2].textContent.toLowerCase();
            const role = row.cells[3].textContent.trim();
            
            const nameMatch = name.includes(nameValue);
            const emailMatch = email.includes(emailValue);
            const roleMatch = roleValue === '' || role === roleValue;
            
            if (nameMatch && emailMatch && roleMatch) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
    
    // Add event listeners
    nameFilter.addEventListener('input', filterUsers);
    emailFilter.addEventListener('input', filterUsers);
    roleFilter.addEventListener('change', filterUsers);
});
</script>
@endsection 