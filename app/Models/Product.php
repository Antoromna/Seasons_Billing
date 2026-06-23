<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
    'name',
    'code',
    'unit',
    'tray_required',
    'hsn_no',
    'gst',
    'stock',
    'selling_price',
    'status',
];
}
