@extends('layouts.master')
@section('title', 'Customer List')
@section('content')

<div class="row mt-2">
    <div class="col col-10">
        <h1>Customers</h1>
    </div>
</div>

<form>
    <div class="row">
        <div class="col col-sm-10">
            <input name="keywords" type="text" class="form-control" placeholder="Search Keywords" value="{{ request()->keywords }}" />
        </div>
        <div class="col col-sm-1">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
        <div class="col col-sm-1">
            <button type="reset" class="btn btn-danger">Reset</button>
        </div>
        <div class="col col-sm-1">
            <button type="reset" class="btn btn-primary">GIFT</button>
        </div>
    </div>
</form>

<div class="table-responsive mt-3">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Credits</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->credits }}</td>
                <td>
                    <a href="{{ route('profile', $user->id) }}" class="btn btn-sm btn-info">Profile</a>
                    @can('charge_credit')
                    <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#chargeModal{{ $user->id }}">
                        Charge Credit
                    </button>
                    @endcan
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@foreach($users as $user)
<!-- Charge Credit Modal -->
<div class="modal fade" id="chargeModal{{ $user->id }}" tabindex="-1" aria-labelledby="chargeModalLabel{{ $user->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="chargeModalLabel{{ $user->id }}">Charge Credit for {{ $user->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('charge_credit', $user->id) }}" method="post">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount</label>
                        <input type="number" step="0.01" min="0" class="form-control" id="amount" name="amount" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Charge</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@endsection 