<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset(path: 'assets/css/admin.css') }}" rel="stylesheet">
</head>

<body>

    <div class="sidebar">
        <h4>MY ADMIN</h4>
        <a href="{{ url('/dashboard') }}">Home</a>
        <a href="{{ url('/admin/users') }}">Users</a>
        <a href="{{ url('/admin/messages') }}">Messages</a>
        <form action="{{ route('logout') }}" method="POST" class="mt-3 align-items-center d-flex justify-content-center">
            @csrf
            <button type="submit" class="btn btn-danger w-50">
                Logout
            </button>
        </form>
    </div>

    <div class="content">
        <h2>Dashboard</h2>
        <div class="alert alert-primary mt-4">
            Welcome to Admin Panel
        </div>
    </div>

</body>

</html>
