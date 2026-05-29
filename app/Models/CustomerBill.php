<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerBill extends Model
{
    protected $table = 'customer_bills';

    protected $fillable = [
        'customer_id',
        'month',
        'total_liters',
        'total_amount',
        'total_days',
        'status',
        'paid_date',
        'notes',
    ];

    protected $casts = [
        'paid_date' => 'date',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}