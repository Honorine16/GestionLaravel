<?php

namespace App\Events;

use App\Models\Group;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserAddedToGroup
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

   

    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $groups;

    public function __construct(User $user, Group $groups)
    {
        $this->user = $user;
        $this->groups = $groups;
    }

    public function broadcastOn()
    {
        return new Channel('groups.' . $this->groups->id);
    }
    public function broadcastWith()
{
    return [
        'message' => "{$this->user->name} a été ajouté au groupe {$this->groups->name}.",
        'user' => $this->user,
        'groups' => $this->groups,
    ];
}
}
