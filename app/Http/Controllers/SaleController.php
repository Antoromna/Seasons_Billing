<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\CustomerOpeningBalance;
use App\Models\CustomerPayment;
use App\Models\TrayReturn;
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

        // Bill Type Filter
        if ($request->filled('bill_type')) {
            $query->where(
                'bill_type',
                $request->bill_type
            );
        }

        $sales = $query
            ->latest()
            ->get();

        $customers = Customer::where('status', 1)
            ->orderBy('name')
            ->get();

        return view(
            'sales.index',
            compact('sales', 'customers')
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
    $sales = Sale::with(['customer', 'items'])
        ->where('id', $id)
        ->get();

    $this->prepareSalePrintData($sales);

    return view('sales.print', [
        'sales' => $sales,
        'isBulk' => false
    ]);
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

    $sales = Sale::with(['customer','items'])
        ->whereIn('id', $ids)
        ->orderBy('bill_date')
        ->get();

    $this->prepareSalePrintData($sales);

    return view('sales.print', [
        'sales' => $sales,
        'isBulk' => true
    ]);
}

private function prepareSalePrintData($sales)
{
    foreach ($sales as $sale) {
        // Today's sale crates
        $sale->bigCrates = $sale->items
            ->where('tray', 'Big')
            ->sum('tray_qty');

        $sale->smallCrates = $sale->items
            ->where('tray', 'Small')
            ->sum('tray_qty');

        if ($sale->customer_id) {
            // Previous crate sales (before this bill)
            $previousBigSales = SaleItem::whereHas('sale', function ($q) use ($sale) {
                $q->where('customer_id', $sale->customer_id)
                    ->where(function ($sub) use ($sale) {
                        $sub->whereDate('bill_date', '<', $sale->bill_date)
                            ->orWhere(function ($s) use ($sale) {
                                $s->whereDate('bill_date', $sale->bill_date)
                                    ->where('id', '<', $sale->id);
                            });
                    });
            })
            ->where('tray', 'Big')
            ->sum('tray_qty');

            $previousSmallSales = SaleItem::whereHas('sale', function ($q) use ($sale) {
                $q->where('customer_id', $sale->customer_id)
                    ->where(function ($sub) use ($sale) {
                        $sub->whereDate('bill_date', '<', $sale->bill_date)
                            ->orWhere(function ($s) use ($sale) {
                                $s->whereDate('bill_date', $sale->bill_date)
                                    ->where('id', '<', $sale->id);
                            });
                    });
            })
            ->where('tray', 'Small')
            ->sum('tray_qty');

            // Previous crate returns (before this bill)
            $previousBigReturns = TrayReturn::where('customer_id', $sale->customer_id)
                ->where('transaction_type', 'returned')
                ->where('tray_type', 'Big')
                ->where(function ($q) use ($sale) {
                    $q->whereDate('return_date', '<', $sale->bill_date)
                        ->orWhere(function ($s) use ($sale) {
                            $s->whereDate('return_date', $sale->bill_date)
                                ->where('created_at', '<', $sale->created_at);
                        });
                })
                ->sum('tray_qty');

            $previousSmallReturns = TrayReturn::where('customer_id', $sale->customer_id)
                ->where('transaction_type', 'returned')
                ->where('tray_type', 'Small')
                ->where(function ($q) use ($sale) {
                    $q->whereDate('return_date', '<', $sale->bill_date)
                        ->orWhere(function ($s) use ($sale) {
                            $s->whereDate('return_date', $sale->bill_date)
                                ->where('created_at', '<', $sale->created_at);
                        });
                })
                ->sum('tray_qty');

            $sale->previousBigBalance = $previousBigSales - $previousBigReturns;
            $sale->previousSmallBalance = $previousSmallSales - $previousSmallReturns;

            // Today's returns up to this bill
            $todayBigReturn = TrayReturn::where('customer_id', $sale->customer_id)
                ->where('transaction_type', 'returned')
                ->where('tray_type', 'Big')
                ->whereDate('return_date', $sale->bill_date)
                ->where('created_at', '<=', $sale->created_at)
                ->sum('tray_qty');

            $todaySmallReturn = TrayReturn::where('customer_id', $sale->customer_id)
                ->where('transaction_type', 'returned')
                ->where('tray_type', 'Small')
                ->whereDate('return_date', $sale->bill_date)
                ->where('created_at', '<=', $sale->created_at)
                ->sum('tray_qty');

            $sale->currentBigBalance = $sale->previousBigBalance + $sale->bigCrates - $todayBigReturn;
            $sale->currentSmallBalance = $sale->previousSmallBalance + $sale->smallCrates - $todaySmallReturn;

            // Ledger calculation
            $openingBalance = CustomerOpeningBalance::where('customer_id', $sale->customer_id)
                ->where('created_at', '<=', $sale->created_at)
                ->sum('amount');

            $previousSales = Sale::where('customer_id', $sale->customer_id)
                ->where(function ($q) use ($sale) {
                    $q->whereDate('bill_date', '<', $sale->bill_date)
                        ->orWhere(function ($s) use ($sale) {
                            $s->whereDate('bill_date', $sale->bill_date)
                                ->where('id', '<', $sale->id);
                        });
                })
                ->sum('net_amount');

            $payment = CustomerPayment::where('customer_id', $sale->customer_id)
                ->where(function ($q) use ($sale) {
                    $q->whereDate('payment_date', '<', $sale->bill_date)
                        ->orWhere(function ($s) use ($sale) {
                            $s->whereDate('payment_date', $sale->bill_date)
                                ->where('created_at', '<', $sale->created_at);
                        });
                })
                ->selectRaw('SUM(amount) as amount, SUM(discount_amount) as discount')
                ->first();

            $payments = $payment->amount ?? 0;
            $discount = $payment->discount ?? 0;

            $calculatedPrevBalance = $openingBalance + $previousSales - ($payments + $discount);

            // Use stored previous_balance if available, else calculatedPrevBalance
            $sale->previousBalance = $sale->previous_balance ?? $calculatedPrevBalance;
            $sale->ledgerBalance = $sale->previousBalance + $sale->net_amount;
        } else {
            $sale->previousBigBalance = 0;
            $sale->previousSmallBalance = 0;
            $sale->currentBigBalance = $sale->bigCrates;
            $sale->currentSmallBalance = $sale->smallCrates;
            $sale->previousBalance = $sale->previous_balance ?? 0;
            $sale->ledgerBalance = $sale->previousBalance + $sale->net_amount;
        }
    }
}
public function getBalance($customerId)
{
    $openingBalance = CustomerOpeningBalance::where('customer_id', $customerId)
        ->sum('amount');

    $totalSales = Sale::where('customer_id', $customerId)
        ->sum('net_amount');

    $payment = CustomerPayment::where('customer_id', $customerId)
    ->selectRaw('SUM(amount) as amount, SUM(discount_amount) as discount')
    ->first();

    $totalPayments = $payment->amount ?? 0;
    $totalDiscount = $payment->discount ?? 0;

    $balance = ($openingBalance + $totalSales) - ($totalPayments + $totalDiscount);

    return response()->json([
        'balance' => $balance
    ]);
}
}
