<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnimalMilkRecord extends Model
{
    use HasFactory;
    protected $table = 'animal_milk_records';

    protected $fillable = [
        'animal_id',
        'date',
        'milk_quantity',
    ];

    protected $casts = [
        'date' => 'date:Y-m-d',
    ];

    public function animal()
    {
        return $this->belongsTo(Animal::class, 'animal_id');
    }
}
