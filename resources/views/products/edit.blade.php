@extends('layouts.master')
@section('title', 'Edit Product')
@section('content')

<div class="container mt-4">
    <h2>{{ $product->id ? 'Edit' : 'Add New' }} Product</h2>

    <form action="{{route('products_save', $product->id)}}" method="post" enctype="multipart/form-data">
        @csrf
        @foreach($errors->all() as $error)
        <div class="alert alert-danger">
        <strong>Error!</strong> {{$error}}
        </div>
        @endforeach
        <div class="row mb-2">
            <div class="col-6">
                <label for="code" class="form-label">Code:</label>
                <input type="text" class="form-control" placeholder="Code" name="code" required value="{{$product->code}}">
            </div>
            <div class="col-6">
                <label for="model" class="form-label">Model:</label>
                <input type="text" class="form-control" placeholder="Model" name="model" required value="{{$product->model}}">
            </div>
        </div>
        <div class="row mb-2">
            <div class="col">
                <label for="name" class="form-label">Name:</label>
                <input type="text" class="form-control" placeholder="Name" name="name" required value="{{$product->name}}">
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-6">
                <label for="price" class="form-label">Price:</label>
                <input type="numeric" class="form-control" placeholder="Price" name="price" required value="{{$product->price}}">
            </div>
            <div class="col-6">
                <label for="quantity" class="form-label">Quantity:</label>
                <input type="number" class="form-control" placeholder="Quantity" name="quantity" required value="{{$product->quantity}}">
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-12">
                <label for="photo" class="form-label">Product Photo:</label>
                <input type="file" class="form-control" name="photo_file" accept="image/*">
                <input type="hidden" name="current_photo" value="{{$product->photo}}">
                @if($product->photo)
                <div class="mt-2">
                    <label>Current Photo:</label>
                    <img src="{{asset('images/' . $product->photo)}}" alt="Product Photo" class="img-thumbnail" style="max-height: 150px">
                </div>
                @endif
            </div>
        </div>
        <div class="row mb-2">
            <div class="col">
                <label for="description" class="form-label">Description:</label>
                <textarea class="form-control" placeholder="Description" name="description" required rows="4">{{$product->description}}</textarea>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Save Product</button>
        <a href="{{route('products_list')}}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
