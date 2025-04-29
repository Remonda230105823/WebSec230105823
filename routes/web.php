<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\ProductsController;
use App\Http\Controllers\Web\UsersController;
use App\Http\Middleware\CheckPermission;

// Public routes
Route::get('register', [UsersController::class, 'register'])->name('register');
Route::post('register', [UsersController::class, 'doRegister'])->name('do_register');
Route::get('login', [UsersController::class, 'login'])->name('login');
Route::post('login', [UsersController::class, 'doLogin'])->name('do_login');
Route::get('logout', [UsersController::class, 'doLogout'])->name('do_logout');

// Add this MiniTest route before the products route
Route::get('/minitest', function () {
    // Create sample supermarket bill data
    $billData = [
        'billNumber' => 'SB-'.rand(1000, 9999),
        'date' => date('Y-m-d'),
        'customer' => 'John Doe',
        'items' => [
            [
                'name' => 'Milk',
                'quantity' => 2,
                'price' => 2.50
            ],
            [
                'name' => 'Bread',
                'quantity' => 1,
                'price' => 3.25
            ],
            [
                'name' => 'Eggs (dozen)',
                'quantity' => 1,
                'price' => 4.99
            ],
            [
                'name' => 'Cheese',
                'quantity' => 1,
                'price' => 5.75
            ],
            [
                'name' => 'Apples (kg)',
                'quantity' => 2,
                'price' => 2.99
            ]
        ],
        'taxRate' => 8
    ];
    
    // Calculate totals
    $subtotal = 0;
    foreach ($billData['items'] as $item) {
        $subtotal += $item['quantity'] * $item['price'];
    }
    
    $tax = $subtotal * ($billData['taxRate'] / 100);
    $total = $subtotal + $tax;
    
    return view('minitest', [
        'billNumber' => $billData['billNumber'],
        'date' => $billData['date'],
        'customer' => $billData['customer'],
        'items' => $billData['items'],
        'subtotal' => $subtotal,
        'taxRate' => $billData['taxRate'],
        'tax' => $tax,
        'total' => $total
    ]);
})->name('minitest');

// Add this Transcript route after the MiniTest route
Route::get('/transcript', function () {
    // Create sample student transcript data
    $studentData = [
        'student' => [
            'name' => 'Jane Smith',
            'id' => 'ST-'.rand(10000, 99999),
            'program' => 'Computer Science',
            'major' => 'Software Engineering',
            'year' => 'Junior',
            'status' => 'Active',
            'advisor' => 'Dr. Robert Johnson'
        ],
        'courses' => [
            [
                'code' => 'CS101',
                'name' => 'Introduction to Programming',
                'credits' => 3,
                'grade' => 95,
                'semester' => 'Fall 2023'
            ],
            [
                'code' => 'CS201',
                'name' => 'Data Structures',
                'credits' => 4,
                'grade' => 88,
                'semester' => 'Spring 2024'
            ],
            [
                'code' => 'MATH241',
                'name' => 'Calculus I',
                'credits' => 4,
                'grade' => 78,
                'semester' => 'Fall 2023'
            ],
            [
                'code' => 'ENG104',
                'name' => 'Technical Writing',
                'credits' => 3,
                'grade' => 92,
                'semester' => 'Fall 2023'
            ],
            [
                'code' => 'CS210',
                'name' => 'Algorithms',
                'credits' => 4,
                'grade' => 82,
                'semester' => 'Spring 2024'
            ],
            [
                'code' => 'CS310',
                'name' => 'Database Systems',
                'credits' => 3,
                'grade' => 91,
                'semester' => 'Spring 2024'
            ],
        ]
    ];
    
    // Calculate GPA and total credits
    $totalPoints = 0;
    $totalCredits = 0;
    
    foreach ($studentData['courses'] as $course) {
        $totalCredits += $course['credits'];
        
        // Convert percentage grade to 4.0 scale (simplified conversion)
        $gradePoints = 0;
        if ($course['grade'] >= 90) {
            $gradePoints = 4.0;
        } elseif ($course['grade'] >= 80) {
            $gradePoints = 3.0;
        } elseif ($course['grade'] >= 70) {
            $gradePoints = 2.0;
        } elseif ($course['grade'] >= 60) {
            $gradePoints = 1.0;
        }
        
        $totalPoints += ($gradePoints * $course['credits']);
    }
    
    $gpa = $totalCredits > 0 ? $totalPoints / $totalCredits : 0;
    
    return view('transcript', [
        'student' => $studentData['student'],
        'courses' => $studentData['courses'],
        'totalCredits' => $totalCredits,
        'gpa' => $gpa
    ]);
})->name('transcript');

