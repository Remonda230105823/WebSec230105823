@extends('layouts.master')
@section('title', 'MCQ Exam Management')
@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>MCQ Exam Management</h2>
        <div>
            <a href="{{ route('mcq_start') }}" class="btn btn-primary me-2">
                <i class="bi bi-play-circle"></i> Start Exam
            </a>
            <a href="{{ route('mcq_new') }}" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Add New Question
            </a>
        </div>
    </div>
    
    @if(session('message'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('message') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    
    <div class="card">
        <div class="card-header bg-light">
            <h5 class="mb-0">Questions List</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th width="5%">#</th>
                            <th width="45%">Question</th>
                            <th width="30%">Options</th>
                            <th width="10%">Correct</th>
                            <th width="10%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($questions as $question)
                        <tr>
                            <td>{{ $question['id'] }}</td>
                            <td>{{ $question['question'] }}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#optionsModal{{ $question['id'] }}">
                                    View Options
                                </button>
                                
                                <!-- Options Modal -->
                                <div class="modal fade" id="optionsModal{{ $question['id'] }}" tabindex="-1" aria-labelledby="optionsModalLabel{{ $question['id'] }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="optionsModalLabel{{ $question['id'] }}">Options for Question #{{ $question['id'] }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <ol type="A">
                                                    @foreach($question['options'] as $index => $option)
                                                    <li class="{{ $index === $question['correct'] ? 'text-success fw-bold' : '' }}">
                                                        {{ $option }}
                                                        @if($index === $question['correct'])
                                                        <span class="badge bg-success ms-2">Correct</span>
                                                        @endif
                                                    </li>
                                                    @endforeach
                                                </ol>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>Option {{ chr(65 + $question['correct']) }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('mcq_edit', $question['id']) }}" class="btn btn-sm btn-primary">Edit</a>
                                    <a href="{{ route('mcq_delete', $question['id']) }}" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this question?')">Delete</a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection 