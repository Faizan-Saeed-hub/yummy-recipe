{{-- @php use Illuminate\Support\Str; @endphp --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Messages</title>
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
        <h2>User Messages</h2>

        <div class="card shadow mt-4">
            <div class="card-body">

                <div class="list-group">

                    @forelse($messages as $message)
                        <div class="list-group-item d-flex justify-content-between align-items-start">

                            <div>
                                <strong>{{ $message->name }} ({{ $message->email }})</strong>
                                <br>
                                <small class="text-muted">
                                    Subject: {{ $message->subject }} |
                                    {{ $message->created_at->format('d M Y - h:i A') }}
                                </small>
                                <p class="mt-2">{{ Str::limit($message->message, 120) }}</p>
                            </div>

                            <!-- Delete Button -->
                            <form action="{{ route('admin.messages.delete', $message->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm"
                                    onclick="return confirm('Are you sure you want to delete this message?')">
                                    Delete
                                </button>
                            </form>

                        </div>
                    @empty
                        <div class="list-group-item text-center">
                            No Messages Found
                        </div>
                    @endforelse

                </div>

            </div>
        </div>

    </div>

</body>

</html>
