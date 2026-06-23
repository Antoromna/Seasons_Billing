<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
    'name',
    'mobile_no',
    'address',
    'email',
    'landline',
    'gstin',
    'state',
    'opening_balance',
    'status',
];
public function payments()
{
    return $this->hasMany(CustomerPayment::class);
}
public function openingBalances()
{
    return $this->hasMany(CustomerOpeningBalance::class);
}
}
