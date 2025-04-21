@extends('layouts.master')
@section('title', 'Grades Management')
@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Grades Management</h2>
        <a href="{{ route('grades_new') }}" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Add New Grade
        </a>
    </div>
    
    @if(session('message'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('message') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    
    <!-- Cumulative GPA Summary -->
    <div class="card mb-4 bg-light">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="d-flex align-items-center">
                        <div class="display-6 me-3">{{ $cumulativeGPA }}</div>
                        <div>
                            <h5 class="mb-0">Cumulative GPA</h5>
                            <small class="text-muted">Out of 4.0 scale</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex align-items-center">
                        <div class="display-6 me-3">{{ $cumulativeCredits }}</div>
                        <div>
                            <h5 class="mb-0">Total Credit Hours</h5>
                            <small class="text-muted">Completed across all terms</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @foreach($terms as $termName => $courses)
    <div class="card mb-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">{{ $termName }}</h4>
            <div>
                <span class="badge bg-light text-dark me-2">GPA: {{ $termStats[$termName]['gpa'] }}</span>
                <span class="badge bg-light text-dark">Credits: {{ $termStats[$termName]['credits'] }}</span>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Course Code</th>
                            <th>Course Name</th>
                            <th>Credits</th>
                            <th>Grade</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($courses as $course)
                        <tr>
                            <td>{{ $course['course_code'] }}</td>
                            <td>{{ $course['course_name'] }}</td>
                            <td>{{ $course['credits'] }}</td>
                            <td>
                                <span class="badge bg-{{ 
                                    in_array($course['grade'], ['A', 'A-']) ? 'success' : 
                                    (in_array($course['grade'], ['B+', 'B', 'B-']) ? 'primary' : 
                                    (in_array($course['grade'], ['C+', 'C', 'C-']) ? 'warning' : 'danger')) 
                                }}">
                                    {{ $course['grade'] }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('grades_edit', $course['id']) }}" class="btn btn-sm btn-primary">Edit</a>
                                    <a href="{{ route('grades_delete', $course['id']) }}" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this grade?')">Delete</a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endforeach
    
    <!-- GPA Calculation Information -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0">GPA Calculation Information</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6>Grade Points Scale</h6>
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Letter Grade</th>
                                <th>Points</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td>A</td><td>4.0</td></tr>
                            <tr><td>A-</td><td>3.7</td></tr>
                            <tr><td>B+</td><td>3.3</td></tr>
                            <tr><td>B</td><td>3.0</td></tr>
                            <tr><td>B-</td><td>2.7</td></tr>
                            <tr><td>C+</td><td>2.3</td></tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6>GPA Calculation Method</h6>
                    <p>GPA is calculated by dividing the total grade points earned by the total credit hours attempted.</p>
                    <p><strong>Formula: </strong> GPA = Total Grade Points / Total Credit Hours</p>
                    <p><small>For example, an A (4.0) in a 3-credit course earns 12 grade points.</small></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 