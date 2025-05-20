@extends('layouts.master')
@section('title', 'Test Page')
@section('content')
<div class="row mt-2">
    <div class="col col-10">
        <h1>Products</h1>
    </div>
    <div class="col col-2">
    @can('add_products')
        <a href="{{route('products_edit')}}" class="btn btn-success form-control">Add Product</a>
        @endcan
    </div>
</div>
<form>
    <div class="row">
        <div class="col col-sm-2">
            <input name="keywords" type="text"  class="form-control" placeholder="Search Keywords" value="{{ request()->keywords }}" />
        </div>
        <div class="col col-sm-2">
            <input name="min_price" type="numeric"  class="form-control" placeholder="Min Price" value="{{ request()->min_price }}"/>
        </div>
        <div class="col col-sm-2">
            <input name="max_price" type="numeric"  class="form-control" placeholder="Max Price" value="{{ request()->max_price }}"/>
        </div>
        <div class="col col-sm-2">
            <select name="order_by" class="form-select">
                <option value="" {{ request()->order_by==""?"selected":"" }} disabled>Order By</option>
                <option value="name" {{ request()->order_by=="name"?"selected":"" }}>Name</option>
                <option value="price" {{ request()->order_by=="price"?"selected":"" }}>Price</option>
            </select>
        </div>
        <div class="col col-sm-2">
            <select name="order_direction" class="form-select">
                <option value="" {{ request()->order_direction==""?"selected":"" }} disabled>Order Direction</option>
                <option value="ASC" {{ request()->order_direction=="ASC"?"selected":"" }}>ASC</option>
                <option value="DESC" {{ request()->order_direction=="DESC"?"selected":"" }}>DESC</option>
            </select>
        </div>
        <div class="col col-sm-1">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
        <div class="col col-sm-1">
            <button type="reset" class="btn btn-danger">Reset</button>
        </div>
    </div>
</form>


@foreach($products as $product)
    <div class="card mt-2">
        <div class="card-body">
            <div class="row">
                <div class="col col-sm-12 col-lg-4">
                    <img src="{{asset("images/$product->photo")}}" class="img-thumbnail" alt="{{$product->name}}" width="100%">
                </div>
                <div class="col col-sm-12 col-lg-8 mt-3">
                    <div class="row mb-2">
					    <div class="col-8">
					        <h3>{{$product->name}} 
                            <button onclick="toggleFavorite(this, {{$product->id}})" 
                                    class="btn btn-outline-warning btn-sm favorite-btn" 
                                    id="fav-btn-{{$product->id}}">
                                <i class="fas fa-heart"></i> <span>Favorite</span>
                            </button>
                        </h3>
					    </div>
					    <div class="col col-2">
                            @can('edit_products')
					        <a href="{{route('products_edit', $product->id)}}" class="btn btn-success form-control">Edit</a>
                            @endcan
					    </div>
					    <div class="col col-2">
                            @can('delete_products')
					        <a href="{{route('products_delete', $product->id)}}" class="btn btn-danger form-control">Delete</a>
                            @endcan
					    </div>
					</div>

                    <table class="table table-striped">
                        <tr><th width="20%">Name</th><td>{{$product->name}}</td></tr>
                        <tr><th>Model</th><td>{{$product->model}}</td></tr>
                        <tr><th>Code</th><td>{{$product->code}}</td></tr>
                        <tr><th>Price</th><td>{{$product->price}}</td>
                        <tr><th>Quantity</th><td>{{$product->quantity}} available</td>
                        <tr><th>Description</th><td>{{$product->description}}</td></tr>
                    </table>
                    
                    @can('buy_products')
                    <div class="text-end">
                        @if($product->quantity > 0)
                            @if(auth()->user()->credits >= $product->price)
                                <a href="{{route('buy_product', $product->id)}}" class="btn btn-primary">Buy</a>
                            @else
                                <span class="text-danger">Insufficient credit to purchase</span>
                            @endif
                        @else
                            <span class="text-danger">Out of stock</span>
                        @endif
                    </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>
@endforeach

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.favorite-btn').forEach(button => {
        const productId = button.id.split('-')[2];
        const isFavorited = localStorage.getItem('fav_' + productId) === 'true';
        updateButtonState(button, isFavorited);
    });
});

function toggleFavorite(button, productId) {
    const isFavorited = localStorage.getItem('fav_' + productId) === 'true';
    const newState = !isFavorited;
    
    localStorage.setItem('fav_' + productId, newState);
    updateButtonState(button, newState);
}

function updateButtonState(button, isFavorited) {
    const span = button.querySelector('span');
    if (isFavorited) {
        button.classList.remove('btn-outline-warning');
        button.classList.add('btn-warning');
        span.textContent = 'Unfavorite';
    } else {
        button.classList.remove('btn-warning');
        button.classList.add('btn-outline-warning');
        span.textContent = 'Favorite';
    }
}
</script>
@endpush

@can('buy_products')
<div data-can-buy-products style="display: none;"></div>
@endcan

@endsection