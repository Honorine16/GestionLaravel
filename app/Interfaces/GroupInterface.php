<?php

namespace App\Interfaces;

use App\Models\Group;

interface GroupInterface
{
    public function create(array $data);
    public function find($id);
    public function verifyOTP($groupId, $otp);
    public function addMember($groupId, $userId);

}
