<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [

        'bill_no',
        'bill_date',
        'bill_type',
        'customer_id',
        'sales_man_id',
        'subtotal',
        'discount',
        'net_amount',
        'previous_balance',
        'paid_amount',
        'balance',
        'tray_count',
        'notes',
    ];

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function payments()
    {
        return $this->hasMany(CustomerPayment::class);
    }
}