// Add this ProductCatalog route after the Transcript route
Route::get('/productcatalog', function () {
    // Create sample product catalog data
    $productsData = [
        [
            'name' => 'LG TV 50"',
            'price' => 499.99,
            'image' => 'https://images.unsplash.com/photo-1593784991095-a205069470b6?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.0.3',
            'description' => '50-inch Smart LED TV with 4K Ultra HD resolution, HDR support, and built-in streaming apps.'
        ],
        [
            'name' => 'Toshiba Refrigerator 14',
            'price' => 899.99,
            'image' => 'https://images.unsplash.com/photo-1584568694244-14fbdf83bd30?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.0.3',
            'description' => '14 cubic ft French door refrigerator with adjustable shelves, LED lighting, and energy-efficient operation.'
        ]
    ];
    
    return view('productcatalog', [
        'products' => $productsData
    ]);
})->name('productcatalog');

// Add Calculator route after ProductCatalog route
Route::get('/calculator', function () {
    // Simple calculator view, no data needed
    return view('calculator');
})->name('calculator');

// Add GPA Simulator route after Calculator route
Route::get('/gpasimulator', function () {
    // Course catalog data with code, title, and credit hours
    $courseCatalog = [
        ['code' => 'CS101', 'title' => 'Introduction to Programming', 'credits' => 3],
        ['code' => 'CS201', 'title' => 'Data Structures', 'credits' => 4],
        ['code' => 'CS320', 'title' => 'Database Systems', 'credits' => 3],
        ['code' => 'MATH101', 'title' => 'Calculus I', 'credits' => 4],
        ['code' => 'MATH202', 'title' => 'Linear Algebra', 'credits' => 3],
        ['code' => 'ENG101', 'title' => 'English Composition', 'credits' => 3],
        ['code' => 'PHYS101', 'title' => 'Physics I', 'credits' => 4],
        ['code' => 'CHEM101', 'title' => 'General Chemistry', 'credits' => 4],
        ['code' => 'BIO101', 'title' => 'Biology Fundamentals', 'credits' => 3],
        ['code' => 'HIST101', 'title' => 'World History', 'credits' => 3],
    ];
    
    return view('gpasimulator', [
        'courses' => $courseCatalog
    ]);
})->name('gpasimulator');

// Users CRUD routes
Route::get('/users_list', function () {
    // Sample users data
    $users = [
        ['id' => 1, 'name' => 'John Doe', 'email' => 'john@example.com', 'role' => 'Admin'],
        ['id' => 2, 'name' => 'Jane Smith', 'email' => 'jane@example.com', 'role' => 'User'],
        ['id' => 3, 'name' => 'Robert Johnson', 'email' => 'robert@example.com', 'role' => 'User'],
        ['id' => 4, 'name' => 'Emily Davis', 'email' => 'emily@example.com', 'role' => 'Manager'],
        ['id' => 5, 'name' => 'Michael Brown', 'email' => 'michael@example.com', 'role' => 'User'],
    ];
    
    return view('users_list', ['users' => $users]);
})->name('users_list');

Route::get('/users_new', function () {
    return view('users_form', ['user' => null, 'action' => 'create']);
})->name('users_new');

