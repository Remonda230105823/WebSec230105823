@extends('layouts.master')
@section('title', 'Product Catalog')
@section('content')
<div class="container mt-4">
    <h2>Product Catalog</h2>
    
    <div class="row">
        @foreach($products as $product)
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="product-image-container" style="height: 200px; overflow: hidden;">
                    @php
                    // Replace placeholder images with real photos based on product name
                    $imagePath = '';
                    switch($product['name']) {
                        case 'Smartphone Pro':
                            $imagePath = 'https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.0.3';
                            break;
                        case 'Wireless Earbuds':
                            $imagePath = 'https://images.unsplash.com/photo-1605464315542-bda3e2f4e605?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.0.3';
                            break;
                        case 'Smart Watch':
                            $imagePath = 'https://images.unsplash.com/photo-1544117519-31a4a39696a8?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.0.3';
                            break;
                        case 'Laptop Ultra':
                            $imagePath = 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.0.3';
                            break;
                        case 'Bluetooth Speaker':
                            $imagePath = 'https://images.unsplash.com/photo-1608043152269-423dbba4e7e1?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.0.3';
                            break;
                        case 'Tablet Pro':
                            $imagePath = 'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.0.3';
                            break;
                        default:
                            $imagePath = $product['image']; // Fallback to original image if no match
                    }
                    @endphp
                    <img src="{{ $imagePath }}" class="card-img-top" alt="{{ $product['name'] }}" style="object-fit: cover; width: 100%; height: 100%;">
                </div>
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ $product['name'] }}</h5>
                    <p class="card-text text-primary fw-bold">${{ number_format($product['price'], 2) }}</p>
                    <p class="card-text">{{ $product['description'] }}</p>
                    <button class="btn btn-primary mt-auto">Add to Cart</button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection 