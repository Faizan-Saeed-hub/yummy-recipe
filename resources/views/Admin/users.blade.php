<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset(path: 'assets/css/admin.css') }}" rel="stylesheet">

    <style>
        body {
            margin: 0;
        }
    </style>
</head>

<body>

    <div class="sidebar">
        <h4>MY ADMIN</h4>
        <a href="{{ url('/dashboard') }}">Home</a>
        <a href="{{ url('/admin/users') }}">Users</a>
        <a href="{{ url('/admin/messages') }}">Messages</a>
    </div>

    <div class="content">
        <h2>Users List</h2>

        <div class="card shadow mt-4">
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->created_at->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No Users Found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</body>

</html>