Route::get('/users_edit/{id}', function ($id) {
    // Sample user data based on ID
    $users = [
        1 => ['id' => 1, 'name' => 'John Doe', 'email' => 'john@example.com', 'role' => 'Admin'],
        2 => ['id' => 2, 'name' => 'Jane Smith', 'email' => 'jane@example.com', 'role' => 'User'],
        3 => ['id' => 3, 'name' => 'Robert Johnson', 'email' => 'robert@example.com', 'role' => 'User'],
        4 => ['id' => 4, 'name' => 'Emily Davis', 'email' => 'emily@example.com', 'role' => 'Manager'],
        5 => ['id' => 5, 'name' => 'Michael Brown', 'email' => 'michael@example.com', 'role' => 'User'],
    ];
    
    return view('users_form', ['user' => $users[$id] ?? null, 'action' => 'edit']);
})->name('users_edit');

Route::get('/users_delete/{id}', function ($id) {
    // Sample success message (in a real app, deletion would happen here)
    return redirect()->route('users_list')->with('message', "User #$id has been deleted successfully.");
})->name('users_delete');

// Grades CRUD routes
Route::get('/grades', function () {
    // Sample terms data
    $terms = [
        'Fall 2023' => [
            ['id' => 1, 'course_code' => 'CS101', 'course_name' => 'Introduction to Programming', 'credits' => 3, 'grade' => 'A'],
            ['id' => 2, 'course_code' => 'MATH101', 'course_name' => 'Calculus I', 'credits' => 4, 'grade' => 'B+'],
            ['id' => 3, 'course_code' => 'ENG101', 'course_name' => 'English Composition', 'credits' => 3, 'grade' => 'A-'],
        ],
        'Spring 2024' => [
            ['id' => 4, 'course_code' => 'CS201', 'course_name' => 'Data Structures', 'credits' => 4, 'grade' => 'B'],
            ['id' => 5, 'course_code' => 'PHYS101', 'course_name' => 'Physics I', 'credits' => 4, 'grade' => 'B-'],
            ['id' => 6, 'course_code' => 'HIST101', 'course_name' => 'World History', 'credits' => 3, 'grade' => 'A'],
        ],
        'Summer 2024' => [
            ['id' => 7, 'course_code' => 'CS320', 'course_name' => 'Database Systems', 'credits' => 3, 'grade' => 'A-'],
            ['id' => 8, 'course_code' => 'MATH202', 'course_name' => 'Linear Algebra', 'credits' => 3, 'grade' => 'B+'],
        ]
    ];
    
    // Calculate GPA and CH per term
    $termStats = [];
    $cumulativeCredits = 0;
    $cumulativePoints = 0;
    
    foreach ($terms as $term => $courses) {
        $termCredits = 0;
        $termPoints = 0;
        
        foreach ($courses as $course) {
            $gradePoints = [
                'A' => 4.0, 'A-' => 3.7,
                'B+' => 3.3, 'B' => 3.0, 'B-' => 2.7,
                'C+' => 2.3, 'C' => 2.0, 'C-' => 1.7,
                'D+' => 1.3, 'D' => 1.0, 'D-' => 0.7,
                'F' => 0.0
            ];
            
            $points = $gradePoints[$course['grade']] * $course['credits'];
            $termCredits += $course['credits'];
            $termPoints += $points;
            
            $cumulativeCredits += $course['credits'];
            $cumulativePoints += $points;
        }
        
        $termStats[$term] = [
            'credits' => $termCredits,
            'gpa' => $termCredits > 0 ? round($termPoints / $termCredits, 2) : 0
        ];
    }
    
    $cumulativeGPA = $cumulativeCredits > 0 ? round($cumulativePoints / $cumulativeCredits, 2) : 0;
    
    return view('grades', [
        'terms' => $terms,
        'termStats' => $termStats,
        'cumulativeGPA' => $cumulativeGPA,
        'cumulativeCredits' => $cumulativeCredits
    ]);
})->name('grades');

Route::get('/grades/new', function () {
    return view('grades_form', ['grade' => null, 'action' => 'create']);
})->name('grades_new');

