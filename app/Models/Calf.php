<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calf extends Model
{
    use HasFactory;
    protected $table = 'calfs';

    protected $fillable = [
        'tag_number',
        'calf_date',
        'gender',
        'expected_insemination_date',
        'expected_delivery_date',
        'notes',
    ];

    protected $casts = [
        'calf_date'                  => 'date',
        'expected_insemination_date' => 'date',
        'expected_delivery_date'     => 'date',
    ];
}
