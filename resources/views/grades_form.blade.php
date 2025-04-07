@extends('layouts.master')
@section('title', $action == 'create' ? 'Add New Grade' : 'Edit Grade')
@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">{{ $action == 'create' ? 'Add New Grade' : 'Edit Grade' }}</h4>
                </div>
                <div class="card-body">
                    <form id="gradeForm" method="POST" action="{{ $action == 'create' ? route('grades') : route('grades') }}">
                        <div class="mb-3">
                            <label for="courseCode" class="form-label">Course Code</label>
                            <input type="text" class="form-control" id="courseCode" name="course_code" value="{{ $grade ? $grade['course_code'] : '' }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="courseName" class="form-label">Course Name</label>
                            <input type="text" class="form-control" id="courseName" name="course_name" value="{{ $grade ? $grade['course_name'] : '' }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="credits" class="form-label">Credit Hours</label>
                            <select class="form-select" id="credits" name="credits" required>
                                <option value="">Select Credits</option>
                                <option value="1" {{ ($grade && $grade['credits'] == 1) ? 'selected' : '' }}>1</option>
                                <option value="2" {{ ($grade && $grade['credits'] == 2) ? 'selected' : '' }}>2</option>
                                <option value="3" {{ ($grade && $grade['credits'] == 3) ? 'selected' : '' }}>3</option>
                                <option value="4" {{ ($grade && $grade['credits'] == 4) ? 'selected' : '' }}>4</option>
                                <option value="5" {{ ($grade && $grade['credits'] == 5) ? 'selected' : '' }}>5</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="grade" class="form-label">Grade</label>
                            <select class="form-select" id="grade" name="grade" required>
                                <option value="">Select Grade</option>
                                <option value="A" {{ ($grade && $grade['grade'] == 'A') ? 'selected' : '' }}>A (4.0)</option>
                                <option value="A-" {{ ($grade && $grade['grade'] == 'A-') ? 'selected' : '' }}>A- (3.7)</option>
                                <option value="B+" {{ ($grade && $grade['grade'] == 'B+') ? 'selected' : '' }}>B+ (3.3)</option>
                                <option value="B" {{ ($grade && $grade['grade'] == 'B') ? 'selected' : '' }}>B (3.0)</option>
                                <option value="B-" {{ ($grade && $grade['grade'] == 'B-') ? 'selected' : '' }}>B- (2.7)</option>
                                <option value="C+" {{ ($grade && $grade['grade'] == 'C+') ? 'selected' : '' }}>C+ (2.3)</option>
                                <option value="C" {{ ($grade && $grade['grade'] == 'C') ? 'selected' : '' }}>C (2.0)</option>
                                <option value="C-" {{ ($grade && $grade['grade'] == 'C-') ? 'selected' : '' }}>C- (1.7)</option>
                                <option value="D+" {{ ($grade && $grade['grade'] == 'D+') ? 'selected' : '' }}>D+ (1.3)</option>
                                <option value="D" {{ ($grade && $grade['grade'] == 'D') ? 'selected' : '' }}>D (1.0)</option>
                                <option value="D-" {{ ($grade && $grade['grade'] == 'D-') ? 'selected' : '' }}>D- (0.7)</option>
                                <option value="F" {{ ($grade && $grade['grade'] == 'F') ? 'selected' : '' }}>F (0.0)</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="term" class="form-label">Term</label>
                            <select class="form-select" id="term" name="term" required>
                                <option value="">Select Term</option>
                                <option value="Fall 2023" {{ ($grade && $grade['term'] == 'Fall 2023') ? 'selected' : '' }}>Fall 2023</option>
                                <option value="Spring 2024" {{ ($grade && $grade['term'] == 'Spring 2024') ? 'selected' : '' }}>Spring 2024</option>
                                <option value="Summer 2024" {{ ($grade && $grade['term'] == 'Summer 2024') ? 'selected' : '' }}>Summer 2024</option>
                                <option value="Fall 2024" {{ ($grade && $grade['term'] == 'Fall 2024') ? 'selected' : '' }}>Fall 2024</option>
                            </select>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('grades') }}" class="btn btn-secondary me-md-2">Cancel</a>
                            <button type="submit" class="btn btn-primary" id="saveButton">Save Grade</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- GPA Impact Preview Card -->
            <div class="card mt-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">GPA Impact Preview</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-6">
                            <h6>Grade Points for this Course</h6>
                            <div class="display-6 mb-2" id="gradePointsDisplay">0.00</div>
                        </div>
                        <div class="col-md-6">
                            <h6>Contribution to GPA</h6>
                            <div class="display-6 mb-2" id="gpaContributionDisplay">0.00</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const gradeForm = document.getElementById('gradeForm');
    const gradeSelect = document.getElementById('grade');
    const creditsSelect = document.getElementById('credits');
    const gradePointsDisplay = document.getElementById('gradePointsDisplay');
    const gpaContributionDisplay = document.getElementById('gpaContributionDisplay');
    
    // Grade points mapping
    const gradePoints = {
        'A': 4.0, 'A-': 3.7,
        'B+': 3.3, 'B': 3.0, 'B-': 2.7,
        'C+': 2.3, 'C': 2.0, 'C-': 1.7,
        'D+': 1.3, 'D': 1.0, 'D-': 0.7,
        'F': 0.0
    };
    
    // Update GPA impact preview
    function updateGPAPreview() {
        const selectedGrade = gradeSelect.value;
        const selectedCredits = parseInt(creditsSelect.value) || 0;
        
        if (selectedGrade && selectedCredits) {
            const points = gradePoints[selectedGrade];
            const totalPoints = points * selectedCredits;
            
            gradePointsDisplay.textContent = points.toFixed(2);
            gpaContributionDisplay.textContent = totalPoints.toFixed(2);
        } else {
            gradePointsDisplay.textContent = '0.00';
            gpaContributionDisplay.textContent = '0.00';
        }
    }
    
    // Add event listeners
    gradeSelect.addEventListener('change', updateGPAPreview);
    creditsSelect.addEventListener('change', updateGPAPreview);
    
    // Initialize preview if values already selected (edit mode)
    updateGPAPreview();
    
    // Form submission handler
    gradeForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // In a real application, we would do validation here
        // Since this is a demo with static routes, we'll simulate success
        
        // Show success message
        alert('Grade {{ $action == "create" ? "created" : "updated" }} successfully!');
        
        // Redirect back to grades list
        window.location.href = "{{ route('grades') }}";
    });
});
</script>
@endsection 