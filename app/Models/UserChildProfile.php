<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserChildProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'profile_image',
        'profile_image_origin_name',
        'kid_name',
        'birth',
        'gender',
        'disease_name',
    ];

    // User와의 관계 설정
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