Route::get('/grades/edit/{id}', function ($id) {
    // Sample course data based on ID
    $allCourses = [
        1 => ['id' => 1, 'course_code' => 'CS101', 'course_name' => 'Introduction to Programming', 'credits' => 3, 'grade' => 'A', 'term' => 'Fall 2023'],
        2 => ['id' => 2, 'course_code' => 'MATH101', 'course_name' => 'Calculus I', 'credits' => 4, 'grade' => 'B+', 'term' => 'Fall 2023'],
        3 => ['id' => 3, 'course_code' => 'ENG101', 'course_name' => 'English Composition', 'credits' => 3, 'grade' => 'A-', 'term' => 'Fall 2023'],
        4 => ['id' => 4, 'course_code' => 'CS201', 'course_name' => 'Data Structures', 'credits' => 4, 'grade' => 'B', 'term' => 'Spring 2024'],
        5 => ['id' => 5, 'course_code' => 'PHYS101', 'course_name' => 'Physics I', 'credits' => 4, 'grade' => 'B-', 'term' => 'Spring 2024'],
        6 => ['id' => 6, 'course_code' => 'HIST101', 'course_name' => 'World History', 'credits' => 3, 'grade' => 'A', 'term' => 'Spring 2024'],
        7 => ['id' => 7, 'course_code' => 'CS320', 'course_name' => 'Database Systems', 'credits' => 3, 'grade' => 'A-', 'term' => 'Summer 2024'],
        8 => ['id' => 8, 'course_code' => 'MATH202', 'course_name' => 'Linear Algebra', 'credits' => 3, 'grade' => 'B+', 'term' => 'Summer 2024'],
    ];
    
    return view('grades_form', ['grade' => $allCourses[$id] ?? null, 'action' => 'edit']);
})->name('grades_edit');

Route::get('/grades/delete/{id}', function ($id) {
    // Sample success message (in a real app, deletion would happen here)
    return redirect()->route('grades')->with('message', "Course grade #$id has been deleted successfully.");
})->name('grades_delete');

// MCQ Exam routes
Route::get('/mcq', function () {
    // Sample questions data
    $questions = [
        ['id' => 1, 'question' => 'What does HTML stand for?', 'options' => ['Hyper Text Markup Language', 'High Tech Modern Language', 'Hyper Transfer Markup Language', 'Home Tool Markup Language'], 'correct' => 0],
        ['id' => 2, 'question' => 'Which of the following is a JavaScript framework?', 'options' => ['Django', 'Flask', 'React', 'Ruby on Rails'], 'correct' => 2],
        ['id' => 3, 'question' => 'What is the correct SQL statement to select all data from a table named "users"?', 'options' => ['SELECT * FROM users', 'SELECT ALL FROM users', 'SELECT users FROM database', 'GET * FROM users'], 'correct' => 0],
        ['id' => 4, 'question' => 'Which CSS property is used to change the text color of an element?', 'options' => ['font-color', 'text-color', 'color', 'text-style'], 'correct' => 2],
        ['id' => 5, 'question' => 'In PHP, which function is used to open a file?', 'options' => ['open()', 'fopen()', 'file_open()', 'openfile()'], 'correct' => 1],
    ];
    
    return view('mcq_list', ['questions' => $questions]);
})->name('mcq');

Route::get('/mcq/new', function () {
    return view('mcq_form', ['question' => null, 'action' => 'create']);
})->name('mcq_new');

Route::get('/mcq/edit/{id}', function ($id) {
    // Sample question based on ID
    $questions = [
        1 => ['id' => 1, 'question' => 'What does HTML stand for?', 'options' => ['Hyper Text Markup Language', 'High Tech Modern Language', 'Hyper Transfer Markup Language', 'Home Tool Markup Language'], 'correct' => 0],
        2 => ['id' => 2, 'question' => 'Which of the following is a JavaScript framework?', 'options' => ['Django', 'Flask', 'React', 'Ruby on Rails'], 'correct' => 2],
        3 => ['id' => 3, 'question' => 'What is the correct SQL statement to select all data from a table named "users"?', 'options' => ['SELECT * FROM users', 'SELECT ALL FROM users', 'SELECT users FROM database', 'GET * FROM users'], 'correct' => 0],
        4 => ['id' => 4, 'question' => 'Which CSS property is used to change the text color of an element?', 'options' => ['font-color', 'text-color', 'color', 'text-style'], 'correct' => 2],
        5 => ['id' => 5, 'question' => 'In PHP, which function is used to open a file?', 'options' => ['open()', 'fopen()', 'file_open()', 'openfile()'], 'correct' => 1],
    ];
    
    return view('mcq_form', ['question' => $questions[$id] ?? null, 'action' => 'edit']);
})->name('mcq_edit');

