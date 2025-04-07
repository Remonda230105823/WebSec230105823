@extends('layouts.master')
@section('title', 'GPA Simulator')
@section('content')
<div class="container mt-4">
    <h2>GPA Simulator</h2>
    
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Add Courses to Calculate GPA</h4>
                </div>
                <div class="card-body">
                    <!-- Course Selection Form -->
                    <form id="courseForm">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="courseSelect" class="form-label">Select Course</label>
                                <select class="form-select" id="courseSelect">
                                    <option value="" selected disabled>Choose a course</option>
                                    @foreach($courses as $course)
                                        <option value="{{ $course['code'] }}" data-credits="{{ $course['credits'] }}">
                                            {{ $course['code'] }} - {{ $course['title'] }} ({{ $course['credits'] }} credits)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="gradeSelect" class="form-label">Grade</label>
                                <select class="form-select" id="gradeSelect">
                                    <option value="4.0">A (4.0)</option>
                                    <option value="3.7">A- (3.7)</option>
                                    <option value="3.3">B+ (3.3)</option>
                                    <option value="3.0">B (3.0)</option>
                                    <option value="2.7">B- (2.7)</option>
                                    <option value="2.3">C+ (2.3)</option>
                                    <option value="2.0">C (2.0)</option>
                                    <option value="1.7">C- (1.7)</option>
                                    <option value="1.3">D+ (1.3)</option>
                                    <option value="1.0">D (1.0)</option>
                                    <option value="0.0">F (0.0)</option>
                                </select>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="button" class="btn btn-success w-100" id="addCourseBtn">
                                    Add
                                </button>
                            </div>
                        </div>
                    </form>
                    
                    <!-- Selected Courses Table -->
                    <div class="table-responsive mt-4">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Course Code</th>
                                    <th>Course Title</th>
                                    <th>Credits</th>
                                    <th>Grade</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="selectedCourses">
                                <!-- Selected courses will be added here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">GPA Results</h4>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="display-1 mb-2" id="gpaDisplay">0.00</div>
                        <p class="text-muted">Cumulative GPA</p>
                    </div>
                    <div class="d-grid">
                        <button class="btn btn-primary" id="calculateGpaBtn">Calculate GPA</button>
                    </div>
                    <hr>
                    <div class="row text-center mt-3">
                        <div class="col-6">
                            <h5 id="totalCredits">0</h5>
                            <p class="text-muted">Total Credits</p>
                        </div>
                        <div class="col-6">
                            <h5 id="courseCount">0</h5>
                            <p class="text-muted">Courses</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Store selected course data
    let selectedCourses = [];
    const coursesData = @json($courses);
    
    // DOM Elements
    const courseSelect = document.getElementById('courseSelect');
    const gradeSelect = document.getElementById('gradeSelect');
    const addCourseBtn = document.getElementById('addCourseBtn');
    const selectedCoursesTable = document.getElementById('selectedCourses');
    const calculateGpaBtn = document.getElementById('calculateGpaBtn');
    const gpaDisplay = document.getElementById('gpaDisplay');
    const totalCreditsDisplay = document.getElementById('totalCredits');
    const courseCountDisplay = document.getElementById('courseCount');
    
    // Add course to the selected courses list
    addCourseBtn.addEventListener('click', function() {
        const courseCode = courseSelect.value;
        
        if (!courseCode) {
            alert('Please select a course');
            return;
        }
        
        // Check if course is already added
        if (selectedCourses.some(course => course.code === courseCode)) {
            alert('This course is already added to your list');
            return;
        }
        
        // Find the course details
        const selectedOption = courseSelect.options[courseSelect.selectedIndex];
        const courseTitle = selectedOption.text.split(' - ')[1].split(' (')[0];
        const credits = parseInt(selectedOption.dataset.credits);
        const gradeValue = parseFloat(gradeSelect.value);
        const gradeText = gradeSelect.options[gradeSelect.selectedIndex].text.split(' ')[0];
        
        // Add to the array
        selectedCourses.push({
            code: courseCode,
            title: courseTitle,
            credits: credits,
            grade: gradeValue,
            gradeText: gradeText
        });
        
        // Update the UI
        updateSelectedCoursesTable();
        updateSummary();
    });
    
    // Calculate GPA
    calculateGpaBtn.addEventListener('click', function() {
        if (selectedCourses.length === 0) {
            alert('Please add at least one course');
            return;
        }
        
        let totalGradePoints = 0;
        let totalCredits = 0;
        
        selectedCourses.forEach(course => {
            totalGradePoints += (course.grade * course.credits);
            totalCredits += course.credits;
        });
        
        const gpa = totalCredits > 0 ? totalGradePoints / totalCredits : 0;
        gpaDisplay.textContent = gpa.toFixed(2);
    });
    
    // Update the table of selected courses
    function updateSelectedCoursesTable() {
        selectedCoursesTable.innerHTML = '';
        
        selectedCourses.forEach((course, index) => {
            const row = document.createElement('tr');
            
            row.innerHTML = `
                <td>${course.code}</td>
                <td>${course.title}</td>
                <td>${course.credits}</td>
                <td>${course.gradeText} (${course.grade})</td>
                <td>
                    <button class="btn btn-sm btn-danger remove-btn" data-index="${index}">
                        Remove
                    </button>
                </td>
            `;
            
            selectedCoursesTable.appendChild(row);
        });
        
        // Add event listeners to remove buttons
        document.querySelectorAll('.remove-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const index = parseInt(this.dataset.index);
                selectedCourses.splice(index, 1);
                updateSelectedCoursesTable();
                updateSummary();
                // Reset GPA display
                gpaDisplay.textContent = '0.00';
            });
        });
    }
    
    // Update the summary information
    function updateSummary() {
        const totalCredits = selectedCourses.reduce((sum, course) => sum + course.credits, 0);
        totalCreditsDisplay.textContent = totalCredits;
        courseCountDisplay.textContent = selectedCourses.length;
    }
});
</script>
@endsection 