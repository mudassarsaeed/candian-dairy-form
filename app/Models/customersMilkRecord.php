<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class customersMilkRecord extends Model
{
   protected $table = 'customers_milk_records'; // make sure this matches your table name

   protected $fillable = [
        'customer_id',
        'date',
        'day_liter',
        'price_day_liter',
        'milk_delivered',
    ];
     // Add this cast
    protected $casts = [
        'date' => 'date:Y-m-d',
    ];
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    
}
