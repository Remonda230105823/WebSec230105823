@extends('layouts.master')
@section('title', 'Student Transcript')
@section('content')
<div class="container mt-4">
    <h2>Student Transcript</h2>
    
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">{{ $student['name'] }} - {{ $student['id'] }}</h4>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <p><strong>Program:</strong> {{ $student['program'] }}</p>
                    <p><strong>Major:</strong> {{ $student['major'] }}</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p><strong>Year:</strong> {{ $student['year'] }}</p>
                    <p><strong>Status:</strong> <span class="badge bg-success">{{ $student['status'] }}</span></p>
                </div>
            </div>
            
            <h5 class="mb-3">Course History</h5>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Course Code</th>
                            <th>Course Name</th>
                            <th>Credits</th>
                            <th>Grade</th>
                            <th>Semester</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($courses as $course)
                        <tr>
                            <td>{{ $course['code'] }}</td>
                            <td>{{ $course['name'] }}</td>
                            <td>{{ $course['credits'] }}</td>
                            <td>
                                <span class="badge {{ $course['grade'] >= 90 ? 'bg-success' : ($course['grade'] >= 70 ? 'bg-primary' : ($course['grade'] >= 60 ? 'bg-warning' : 'bg-danger')) }}">
                                    {{ $course['grade'] }}
                                </span>
                            </td>
                            <td>{{ $course['semester'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-secondary">
                        <tr>
                            <td colspan="2" class="text-end"><strong>Total Credits Earned:</strong></td>
                            <td>{{ $totalCredits }}</td>
                            <td colspan="2"></td>
                        </tr>
                        <tr>
                            <td colspan="2" class="text-end"><strong>GPA:</strong></td>
                            <td colspan="3">{{ number_format($gpa, 2) }} / 4.0</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-0"><strong>Advisor:</strong> {{ $student['advisor'] }}</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0"><small>Transcript generated on {{ date('F d, Y') }}</small></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 