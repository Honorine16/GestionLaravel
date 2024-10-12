<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class GroupInvitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'email',
        'otp',
    ];

public function group() {
    return $this->belongsTo(Group::class);
}

    protected function casts(): array
    {
        return [
            'code' => 'hashed',
        ];
    }
}
