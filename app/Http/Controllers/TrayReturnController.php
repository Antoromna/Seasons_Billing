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
            'return_date' => $request->return_date,
            'tray_type'   => 'Big',
            'tray_qty'    => $request->big_qty,
            'remarks'     => $request->remarks,
        ]);
    }

    if ($request->small_qty > 0) {

        TrayReturn::create([
            'customer_id' => $request->customer_id,
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

            $bigGiven = \App\Models\SaleItem::join('sales', 'sales.id', '=', 'sale_items.sale_id')
                ->where('sales.customer_id', $customer->id)
                ->where('sale_items.tray', 'Big')
                ->sum('sale_items.tray_qty');

            $smallGiven = \App\Models\SaleItem::join('sales', 'sales.id', '=', 'sale_items.sale_id')
                ->where('sales.customer_id', $customer->id)
                ->where('sale_items.tray', 'Small')
                ->sum('sale_items.tray_qty');

            $bigReturned = \App\Models\TrayReturn::where('customer_id', $customer->id)
                ->where('tray_type', 'Big')
                ->sum('tray_qty');

            $smallReturned = \App\Models\TrayReturn::where('customer_id', $customer->id)
                ->where('tray_type', 'Small')
                ->sum('tray_qty');

            return [
                'customer'       => $customer,
                'big_given'      => $bigGiven,
                'big_returned'   => $bigReturned,
                'big_balance'    => $bigGiven - $bigReturned,
                'small_given'    => $smallGiven,
                'small_returned' => $smallReturned,
                'small_balance'  => $smallGiven - $smallReturned,
            ];
        });

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

        // Sales (Given)
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
                    'remarks' => 'Sale',
                ];
            }
        }

                // Returns
        $returns = TrayReturn::where('customer_id', $customer->id)
            ->orderBy('return_date')
            ->get()
            ->groupBy('return_date');

        foreach ($returns as $date => $rows) {

            $bigQty = $rows->where('tray_type', 'Big')->sum('tray_qty');
            $smallQty = $rows->where('tray_type', 'Small')->sum('tray_qty');

            $entries[] = [
                'type' => 'returned',
                'date' => $date,
                'reference' => 'Return',
                'big_returned' => $bigQty,
                'small_returned' => $smallQty,
            ];
        }

        usort($entries, function ($a, $b) {
            return strtotime($a['date']) <=> strtotime($b['date']);
        });

        // Calculate running balances here

    $bigBalance = 0;
    $smallBalance = 0;

    $data = [];

    foreach ($entries as $entry) {

        $bigGiven = 0;
        $bigReturned = 0;
        $smallGiven = 0;
        $smallReturned = 0;

        if ($entry['type'] == 'given') {

            $bigGiven = $entry['big_given'];
            $smallGiven = $entry['small_given'];

            $bigBalance += $bigGiven;
            $smallBalance += $smallGiven;

        } else {

            $bigReturned = $entry['big_returned'];
        $smallReturned = $entry['small_returned'];

        $bigBalance -= $bigReturned;
        $smallBalance -= $smallReturned;
        }

        $data[] = [
            'date' => $entry['date'],
            'reference' => $entry['reference'],

            'big_given' => $bigGiven,
            'big_returned' => $bigReturned,
            'big_balance' => $bigBalance,

            'small_given' => $smallGiven,
            'small_returned' => $smallReturned,
            'small_balance' => $smallBalance,
        ];
    }

    return response()->json($data);
    }
}
