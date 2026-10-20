<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\CustomerPayment;
class CustomerPaymentController extends Controller
{
    public function store(Request $request)
    {
        if ($request->has('amount')) {
            $request->merge([
                'amount' => str_replace(',', '', (string) $request->amount),
            ]);
        }

        $request->validate([
            'customer_id'     => 'required',
            'amount'          => 'required|numeric|min:0.01',
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

            $sale->paid_amount += ($request->amount + ($request->discount_amount ?? 0));

            // If remaining bill balance is covered by existing unallocated general payments, absorb so bill doesn't remain pending
            if ($sale->paid_amount < $sale->net_amount) {
                $unallocatedCredit = (float) (CustomerPayment::where('customer_id', $request->customer_id)
                    ->whereNull('sale_id')
                    ->selectRaw('SUM(amount + discount_amount) as total')
                    ->value('total') ?? 0);

                $needed = (float) $sale->net_amount - (float) $sale->paid_amount;
                if ($unallocatedCredit >= $needed) {
                    $sale->paid_amount = $sale->net_amount;
                }
            }

            $sale->save();
        }

        return back()->with('success', 'Payment Added Successfully');
    }
    public function getCustomerSales($customerId)
    {
        $sales = Sale::where('customer_id', $customerId)
            ->where('bill_type', 'credit')
            ->orderBy('id')
            ->get();

        // Calculate available unallocated general payments & discounts
        $unallocatedCredit = (float) (CustomerPayment::where('customer_id', $customerId)
            ->whereNull('sale_id')
            ->selectRaw('SUM(amount + discount_amount) as total')
            ->value('total') ?? 0);

        $result = [];

        foreach ($sales as $sale) {
            $received = CustomerPayment::where('sale_id', $sale->id)->sum('amount');
            $discount = CustomerPayment::where('sale_id', $sale->id)->sum('discount_amount');
            $totalDirect = max((float) $sale->paid_amount, (float) ($received + $discount));
            $billPending = (float) $sale->net_amount - $totalDirect;

            if ($billPending > 0.01) {
                // Apply unallocated general credits via FIFO
                $creditApplied = 0;
                if ($unallocatedCredit > 0) {
                    $creditApplied = min($billPending, $unallocatedCredit);
                    $unallocatedCredit -= $creditApplied;
                }

                $netPending = $billPending - $creditApplied;

                if ($netPending > 0.01) {
                    $result[] = [
                        'id'             => $sale->id,
                        'bill_no'        => $sale->bill_no,
                        'net_amount'     => (float) $sale->net_amount,
                        'bill_pending'   => (float) $billPending,
                        'credit_applied' => (float) $creditApplied,
                        'pending'        => (float) $netPending,
                    ];
                }
            }
        }

        return response()->json($result);
    }
    
}
