@extends('layouts.master')
@section('title', $action == 'create' ? 'Add New Question' : 'Edit Question')
@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">{{ $action == 'create' ? 'Add New Question' : 'Edit Question' }}</h4>
                </div>
                <div class="card-body">
                    <form id="questionForm" method="POST" action="{{ $action == 'create' ? route('mcq') : route('mcq') }}">
                        <div class="mb-3">
                            <label for="questionText" class="form-label">Question Text</label>
                            <textarea class="form-control" id="questionText" name="question" rows="3" required>{{ $question ? $question['question'] : '' }}</textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Options</label>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="input-group">
                                        <span class="input-group-text">A</span>
                                        <input type="text" class="form-control" name="option[0]" value="{{ $question ? $question['options'][0] : '' }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="input-group">
                                        <span class="input-group-text">B</span>
                                        <input type="text" class="form-control" name="option[1]" value="{{ $question ? $question['options'][1] : '' }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="input-group">
                                        <span class="input-group-text">C</span>
                                        <input type="text" class="form-control" name="option[2]" value="{{ $question ? $question['options'][2] : '' }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="input-group">
                                        <span class="input-group-text">D</span>
                                        <input type="text" class="form-control" name="option[3]" value="{{ $question ? $question['options'][3] : '' }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="correctOption" class="form-label">Correct Option</label>
                            <select class="form-select" id="correctOption" name="correct" required>
                                <option value="">Select Correct Option</option>
                                <option value="0" {{ ($question && $question['correct'] == 0) ? 'selected' : '' }}>Option A</option>
                                <option value="1" {{ ($question && $question['correct'] == 1) ? 'selected' : '' }}>Option B</option>
                                <option value="2" {{ ($question && $question['correct'] == 2) ? 'selected' : '' }}>Option C</option>
                                <option value="3" {{ ($question && $question['correct'] == 3) ? 'selected' : '' }}>Option D</option>
                            </select>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('mcq') }}" class="btn btn-secondary me-md-2">Cancel</a>
                            <button type="submit" class="btn btn-primary" id="saveButton">Save Question</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const questionForm = document.getElementById('questionForm');
    
    // Form submission handler
    questionForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // In a real application, we would do validation here
        // Since this is a demo with static routes, we'll simulate success
        
        // Show success message
        alert('Question {{ $action == "create" ? "created" : "updated" }} successfully!');
        
        // Redirect back to questions list
        window.location.href = "{{ route('mcq') }}";
    });
});
</script>
@endsection 