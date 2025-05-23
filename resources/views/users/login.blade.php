@extends('layouts.master')
@section('title', 'Login')
@section('content')
<div class="d-flex justify-content-center">
  <div class="card m-4 col-sm-6">
    <div class="card-body">
      <form action="{{route('do_login')}}" method="post">
      {{ csrf_field() }}
      <div class="form-group">
        @foreach($errors->all() as $error)
        <div class="alert alert-danger">
          <strong>Error!</strong> {{$error}}
        </div>
        @endforeach
      </div>
      <div class="form-group mb-2">
        <label for="model" class="form-label">Email:</label>
        <input type="email" class="form-control" placeholder="email" name="email" required>
      </div>
      <div class="form-group mb-2">
        <label for="model" class="form-label">Password:</label>
        <input type="password" class="form-control" placeholder="password" name="password" required>
      </div>
      <div class="form-group mb-2">
        <button type="submit" class="btn btn-primary">Login</button>
      </div>
    </form>
    <hr>
    <div class="text-center mt-3">
        <p>Or login with:</p>
        <a href="{{ url('auth/facebook') }}" class="btn btn-outline-primary m-1">Facebook</a>
        <a href="{{ url('auth/microsoft') }}" class="btn btn-outline-secondary m-1">Microsoft</a>
        <a href="{{ url('auth/linkedin') }}" class="btn btn-outline-info m-1">LinkedIn</a>
    </div>
    </div>
  </div>
</div>
@endsection
