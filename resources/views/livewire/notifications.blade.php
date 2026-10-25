<div class="position-relative" wire:poll.5s="fetchNotifications">
    <!-- Notification Icon -->
    <button wire:click="toggleDropdown" class="btn btn-light position-relative">
        <svg xmlns="http://www.w3.org/2000/svg" class="bi bi-bell" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
            <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zm.104-14a4 4 0 0 1 3.9 3.418c.035.256.056.518.056.782v.55c0 1.528.5 3.063 1.44 4.282.212.272.348.619.348.996v.75a1 1 0 0 1-1 1H3.152a1 1 0 0 1-1-1v-.75c0-.377.136-.724.348-.996.94-1.219 1.44-2.754 1.44-4.282v-.55c0-.264.02-.526.056-.782A4 4 0 0 1 8.104 2z"/>
        </svg>

        <!-- Notification count badge -->
        @if($notifications->filter(fn($n) => $n->status === 'unread')->count() > 0)
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                {{ $notifications->filter(fn($n) => $n->status === 'unread')->count() }}
            </span>
        @endif
    </button>

    <!-- Notification Dropdown -->
    @if($showDropdown)
    <div class="dropdown-menu dropdown-menu-end shadow-lg show position-absolute mt-2 w-100" style="max-height: 300px; overflow-y: auto;">
        <ul class="list-group list-group-flush">
            @forelse($notifications as $notification)
                <li class="list-group-item d-flex justify-content-between align-items-start">
                    <div>
                        <p class="mb-1 small text-muted">{{ $notification->content }}</p>
                        <small class="text-muted">{{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</small>
                    </div>
                    @if($notification->status === 'unread')
                        <button wire:click="markAsRead({{ $notification->id }})" class="btn btn-link btn-sm text-decoration-none text-primary">
                            Mark as read
                        </button>
                    @endif
                </li>
            @empty
                <li class="list-group-item text-center text-muted">No notifications found.</li>
            @endforelse
        </ul>
    </div>
    @endif
</div>
