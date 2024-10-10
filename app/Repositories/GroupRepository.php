<?php

namespace App\Repositories;

use App\Interfaces\GroupInterface;
use App\Models\Group;
use App\Models\GroupInvitation;

class GroupRepository implements GroupInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    public function create(array $data): Group
    {
        return Group::create([
            'name' => $data['name'],
            'user_id' => $data['user_id'],
            'description_group' => $data['description_group']
        ]);
    }

    public function find($id): ?Group
    {
        return Group::find($id);
    }

    public function verifyOTP($groupId, $otp)
    {
        return GroupInvitation::where('group_id', $groupId)
            ->where('otp', $otp)
            ->first();
    }

    public function addMember($groupId, $userId)
    {
        //
    }
}
