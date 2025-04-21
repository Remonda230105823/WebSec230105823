<nav class="navbar navbar-expand-sm bg-light">
    <div class="container-fluid">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="./">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="./even">Even Numbers</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="./prime">Prime Numbers</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="./multable">Multiplication Table</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('minitest')}}">MiniTest</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('transcript')}}">Transcript</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('productcatalog')}}">Product Catalog</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('calculator')}}">Calculator</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('gpasimulator')}}">GPA Simulator</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('users_list')}}">Users CRUD</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('grades')}}">Grades CRUD</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('mcq')}}">MCQ Exam</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('products_list')}}">Products</a>
            </li>
            @can('admin_users')
            <li class="nav-item">
                <a class="nav-link" href="{{route('users')}}">All Users</a>
            </li>
            @endcan
            @can('charge_credit')
            <li class="nav-item">
                <a class="nav-link" href="{{route('customers')}}">Customers</a>
            </li>
            @endcan
            @can('buy_products')
            <li class="nav-item">
                <a class="nav-link" href="{{route('my_purchases')}}">My Purchases</a>
            </li>
            @endcan
        </ul>
        <ul class="navbar-nav">
            @auth
            <li class="nav-item">
                <a class="nav-link" href="{{route('profile')}}">{{auth()->user()->name}} (Credits: {{auth()->user()->credits}})</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('do_logout')}}">Logout</a>
            </li>
            @else
            <li class="nav-item">
                <a class="nav-link" href="{{route('login')}}">Login</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('register')}}">Register</a>
            </li>
            @endauth
        </ul>
    </div>
</nav>
