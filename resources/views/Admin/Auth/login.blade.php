<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
     <link href="{{ asset(path: 'assets/css/admin_login.css') }}" rel="stylesheet">
    <style>

    </style>
</head>
<body>

<div class="login-card">

    <h3>Login To Admin Panel</h3>

    @if(session('error'))
        <div class="alert alert-danger bg-dark text-white border-0">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ url('authenticate') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label class="form-label">Username</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-4">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-login">
            Submit
        </button>

    </form>

</div>

</body>
</html>
