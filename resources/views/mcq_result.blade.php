@extends('layouts.master')
@section('title', 'Exam Results')
@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Exam Results</h3>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <h1 class="display-1 mb-3 {{ $percentage >= 70 ? 'text-success' : ($percentage >= 50 ? 'text-warning' : 'text-danger') }}">
                            {{ number_format($percentage, 1) }}%
                        </h1>
                        <p class="lead">
                            You answered <strong>{{ $correctAnswers }}</strong> out of <strong>{{ $totalQuestions }}</strong> questions correctly.
                        </p>
                    </div>
                    
                    <div class="progress mb-4" style="height: 30px;">
                        <div 
                            class="progress-bar {{ $percentage >= 70 ? 'bg-success' : ($percentage >= 50 ? 'bg-warning' : 'bg-danger') }}" 
                            role="progressbar" 
                            style="width: {{ $percentage }}%;" 
                            aria-valuenow="{{ $percentage }}" 
                            aria-valuemin="0" 
                            aria-valuemax="100">
                            {{ number_format($percentage, 1) }}%
                        </div>
                    </div>
                    
                    <div class="row text-center mb-4">
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h5 class="card-title">Total Questions</h5>
                                    <p class="card-text display-6">{{ $totalQuestions }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h5 class="card-title">Correct Answers</h5>
                                    <p class="card-text display-6 text-success">{{ $correctAnswers }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h5 class="card-title">Incorrect Answers</h5>
                                    <p class="card-text display-6 text-danger">{{ $totalQuestions - $correctAnswers }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert {{ $percentage >= 70 ? 'alert-success' : ($percentage >= 50 ? 'alert-warning' : 'alert-danger') }}">
                        <h5 class="alert-heading">
                            @if($percentage >= 70)
                                Excellent work!
                            @elseif($percentage >= 50)
                                Good effort!
                            @else
                                You need more practice!
                            @endif
                        </h5>
                        <p>
                            @if($percentage >= 70)
                                You have demonstrated a strong understanding of the material.
                            @elseif($percentage >= 50)
                                You have a good grasp of the material, but there's room for improvement.
                            @else
                                You should review the material again and try to improve your knowledge.
                            @endif
                        </p>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-center mt-4">
                        <a href="{{ route('mcq_start') }}" class="btn btn-primary me-md-2">
                            <i class="bi bi-arrow-repeat"></i> Take Exam Again
                        </a>
                        <a href="{{ route('mcq') }}" class="btn btn-secondary">
                            <i class="bi bi-list-check"></i> Return to Questions
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 