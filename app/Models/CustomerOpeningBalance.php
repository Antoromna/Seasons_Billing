<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerOpeningBalance extends Model
{
    protected $fillable = [
        'customer_id',
        'amount',
        'remarks',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
