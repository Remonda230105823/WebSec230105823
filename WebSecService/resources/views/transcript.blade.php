@extends('layouts.master')
@section('title', 'Student Transcript')
@section('content')
<div class="container mt-4">
    <h2 class="text-center">Student Transcript</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Course</th>
                <th>Grade</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transcript as $record)
            <tr>
                <td>{{ $record['course'] }}</td>
                <td>{{ $record['grade'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
