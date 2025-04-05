@extends('layouts.master')
@section('title', 'Calculator')
@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h4>Simple Calculator</h4>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label for="num1" class="form-label">Number 1:</label>
                <input type="number" id="num1" class="form-control">
            </div>
            <div class="mb-3">
                <label for="num2" class="form-label">Number 2:</label>
                <input type="number" id="num2" class="form-control">
            </div>
            <div class="mb-3">
                <label for="operation" class="form-label">Operation:</label>
                <select id="operation" class="form-select">
                    <option value="add">Addition (+)</option>
                    <option value="subtract">Subtraction (-)</option>
                    <option value="multiply">Multiplication (×)</option>
                    <option value="divide">Division (÷)</option>
                </select>
            </div>
            <button class="btn btn-primary" onclick="calculate()">Calculate</button>
            <h5 class="mt-3">Result: <span id="result"></span></h5>
        </div>
    </div>
</div>

<script>
    function calculate() {
        let num1 = parseFloat(document.getElementById('num1').value);
        let num2 = parseFloat(document.getElementById('num2').value);
        let operation = document.getElementById('operation').value;
        let result;

        if (isNaN(num1) || isNaN(num2)) {
            alert("Please enter valid numbers");
            return;
        }

        switch (operation) {
            case "add":
                result = num1 + num2;
                break;
            case "subtract":
                result = num1 - num2;
                break;
            case "multiply":
                result = num1 * num2;
                break;
            case "divide":
                if (num2 === 0) {
                    alert("Cannot divide by zero!");
                    return;
                }
                result = num1 / num2;
                break;
        }

        document.getElementById('result').innerText = result;
    }
</script>
@endsection