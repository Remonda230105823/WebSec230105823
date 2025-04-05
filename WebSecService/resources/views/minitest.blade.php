@extends('layouts.master')
@section('title', 'MiniTest')
@section('content')
<div class="card m-4">
    <div class="card-header">Supermarket Bill</div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bill as $item)
                <tr>
                    <td>{{ $item['item'] }}</td>
                    <td><span class="badge bg-info">{{ $item['quantity'] }}</span></td>
                    <td>${{ number_format($item['price'], 2) }}</td>
                    <td><span class="badge bg-success">${{ number_format($item['quantity'] * $item['price'], 2) }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