Route::get('/mcq/delete/{id}', function ($id) {
    // Sample success message (in a real app, deletion would happen here)
    return redirect()->route('mcq')->with('message', "Question #$id has been deleted successfully.");
})->name('mcq_delete');

Route::get('/mcq/start', function () {
    // Sample questions data for exam
    $questions = [
        ['id' => 1, 'question' => 'What does HTML stand for?', 'options' => ['Hyper Text Markup Language', 'High Tech Modern Language', 'Hyper Transfer Markup Language', 'Home Tool Markup Language']],
        ['id' => 2, 'question' => 'Which of the following is a JavaScript framework?', 'options' => ['Django', 'Flask', 'React', 'Ruby on Rails']],
        ['id' => 3, 'question' => 'What is the correct SQL statement to select all data from a table named "users"?', 'options' => ['SELECT * FROM users', 'SELECT ALL FROM users', 'SELECT users FROM database', 'GET * FROM users']],
        ['id' => 4, 'question' => 'Which CSS property is used to change the text color of an element?', 'options' => ['font-color', 'text-color', 'color', 'text-style']],
        ['id' => 5, 'question' => 'In PHP, which function is used to open a file?', 'options' => ['open()', 'fopen()', 'file_open()', 'openfile()']],
    ];
    
    return view('mcq_exam', ['questions' => $questions]);
})->name('mcq_start');

Route::get('/mcq/result', function () {
    // For demo purposes, we'll simulate results via query params
    $totalQuestions = request()->query('total', 5);
    $correctAnswers = request()->query('correct', 3);
    $percentage = ($correctAnswers / $totalQuestions) * 100;
    
    return view('mcq_result', [
        'totalQuestions' => $totalQuestions,
        'correctAnswers' => $correctAnswers,
        'percentage' => $percentage
    ]);
})->name('mcq_result');

Route::get('products', [ProductsController::class, 'list'])->name('products_list');

// Routes that require authentication
Route::middleware(['auth'])->group(function () {
    // Profile routes - accessible to all authenticated users
Route::get('profile/{user?}', [UsersController::class, 'profile'])->name('profile');
    
    // Routes without middleware - permission checks are in controllers
Route::get('users/edit/{user?}', [UsersController::class, 'edit'])->name('users_edit');
Route::post('users/save/{user}', [UsersController::class, 'save'])->name('users_save');
Route::get('users/edit_password/{user?}', [UsersController::class, 'editPassword'])->name('edit_password');
Route::post('users/save_password/{user}', [UsersController::class, 'savePassword'])->name('save_password');
    

    Route::get('customers', [UsersController::class, 'listCustomers'])->name('customers');
Route::post('users/charge_credit/{user}', [UsersController::class, 'chargeCredit'])->name('charge_credit');


    // Customer specific routes
    Route::get('products/buy/{product}', [ProductsController::class, 'buyProduct'])->name('buy_product');
    Route::get('my-purchases', [ProductsController::class, 'myPurchases'])->name('my_purchases');

    
    // Product management routes
Route::get('products/edit/{product?}', [ProductsController::class, 'edit'])->name('products_edit');
Route::post('products/save/{product?}', [ProductsController::class, 'save'])->name('products_save');
Route::get('products/delete/{product}', [ProductsController::class, 'delete'])->name('products_delete');

    // Admin specific routes
    Route::get('users', [UsersController::class, 'list'])->name('users');
    Route::get('users/delete/{user}', [UsersController::class, 'delete'])->name('users_delete');
});


Route::get('/', function () {
    return view('welcome');
});

Route::get('/multable', function (Request $request) {
    $j = $request->number??5;
    $msg = $request->msg;
    return view('multable', compact("j", "msg"));
});

Route::get('/even', function () {
    return view('even');
});

Route::get('/prime', function () {
    return view('prime');
});

Route::get('/test', function () {
    return view('test');
});
