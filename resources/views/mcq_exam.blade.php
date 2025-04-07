@extends('layouts.master')
@section('title', 'MCQ Exam')
@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0">MCQ Exam</h3>
        </div>
        <div class="card-body">
            <div class="alert alert-info">
                <h5><i class="bi bi-info-circle"></i> Exam Instructions</h5>
                <ul>
                    <li>This exam contains {{ count($questions) }} multiple-choice questions.</li>
                    <li>Select one answer for each question.</li>
                    <li>Click the "Submit Exam" button when you are finished.</li>
                    <li>You cannot go back to previous questions once submitted.</li>
                </ul>
            </div>
            
            <form id="examForm">
                @foreach($questions as $index => $question)
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Question {{ $index + 1 }}</h5>
                    </div>
                    <div class="card-body">
                        <p class="fw-bold">{{ $question['question'] }}</p>
                        
                        <div class="mt-3">
                            @foreach($question['options'] as $optionIndex => $option)
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="answer[{{ $question['id'] }}]" id="q{{ $question['id'] }}_opt{{ $optionIndex }}" value="{{ $optionIndex }}" required>
                                <label class="form-check-label" for="q{{ $question['id'] }}_opt{{ $optionIndex }}">
                                    {{ chr(65 + $optionIndex) }}. {{ $option }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach
                
                <div class="d-grid gap-2 col-md-6 mx-auto mt-4">
                    <button type="submit" class="btn btn-primary btn-lg">Submit Exam</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const examForm = document.getElementById('examForm');
    const questions = @json($questions);
    
    // Correct answers (in real app these would be hidden from client)
    const correctAnswers = {
        1: 0, // HTML = A
        2: 2, // JavaScript framework = C
        3: 0, // SQL statement = A
        4: 2, // CSS property = C
        5: 1  // PHP function = B
    };
    
    examForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Get form data
        const formData = new FormData(examForm);
        
        // Count correct answers
        let correctCount = 0;
        for (const [key, value] of formData.entries()) {
            const questionId = parseInt(key.match(/\d+/)[0]);
            const selectedAnswer = parseInt(value);
            
            if (correctAnswers[questionId] === selectedAnswer) {
                correctCount++;
            }
        }
        
        // Redirect to results page
        window.location.href = "{{ route('mcq_result') }}?total=" + questions.length + "&correct=" + correctCount;
    });
});
</script>
@endsection 