<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Animal extends Model
{
    use HasFactory;

    protected $table = 'animals';

    protected $fillable = [
        'tag_id',
        'animal_type',
        'gender',
        'timer',
        'insemination_date',
        'semen_type',
        'exp_insemination_date',
        'confirmation_date',
        'status',
        'calf_date',
        'date_of_birth',
        'notes',
    ];

    protected $casts = [
        'insemination_date'     => 'date',
        'exp_insemination_date' => 'date',
        'calf_date'             => 'date',
        'date_of_birth'         => 'date',
    ];
}
