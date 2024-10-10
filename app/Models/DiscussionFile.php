<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscussionFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id', 
        'user_id', 
        'file_path', 
        'original_name'
    ];

    public function discussion()
    {
        return $this->belongsTo(Discussion::class);
    }
}
