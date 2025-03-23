@extends('layouts.master')
@section('title', 'Products')
@section('content')
<div class="container mt-4">
    <h2 class="text-center">Product Catalog</h2>
    <div class="row">
        @foreach($products as $product)
        <div class="col-md-4">
            <div class="card mb-4">
            <img src="{{ $product['image'] }}" class="card-img-top" alt="{{ $product['name'] }}">
                    <h5 class="card-title">{{ $product['name'] }}</h5>
                    <p class="card-text">{{ $product['description'] }}</p>
                    <h6 class="text-primary">{{ $product['price'] }}</h6>
                    <a href="#" class="btn btn-success">Add to Cart</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
