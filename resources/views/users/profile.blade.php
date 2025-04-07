@extends('layouts.master')
@section('title', 'User Profile')
@section('content')
<div class="row mt-2">
    <div class="col col-10">
        <h1>{{$user->name}} Profile</h1>
    </div>
    <div class="col col-2">
        <a href="{{route('users_edit', $user->id)}}" class="btn btn-success form-control">Edit</a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-striped">
            <tr>
                <th width="20%">Name</th>
                <td>{{$user->name}}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>{{$user->email}}</td>
            </tr>
            <tr>
                <th>Credits</th>
                <td>{{$user->credits}}</td>
            </tr>
            <tr>
                <th>Roles</th>
                <td>
                    @foreach($user->roles as $role)
                        <span class="badge bg-primary">{{$role->name}}</span>
                    @endforeach
                </td>
            </tr>
            <tr>
                <th>Permissions</th>
                <td>
                    @foreach($permissions as $permission)
                        <span class="badge bg-success">{{$permission->display_name}}</span>
                    @endforeach
                </td>
            </tr>
        </table>
    </div>
</div>

<!-- Credit Charging Section for Employees -->
@can('charge_credit')
@if($user->hasRole('Customer'))
<div class="card mt-3">
    <div class="card-header bg-info text-white">
        <h5>Charge Customer Credit</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('charge_credit', $user->id) }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" name="amount" class="form-control" placeholder="Amount to add" step="0.01" min="0.01" required>
                    </div>
                    <small class="text-muted">Only positive values are allowed</small>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100">Add Credit</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif
@endcan

<div class="row">
    <div class="m-4 col-sm-6">
        @if(auth()->user()->hasPermissionTo('admin_users')||auth()->id()==$user->id)
        <div class="col col-4">
            <a class="btn btn-primary" href='{{route('edit_password', $user->id)}}'>Change Password</a>
        </div>
        @else
        <div class="col col-4">
        </div>
        @endif
    </div>
</div>
@endsection
