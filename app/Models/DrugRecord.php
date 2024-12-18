<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DrugRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'child_id',
        'drug_item_seq',
        'dosage',
        'start_date',
        'drug_take_time',
        'drug_take_date',
    ];

    // 관계 설정
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 약물 정보 관계
    public function drug()
    {
        return $this->belongsTo(Drug::class, 'drug_item_seq', 'item_seq');
    }

}
