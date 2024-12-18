<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Drug extends Model
{
    use HasFactory;

    protected $table = 'drug';

    protected $fillable = [
        'total_count',
        'item_seq',
        'item_name',
        'entp_name',
        'description',
        'chart',
        'item_image',
        'item_image_file',
        'class_name',
    ];


}
