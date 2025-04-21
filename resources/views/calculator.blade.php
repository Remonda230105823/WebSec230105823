@extends('layouts.master')
@section('title', 'Calculator')
@section('content')
<div class="container mt-4">
    <h2>Simple Calculator</h2>
    
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Basic Math Operations</h4>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <input type="number" class="form-control" id="number1" placeholder="0">
                        <label for="number1">First Number</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <input type="number" class="form-control" id="number2" placeholder="0">
                        <label for="number2">Second Number</label>
                    </div>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col d-grid gap-2">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-primary" onclick="calculate('add')">Add (+)</button>
                        <button type="button" class="btn btn-secondary" onclick="calculate('subtract')">Subtract (-)</button>
                        <button type="button" class="btn btn-success" onclick="calculate('multiply')">Multiply (×)</button>
                        <button type="button" class="btn btn-danger" onclick="calculate('divide')">Divide (÷)</button>
                    </div>
                </div>
            </div>
            
            <div class="result-section mt-4">
                <div class="alert alert-info" role="alert">
                    <h5 class="alert-heading">Result:</h5>
                    <p id="result" class="display-6 text-center">0</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function calculate(operation) {
        // Get input values
        const num1 = parseFloat(document.getElementById('number1').value) || 0;
        const num2 = parseFloat(document.getElementById('number2').value) || 0;
        let result = 0;
        
        // Perform the selected operation
        switch(operation) {
            case 'add':
                result = num1 + num2;
                break;
            case 'subtract':
                result = num1 - num2;
                break;
            case 'multiply':
                result = num1 * num2;
                break;
            case 'divide':
                result = num2 !== 0 ? num1 / num2 : 'Cannot divide by zero';
                break;
        }
        
        // Display the result
        document.getElementById('result').innerText = 
            typeof result === 'number' ? result.toLocaleString() : result;
    }
</script>
@endsection 