<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyMilkSummary extends Model
{
    use HasFactory;
    protected $table = 'daily_milk_summary';

    protected $fillable = [
        'date',
        'farm_use',
        'samples',
        'waste',
    ];

    protected $casts = [
        'date' => 'date:Y-m-d',
    ];
}
