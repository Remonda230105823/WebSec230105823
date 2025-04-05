@extends('layouts.master')
@section('title', 'GPA Simulator')
@section('content')
<div class="card m-4">
    <div class="card-header">GPA Simulator</div>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>Course Code</th>
                    <th>Course Title</th>
                    <th>Credit Hours</th>
                    <th>Grade</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($courses as $course)
                <tr>
                    <td>{{ $course['code'] }}</td>
                    <td>{{ $course['title'] }}</td>
                    <td>{{ $course['credit'] }}</td>
                    <td>
                        <select class="form-select grade" data-credit="{{ $course['credit'] }}">
                            <option value="4">A</option>
                            <option value="3.7">A-</option>
                            <option value="3.3">B+</option>
                            <option value="3">B</option>
                            <option value="2.7">B-</option>
                            <option value="2.3">C+</option>
                            <option value="2">C</option>
                            <option value="1.7">C-</option>
                            <option value="1.3">D+</option>
                            <option value="1">D</option>
                            <option value="0">F</option>
                        </select>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <button class="btn btn-primary" onclick="calculateGPA()">Calculate GPA</button>
        <h4 class="mt-3">Your GPA: <span id="gpa-result">0.00</span></h4>
    </div>
</div>

<script>
function calculateGPA() {
    let totalCredits = 0;
    let totalPoints = 0;

    document.querySelectorAll('.grade').forEach(select => {
        let grade = parseFloat(select.value);
        let credit = parseInt(select.dataset.credit);
        totalCredits += credit;
        totalPoints += grade * credit;
    });

    let gpa = totalPoints / totalCredits;
    document.getElementById('gpa-result').innerText = gpa.toFixed(2);
}
</script>
@endsection



