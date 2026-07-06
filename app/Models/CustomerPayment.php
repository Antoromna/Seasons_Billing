<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerPayment extends Model
{
    protected $fillable = [
        'customer_id',
        'sale_id',
        'amount',
        'discount_amount',
        'payment_date',
        'remarks',
    ];
public function customer()
{
    return $this->belongsTo(Customer::class);
}

public function sale()
{
    return $this->belongsTo(Sale::class);
}
}
