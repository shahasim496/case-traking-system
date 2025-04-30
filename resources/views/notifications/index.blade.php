<!-- filepath: resources/views/notifications/index.blade.php -->
@extends('layouts.app')

@section('content')

<style>
    .nav-link .fas.fa-bell {
    font-size: 18px;
    position: relative;
}

.nav-link .badge {
    position: absolute;
    top: -5px;
    right: -5px;
    font-size: 10px;
    padding: 3px 6px;
    border-radius: 50%;
}
</style>
<div class="container">
    <h1>All Notifications</h1>
    <ul class="list-group">
        @foreach ($notifications as $notification)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                {{ $notification->data['message'] }}
                @if (is_null($notification->read_at))
                    <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-primary">Mark as Read</button>
                    </form>
                @else
                    <span class="badge bg-success">Read</span>
                @endif
            </li>
        @endforeach
    </ul>
</div>
@endsection