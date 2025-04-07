@extends('layouts.master')
@section('title', 'My Purchases')
@section('content')

<div class="row mt-2">
    <div class="col col-10">
        <h1>My Purchases</h1>
    </div>
    <div class="col col-2">
        <a href="{{route('products_list')}}" class="btn btn-primary form-control">Back to Products</a>
    </div>
</div>

<div class="table-responsive mt-3">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($purchases as $purchase)
            <tr>
                <td>{{ $purchase->id }}</td>
                <td>{{ $purchase->product->name }}</td>
                <td>{{ $purchase->price }}</td>
                <td>{{ $purchase->quantity }}</td>
                <td>{{ $purchase->created_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@if(count($purchases) == 0)
    <div class="alert alert-info">
        You haven't made any purchases yet.
    </div>
@endif

@endsection 