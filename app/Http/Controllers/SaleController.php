<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
public function index(Request $request)
{
    $query = Sale::with('customer');

    if ($request->filled('from_date')) {
        $query->whereDate(
            'bill_date',
            '>=',
            $request->from_date
        );
    }

    if ($request->filled('to_date')) {
        $query->whereDate(
            'bill_date',
            '<=',
            $request->to_date
        );
    }

    if ($request->filled('customer_id')) {
        $query->where(
            'customer_id',
            $request->customer_id
        );
    }

    $sales = $query
        ->latest()
        ->get();

    $customers = Customer::where('status',1)
        ->orderBy('name')
        ->get();

    return view(
        'sales.index',
        compact('sales','customers')
    );
}  
public function create()
{
    $customers = Customer::where('status', 1)->orderBy('name')->get();
    $products = Product::where('status', 1)->orderBy('name')->get();

    $today = now();

    if ($today->month >= 4) {
        $startYear = $today->year;
        $endYear = $today->year + 1;
    } else {
        $startYear = $today->year - 1;
        $endYear = $today->year;
    }

    $fyPrefix = substr($startYear, -2) . '-' . substr($endYear, -2);

    $saleCount = Sale::where('bill_no', 'like', $fyPrefix . '/%')->count() + 1;

    $billNo = $fyPrefix . '/' . str_pad($saleCount, 2, '0', STR_PAD_LEFT);

    return view('sales.create', compact(
        'customers',
        'products',
        'billNo'
    ));
}

public function store(Request $request)
{
   
    $request->validate([

        'bill_no' => 'required',

        'bill_date' => 'required|date',

        'bill_type' => 'required|in:cash,credit',

        'customer_id' => 'required',

        'products' => 'required|array',

    ]);

    DB::beginTransaction();

    try {

        $netAmount = 0;

        foreach ($request->products as $item) {

            $netAmount += $item['total'];
        }

        $balance = ($netAmount + ($request->previous_balance ?? 0))
                    - ($request->paid_amount ?? 0);

        $sale = Sale::create([

            'bill_no' => $request->bill_no,

            'bill_date' => $request->bill_date,

            'bill_type' => $request->bill_type,

            'sales_man_id' => auth()->id(),

            'customer_id' => $request->customer_id,

            'net_amount' => $netAmount,

            'previous_balance' => $request->previous_balance ?? 0,

            'paid_amount' => $request->paid_amount ?? 0,

            'balance' => $balance,

            'notes' => $request->notes,
        ]);
        

        foreach ($request->products as $item) {

    SaleItem::create([

        'sale_id' => $sale->id,

        'product_id' => $item['product_id'],

        'product' => Product::find($item['product_id'])->name ?? '',

        'unit' => $item['unit'],

        'quantity' => $item['quantity'],

        'tray' => $item['tray'] ?? null,

        'tray_qty' => $item['tray_qty'] ?? 0,

        'price' => $item['price'],

        'total' => $item['total'],
    ]);
}

        DB::commit();

        return redirect()
            ->route('sales.index')
            ->with('success', 'Sale Created Successfully');

    } catch (\Exception $e) {

        DB::rollback();

        return back()->with('error', $e->getMessage());
    }
}
public function print($id)
{
    $sale = Sale::with([
        'customer',
        'items'
    ])->findOrFail($id);

    return view('sales.print', compact('sale'));
}
public function edit(Sale $sale)
{
    $sale->load('items');

    $customers = Customer::where('status', 1)
        ->orderBy('name')
        ->get();

    $products = Product::where('status', 1)
        ->orderBy('name')
        ->get();

    return view('sales.edit', compact(
        'sale',
        'customers',
        'products'
    ));
}
public function update(Request $request, Sale $sale)
{
    DB::beginTransaction();

    try {

        $netAmount = 0;

        foreach ($request->products as $item) {
            $netAmount += $item['total'];
        }

        $balance = $netAmount +
                   ($request->previous_balance ?? 0);

        $sale->update([
            'bill_date'        => $request->bill_date,
            'bill_type'        => $request->bill_type,
            'customer_id'      => $request->customer_id,
            'net_amount'       => $netAmount,
            'previous_balance' => $request->previous_balance,
            'balance'          => $balance,
            'notes'            => $request->notes,
        ]);

        $sale->items()->delete();

        foreach ($request->products as $item) {

            SaleItem::create([
                'sale_id'    => $sale->id,
                'product_id' => $item['product_id'],
                'product'    => Product::find($item['product_id'])->name,
                'unit'       => $item['unit'],
                'quantity'   => $item['quantity'],
                'tray'       => $item['tray'],
                'tray_qty'   => $item['tray_qty'],
                'price'      => $item['price'],
                'total'      => $item['total'],
            ]);
        }

        DB::commit();

        return redirect()
            ->route('sales.index')
            ->with('success', 'Sale Updated Successfully');

    } catch (\Exception $e) {

        DB::rollBack();

        return back()->with('error', $e->getMessage());
    }
}
public function bulkPrint(Request $request)
{
    $ids = explode(',', $request->ids);

    $sales = Sale::with([
        'customer',
        'items'
    ])
    ->whereIn('id', $ids)
    ->orderBy('bill_date')
    ->get();

    return view(
        'sales.bulk-print',
        compact('sales')
    );
}
}
