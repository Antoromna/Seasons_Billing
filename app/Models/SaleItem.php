<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
     protected $fillable = [

        'sale_id',
        'product_id',
        'code',
        'product',
        'unit',
        'quantity',
        'price',
        'discount',
        'total',
        'tray',
        'tray_qty',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function productData()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
