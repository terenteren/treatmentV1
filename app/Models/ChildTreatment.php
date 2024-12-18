<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildTreatment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'child_id',
        'kind',
        'kind_etc',
        'clinic_name',
        'start_date',
        'treatment_frequency',
        'monthly_cost',
    ];

    // User와의 관계
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // UserChildProfile과의 관계
    public function childProfile()
    {
        return $this->belongsTo(UserChildProfile::class, 'child_id');
    }

}
