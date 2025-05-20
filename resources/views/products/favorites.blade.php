@extends('layouts.master')
@section('title', 'Favorite Products')
@section('content')
<div class="row mt-2">
    <div class="col col-10">
        <h1>Favorite Products</h1>
    </div>
    <div class="col col-2">
        <a href="{{route('products_list')}}" class="btn btn-primary form-control">Back to Products</a>
    </div>
</div>

<div id="favorites-container">
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const favoritesContainer = document.getElementById('favorites-container');
    const products = @json($products);
    
    console.log('Products:', products);
    console.log('LocalStorage:', Object.keys(localStorage));
    const favoriteProducts = products.filter(product => {
        const isFavorite = localStorage.getItem('fav_' + product.id) === 'true';
        console.log(`Product ${product.id} favorite status:`, isFavorite);
        return isFavorite;
    });

    console.log('Favorite products:', favoriteProducts);

    if (favoriteProducts.length === 0) {
        favoritesContainer.innerHTML = `
            <div class="alert alert-info mt-3">
                You haven't added any products to your favorites yet.
            </div>
        `;
        return;
    }
    favoriteProducts.forEach(product => {
        const card = document.createElement('div');
        card.className = 'card mt-2';
        card.innerHTML = `
            <div class="card-body">
                <div class="row">
                    <div class="col col-sm-12 col-lg-4">
                        <img src="/images/${product.photo}" class="img-thumbnail" alt="${product.name}" width="100%">
                    </div>
                    <div class="col col-sm-12 col-lg-8 mt-3">
                        <div class="row mb-2">
                            <div class="col-8">
                                <h3>${product.name}
                                    <button onclick="toggleFavorite(this, ${product.id})" 
                                            class="favorite-btn" 
                                            id="fav-btn-${product.id}">
                                        <i class="far fa-heart" style="color: white;"></i>
                                    </button>
                                </h3>
                            </div>
                        </div>

                        <table class="table table-striped">
                            <tr><th width="20%">Name</th><td>${product.name}</td></tr>
                            <tr><th>Model</th><td>${product.model}</td></tr>
                            <tr><th>Code</th><td>${product.code}</td></tr>
                            <tr><th>Price</th><td>${product.price}</td></tr>
                            <tr><th>Quantity</th><td>${product.quantity} available</td></tr>
                            <tr><th>Description</th><td>${product.description}</td></tr>
                        </table>
                        
                        @can('buy_products')
                        <div class="text-end">
                            ${product.quantity > 0 
                                ? `<a href="/products/buy/${product.id}" class="btn btn-primary">Buy</a>`
                                : `<span class="text-danger">Out of stock</span>`
                            }
                        </div>
                        @endcan
                    </div>
                </div>
            </div>
        `;
        favoritesContainer.appendChild(card);
    });
});

function toggleFavorite(button, productId) {
    const isFavorited = localStorage.getItem('fav_' + productId) === 'true';
    const newState = !isFavorited;
    
    localStorage.setItem('fav_' + productId, newState);
    
    if (!newState) {
        const card = button.closest('.card');
        card.remove();
        

        const favoritesContainer = document.getElementById('favorites-container');
        if (favoritesContainer.children.length === 0) {
            favoritesContainer.innerHTML = `
                <div class="alert alert-info mt-3">
                    You haven't added any products to your favorites yet.
                </div>
            `;
        }
    }
}
</script>
@endpush
@endsection 