<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\CustomerPayment;
use App\Models\Customer;
use App\Models\CustomerOpeningBalance;

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

        $payment = CustomerPayment::create([
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

        return back()
            ->with('success', 'Payment Added Successfully')
            ->with('print_payment_id', empty($request->sale_id) ? $payment->id : null);
    }

    public function print($id)
    {
        $payment = CustomerPayment::with(['customer', 'sale'])->findOrFail($id);
        $customer = $payment->customer;

        // Calculate balances prior to and after this payment
        $openingBalance = (float) CustomerOpeningBalance::where('customer_id', $customer->id)
            ->where('created_at', '<=', $payment->created_at)
            ->sum('amount');

        $salesAmount = (float) Sale::where('customer_id', $customer->id)
            ->where('created_at', '<=', $payment->created_at)
            ->sum('net_amount');

        $prevPayments = CustomerPayment::where('customer_id', $customer->id)
            ->where('id', '<', $payment->id)
            ->selectRaw('SUM(amount) as amount, SUM(discount_amount) as discount')
            ->first();

        $prevPaid = (float) (($prevPayments->amount ?? 0) + ($prevPayments->discount ?? 0));
        $previousBalance = (float) ($openingBalance + $salesAmount - $prevPaid);

        $paidAmount = (float) $payment->amount;
        $discountAmount = (float) ($payment->discount_amount ?? 0);
        $totalCredit = $paidAmount + $discountAmount;
        $balanceRemaining = (float) ($previousBalance - $totalCredit);

        // Overall current balance
        $currentOpening = (float) CustomerOpeningBalance::where('customer_id', $customer->id)->sum('amount');
        $currentSales = (float) Sale::where('customer_id', $customer->id)->sum('net_amount');
        $allPayments = CustomerPayment::where('customer_id', $customer->id)
            ->selectRaw('SUM(amount) as amount, SUM(discount_amount) as discount')
            ->first();
        $currentBalance = (float) ($currentOpening + $currentSales - (($allPayments->amount ?? 0) + ($allPayments->discount ?? 0)));

        // Crate balance calculation
        $bigSales = (int) \App\Models\SaleItem::whereHas('sale', function ($q) use ($customer, $payment) {
            $q->where('customer_id', $customer->id)
              ->where(function ($sub) use ($payment) {
                  $sub->whereDate('bill_date', '<', $payment->payment_date)
                      ->orWhere(function ($s) use ($payment) {
                          $s->whereDate('bill_date', $payment->payment_date)
                            ->where('created_at', '<=', $payment->created_at);
                      });
              });
        })->where('tray', 'Big')->sum('tray_qty');

        $smallSales = (int) \App\Models\SaleItem::whereHas('sale', function ($q) use ($customer, $payment) {
            $q->where('customer_id', $customer->id)
              ->where(function ($sub) use ($payment) {
                  $sub->whereDate('bill_date', '<', $payment->payment_date)
                      ->orWhere(function ($s) use ($payment) {
                          $s->whereDate('bill_date', $payment->payment_date)
                            ->where('created_at', '<=', $payment->created_at);
                      });
              });
        })->where('tray', 'Small')->sum('tray_qty');

        $bigReturns = (int) \App\Models\TrayReturn::where('customer_id', $customer->id)
            ->where('transaction_type', 'returned')
            ->where('tray_type', 'Big')
            ->where(function ($q) use ($payment) {
                $q->whereDate('return_date', '<', $payment->payment_date)
                    ->orWhere(function ($s) use ($payment) {
                        $s->whereDate('return_date', $payment->payment_date)
                          ->where('created_at', '<=', $payment->created_at);
                    });
            })->sum('tray_qty');

        $smallReturns = (int) \App\Models\TrayReturn::where('customer_id', $customer->id)
            ->where('transaction_type', 'returned')
            ->where('tray_type', 'Small')
            ->where(function ($q) use ($payment) {
                $q->whereDate('return_date', '<', $payment->payment_date)
                    ->orWhere(function ($s) use ($payment) {
                        $s->whereDate('return_date', $payment->payment_date)
                          ->where('created_at', '<=', $payment->created_at);
                    });
            })->sum('tray_qty');

        $bigBalance = max(0, $bigSales - $bigReturns);
        $smallBalance = max(0, $smallSales - $smallReturns);

        $amountInWords = $this->numberToWords($paidAmount);

        return view('customer-payments.print', compact(
            'payment',
            'customer',
            'previousBalance',
            'paidAmount',
            'discountAmount',
            'totalCredit',
            'balanceRemaining',
            'currentBalance',
            'bigBalance',
            'smallBalance',
            'amountInWords'
        ));
    }

    private function numberToWords(float $num): string
    {
        $no = floor($num);
        $point = round(($num - $no) * 100);
        $words = [
            0 => '', 1 => 'One', 2 => 'Two', 3 => 'Three', 4 => 'Four', 5 => 'Five',
            6 => 'Six', 7 => 'Seven', 8 => 'Eight', 9 => 'Nine', 10 => 'Ten',
            11 => 'Eleven', 12 => 'Twelve', 13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',
            16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen', 19 => 'Nineteen', 20 => 'Twenty',
            30 => 'Thirty', 40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty', 70 => 'Seventy',
            80 => 'Eighty', 90 => 'Ninety'
        ];
        $digits = ['', 'Hundred', 'Thousand', 'Lakh', 'Crore'];
        $i = 0;
        $str = [];
        while ($no > 0) {
            if ($i == 0) {
                $part = $no % 1000;
                $no = floor($no / 1000);
                if ($part > 0) {
                    $h = floor($part / 100);
                    $rem = $part % 100;
                    $pStr = '';
                    if ($h > 0) { $pStr .= $words[$h] . ' Hundred '; }
                    if ($rem > 0) {
                        if ($rem < 20) { $pStr .= $words[$rem] . ' '; }
                        else { $pStr .= $words[floor($rem/10)*10] . ' ' . $words[$rem%10] . ' '; }
                    }
                    $str[] = trim($pStr);
                } else {
                    $str[] = '';
                }
                $i = 2;
            } else {
                $part = $no % 100;
                $no = floor($no / 100);
                if ($part > 0) {
                    $pStr = '';
                    if ($part < 20) { $pStr .= $words[$part] . ' '; }
                    else { $pStr .= $words[floor($part/10)*10] . ' ' . $words[$part%10] . ' '; }
                    $str[] = trim($pStr) . ' ' . ($digits[$i] ?? '');
                } else {
                    $str[] = '';
                }
                $i++;
            }
        }
        $res = trim(preg_replace('/\s+/', ' ', implode(' ', array_filter(array_reverse($str)))));
        $paiseStr = '';
        if ($point > 0) {
            $pPart = '';
            if ($point < 20) { $pPart = $words[$point] ?? ''; }
            else { $pPart = ($words[floor($point/10)*10] ?? '') . ' ' . ($words[$point%10] ?? ''); }
            $paiseStr = ' and ' . trim($pPart) . ' Paise';
        }
        return ($res ? $res . ($res === 'One' ? ' Rupee' : ' Rupees') : 'Zero Rupees') . $paiseStr . ' Only';
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
