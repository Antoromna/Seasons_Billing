<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\SaleItem;
use App\Models\Customer;

class ReportController extends Controller
{
public function productWise(Request $request)
{
    $products = Product::orderBy('name')->get();
    $customers = Customer::orderBy('name')->get();

    $query = SaleItem::with([
        'sale.customer',
        'product'
    ]);

    if ($request->filled('from_date')) {
        $query->whereHas('sale', function ($q) use ($request) {
            $q->whereDate('bill_date', '>=', $request->from_date);
        });
    }

    if ($request->filled('to_date')) {
        $query->whereHas('sale', function ($q) use ($request) {
            $q->whereDate('bill_date', '<=', $request->to_date);
        });
    }

    if ($request->filled('customer_id')) {
        $query->whereHas('sale', function ($q) use ($request) {
            $q->where('customer_id', $request->customer_id);
        });
    }

    if ($request->filled('product_id')) {
        $query->where('product_id', $request->product_id);
    }

    $items = $query->get();

    $totalAmount = (clone $query)->sum('total');

    return view('reports.product-wise', compact(
        'items',
        'products',
        'customers',
        'totalAmount'
    ));
}

}
