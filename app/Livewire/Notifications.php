<?php

namespace App\Livewire;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Notifications extends Component
{
    public $notifications;

    public $showDropdown = false;

    public function mount()
    {
        $this->fetchNotifications();
    }

    public function fetchNotifications()
    {
        $this->notifications = Notification::where('client_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
    }

    public function toggleDropdown()
    {
        $this->showDropdown = ! $this->showDropdown;
        $this->fetchNotifications();
    }

    public function markAsRead($notificationId)
    {
        $notification = Notification::where('client_id', Auth::id())->find($notificationId);
        if ($notification) {
            $notification->update(['status' => 'read']);
        }
        $this->fetchNotifications();
    }

    public function render()
    {
        return view('livewire.notifications');
    }
}
