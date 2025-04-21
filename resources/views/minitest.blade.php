@extends('layouts.master')
@section('title', 'MiniTest')
@section('content')
<div class="container mt-4">
    <h2>Supermarket Bill</h2>
    
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Bill #{{ $billNumber }}</h4>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <p><strong>Date:</strong> {{ $date }}</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p><strong>Customer:</strong> {{ $customer }}</p>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Item</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item['name'] }}</td>
                            <td>{{ $item['quantity'] }}</td>
                            <td>${{ number_format($item['price'], 2) }}</td>
                            <td>${{ number_format($item['quantity'] * $item['price'], 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-secondary">
                        <tr>
                            <td colspan="4" class="text-end"><strong>Subtotal:</strong></td>
                            <td>${{ number_format($subtotal, 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-end"><strong>Tax ({{ $taxRate }}%):</strong></td>
                            <td>${{ number_format($tax, 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-end"><strong>Total:</strong></td>
                            <td class="fw-bold">${{ number_format($total, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="card-footer">
            <p class="mb-0 text-center">Thank you for shopping with us!</p>
        </div>
    </div>
</div>
@endsection 