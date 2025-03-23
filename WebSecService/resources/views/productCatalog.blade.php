@extends('layouts.master')
@section('title', 'Product Catalog')
@section('content')
    <div class="container mt-4">
        <h2 class="mb-4">Product Catalog</h2>

        @foreach($products as $product)
            <div class="card mb-3">
                <div class="row g-0">
                    <div class="col-md-4">
                        <img src="{{ $product['image'] }}" class="img-fluid rounded-start" alt="{{ $product['name'] }}">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title">{{ $product['name'] }}</h5>
                            <p class="card-text"><strong>Price:</strong> ${{ $product['price'] }}</p>
                            <p class="card-text"><strong>Description:</strong> {{ $product['description'] }}</p>
                            <a href="#" class="btn btn-primary">Add to Cart</a>
                        </div>
                    </div>
                </div>
            </div>
    </div>
@endsection
