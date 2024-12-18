<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'sido',
        'sigugun',
        'selected_child_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}