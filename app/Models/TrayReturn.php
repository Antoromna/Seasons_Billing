<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrayReturn extends Model
{
    protected $fillable = [
        'customer_id',
        'transaction_type',
        'return_date',
        'tray_type',
        'tray_qty',
        'remarks'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
