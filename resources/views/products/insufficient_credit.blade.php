@extends('layouts.master')
@section('title', 'Insufficient Credit')
@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h3>Insufficient Credit</h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <h4>You don't have enough credit to purchase this product</h4>
                        <p>Your current credit: <strong>${{ number_format($user->credits, 2) }}</strong></p>
                        <p>Product price: <strong>${{ number_format($product->price, 2) }}</strong></p>
                        <p>Missing amount: <strong>${{ number_format($missing, 2) }}</strong></p>
                    </div>
                    
                    <div class="text-center mt-4">
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Product Details</h5>
                                <table class="table">
                                    <tr>
                                        <th>Name:</th>
                                        <td>{{ $product->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Model:</th>
                                        <td>{{ $product->model }}</td>
                                    </tr>
                                    <tr>
                                        <th>Price:</th>
                                        <td>${{ number_format($product->price, 2) }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <img src="{{ asset("images/$product->photo") }}" alt="{{ $product->name }}" class="img-fluid rounded" style="max-height: 150px;">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <div class="d-grid gap-2">
                            <a href="{{ route('products_list') }}" class="btn btn-secondary">Return to Products</a>
                            <a href="{{ route('profile') }}" class="btn btn-primary">Go to Profile to Add Credit</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 