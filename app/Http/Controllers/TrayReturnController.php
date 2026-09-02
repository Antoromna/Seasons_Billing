<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\TrayReturn;
use App\Models\SaleItem;
use App\Models\Sale;
class TrayReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    /**
     * Show the form for creating a new resource.
     */
   public function create()
    {
        $customers = Customer::orderBy('name')->get();

        return view('tray-returns.summary', compact('customers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    $request->validate([
        'customer_id' => 'required|exists:customers,id',
        'return_date' => 'required|date',
        'big_qty'     => 'nullable|integer|min:0',
        'small_qty'   => 'nullable|integer|min:0',
    ]);

    if (($request->big_qty ?? 0) <= 0 &&
        ($request->small_qty ?? 0) <= 0) {

        return back()->withErrors([
            'tray' => 'Enter Big Tray or Small Tray quantity.'
        ]);
    }

    if ($request->big_qty > 0) {

        TrayReturn::create([
            'customer_id' => $request->customer_id,
            'transaction_type'=> 'returned',
            'return_date' => $request->return_date,
            'tray_type'   => 'Big',
            'tray_qty'    => $request->big_qty,
            'remarks'     => $request->remarks,
        ]);
    }

    if ($request->small_qty > 0) {

        TrayReturn::create([
            'customer_id' => $request->customer_id,
            'transaction_type'=> 'returned',
            'return_date' => $request->return_date,
            'tray_type'   => 'Small',
            'tray_qty'    => $request->small_qty,
            'remarks'     => $request->remarks,
        ]);
    }

    return redirect()
        ->route('tray-returns.summary')
        ->with('success', 'Tray Return Added Successfully');
    }
    public function give(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'give_date'   => 'required|date',
            'big_qty'     => 'nullable|integer|min:0',
            'small_qty'  => 'nullable|integer|min:0',
            'remarks'     => 'nullable|string|max:255',
        ]);

        $bigQty = (int) $request->big_qty;
        $smallQty = (int) $request->small_qty;

        if ($bigQty <= 0 && $smallQty <= 0) {
            return back()
                ->with('error', 'Enter at least one tray quantity.');
        }

        if ($bigQty > 0) {

            TrayReturn::create([
                'customer_id'      => $request->customer_id,
                'transaction_type' => 'given',
                'return_date'      => $request->give_date,
                'tray_type'        => 'Big',
                'tray_qty'         => $bigQty,
                'remarks'          => $request->remarks,
            ]);
        }

        if ($smallQty > 0) {

            TrayReturn::create([
                'customer_id'      => $request->customer_id,
                'transaction_type' => 'given',
                'return_date'      => $request->give_date,
                'tray_type'        => 'Small',
                'tray_qty'         => $smallQty,
                'remarks'          => $request->remarks,
            ]);
        }

        return back()->with('success', 'Tray given successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function summary()
    {
        $customers = \App\Models\Customer::orderBy('name')->get();

        $summary = $customers->map(function ($customer) {

            // -----------------------------------------
            // DC SALE - TRAYS GIVEN
            // -----------------------------------------

            $bigSaleGiven = SaleItem::join(
                    'sales',
                    'sales.id',
                    '=',
                    'sale_items.sale_id'
                )
                ->where('sales.customer_id', $customer->id)
                ->where('sale_items.tray', 'Big')
                ->sum('sale_items.tray_qty');

            $smallSaleGiven = SaleItem::join(
                    'sales',
                    'sales.id',
                    '=',
                    'sale_items.sale_id'
                )
                ->where('sales.customer_id', $customer->id)
                ->where('sale_items.tray', 'Small')
                ->sum('sale_items.tray_qty');


            // -----------------------------------------
            // MANUAL TRAYS GIVEN
            // -----------------------------------------

            $bigManualGiven = TrayReturn::where('customer_id', $customer->id)
                ->where('transaction_type', 'given')
                ->where('tray_type', 'Big')
                ->sum('tray_qty');

            $smallManualGiven = TrayReturn::where('customer_id', $customer->id)
                ->where('transaction_type', 'given')
                ->where('tray_type', 'Small')
                ->sum('tray_qty');


            // -----------------------------------------
            // TRAYS RETURNED
            // -----------------------------------------

            $bigReturned = TrayReturn::where('customer_id', $customer->id)
                ->where('transaction_type', 'returned')
                ->where('tray_type', 'Big')
                ->sum('tray_qty');

            $smallReturned = TrayReturn::where('customer_id', $customer->id)
                ->where('transaction_type', 'returned')
                ->where('tray_type', 'Small')
                ->sum('tray_qty');


            // -----------------------------------------
            // TOTAL GIVEN
            // -----------------------------------------

            $bigGiven = $bigSaleGiven + $bigManualGiven;

            $smallGiven = $smallSaleGiven + $smallManualGiven;


            // -----------------------------------------
            // BALANCE
            // -----------------------------------------

            return [
                'customer' => $customer,

                'big_given' => $bigGiven,
                'big_returned' => $bigReturned,
                'big_balance' => $bigGiven - $bigReturned,

                'small_given' => $smallGiven,
                'small_returned' => $smallReturned,
                'small_balance' => $smallGiven - $smallReturned,
            ];
        });

        $filter = request('balance_filter', 'all');

        if ($filter === 'with_balance') {

            $summary = $summary->filter(function ($row) {
                return $row['big_balance'] > 0
                    || $row['small_balance'] > 0;
            });

        } elseif ($filter === 'without_balance') {

            $summary = $summary->filter(function ($row) {
                return $row['big_balance'] == 0
                    && $row['small_balance'] == 0;
            });
        }

        return view(
        'tray-returns.summary',
        compact('summary', 'customers')
    );
    }

    public function billDetails($billNo)
    {
        $items = SaleItem::join('sales', 'sales.id', '=', 'sale_items.sale_id')
            ->where('sales.bill_no', $billNo)
            ->select(
                'sale_items.product',
                'sale_items.tray',
                'sale_items.tray_qty'
            )
            ->get();

        return response()->json($items);
    }
    public function ledger(Customer $customer)
    {
        $entries = [];

        /*
        |--------------------------------------------------------------------------
        | Sales (Given)
        |--------------------------------------------------------------------------
        */
        $sales = Sale::with('items')
            ->where('customer_id', $customer->id)
            ->get();

        foreach ($sales as $sale) {

            $bigQty = $sale->items
                ->where('tray', 'Big')
                ->sum('tray_qty');

            $smallQty = $sale->items
                ->where('tray', 'Small')
                ->sum('tray_qty');

            if ($bigQty > 0 || $smallQty > 0) {

                $entries[] = [
                    'type' => 'given',
                    'date' => $sale->bill_date,
                    'reference' => $sale->bill_no,

                    'big_given' => $bigQty,
                    'small_given' => $smallQty,

                    'big_returned' => 0,
                    'small_returned' => 0,

                    'remarks' => 'Sale',
                ];
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Tray Transactions (Given / Returned)
        |--------------------------------------------------------------------------
        */
        $trayTransactions = TrayReturn::where('customer_id', $customer->id)
            ->orderBy('return_date')
            ->get();

        foreach ($trayTransactions as $transaction) {

            $bigQty = $transaction->tray_type === 'Big'
                ? $transaction->tray_qty
                : 0;

            $smallQty = $transaction->tray_type === 'Small'
                ? $transaction->tray_qty
                : 0;


            if ($transaction->transaction_type === 'given') {

                $entries[] = [
                    'type' => 'given',
                    'date' => $transaction->return_date,
                    'reference' => 'Given',

                    'big_given' => $bigQty,
                    'small_given' => $smallQty,

                    'big_returned' => 0,
                    'small_returned' => 0,

                    'remarks' => $transaction->remarks ?? 'Given',
                ];

            } else {

                $entries[] = [
                    'type' => 'returned',
                    'date' => $transaction->return_date,
                    'reference' => 'Return',

                    'big_given' => 0,
                    'small_given' => 0,

                    'big_returned' => $bigQty,
                    'small_returned' => $smallQty,

                    'remarks' => $transaction->remarks ?? 'Return',
                ];
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Sort by Date
        |--------------------------------------------------------------------------
        */
        usort($entries, function ($a, $b) {
            return strtotime($a['date']) <=> strtotime($b['date']);
        });


        /*
        |--------------------------------------------------------------------------
        | Running Balance
        |--------------------------------------------------------------------------
        */
        $bigBalance = 0;
        $smallBalance = 0;

        $data = [];

        foreach ($entries as $entry) {

            $bigGiven = $entry['big_given'] ?? 0;
            $bigReturned = $entry['big_returned'] ?? 0;

            $smallGiven = $entry['small_given'] ?? 0;
            $smallReturned = $entry['small_returned'] ?? 0;

            // Given increases balance
            $bigBalance += $bigGiven;
            $smallBalance += $smallGiven;

            // Returned decreases balance
            $bigBalance -= $bigReturned;
            $smallBalance -= $smallReturned;

            $data[] = [
                'date' => $entry['date'],
                'reference' => $entry['reference'],

                'big_given' => $bigGiven,
                'big_returned' => $bigReturned,
                'big_balance' => $bigBalance,

                'small_given' => $smallGiven,
                'small_returned' => $smallReturned,
                'small_balance' => $smallBalance,

                'remarks' => $entry['remarks'] ?? '',
            ];
        }

        return response()->json($data);
    }
}
