<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\CustomerPayment;
class CustomerPaymentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'customer_id'     => 'required',
            'amount'          => 'required|numeric|min:1',
            'discount_amount' => 'nullable|numeric|min:0',
            'payment_date'    => 'required',
        ]);

        CustomerPayment::create([
            'customer_id'     => $request->customer_id,
            'sale_id'         => $request->sale_id,
            'amount'          => $request->amount,
            'discount_amount' => $request->discount_amount ?? 0,
            'payment_date'    => $request->payment_date,
            'remarks'         => $request->remarks,
        ]);

        if ($request->sale_id) {

            $sale = Sale::find($request->sale_id);

            $sale->paid_amount += $request->amount;

            $sale->save();
        }

        return back()->with('success', 'Payment Added Successfully');
    }
    public function getCustomerSales($customerId)
    {
        $sales = Sale::where('customer_id', $customerId)
            ->whereRaw('net_amount > paid_amount')
            ->select('id', 'bill_no', 'net_amount', 'paid_amount')
            ->get();

        return response()->json($sales);
    }
    
}
