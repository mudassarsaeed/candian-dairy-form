<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expenses extends Model
{
    protected $table = 'expenses';  

    protected $fillable = [
        'cat_id',
        'order_date',
        'arrival_date',
        'ending_date',
        'receipt',
        'unit',
        'number_bags_bails',
        'unit_price',
        'total',
        'other_category',  // add this
    ];
     public function category()
    {
        return $this->belongsTo(expenceCategory::class, 'cat_id');
    }
}
