<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Group extends Model
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'name',
        'description_group',
        'user_id',
    ];


    public function members()
    {
        return $this->belongsToMany(User::class, 'group_members');
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
