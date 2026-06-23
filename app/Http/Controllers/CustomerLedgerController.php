<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\CustomerPayment;
use App\Models\CustomerOpeningBalance;

class CustomerLedgerController extends Controller
{
public function index()
{
    $customers = Customer::latest()->get();

    foreach ($customers as $customer) {

        $openingBalance = CustomerOpeningBalance::where(
                'customer_id',
                $customer->id
            )->sum('amount');

        $salesAmount = Sale::where(
                'customer_id',
                $customer->id
            )->sum('net_amount');

        $paymentsReceived = CustomerPayment::where(
                'customer_id',
                $customer->id
            )->sum('amount');

        $customer->due_amount =
            $openingBalance
            + $salesAmount
            - $paymentsReceived;
    }

    return view('customer-ledger.index', compact('customers'));
}

public function bills($customerId)
{
    $customer = Customer::findOrFail($customerId);

    $entries = [];

    // Opening Balance Entries
    $openingBalances = CustomerOpeningBalance::where(
        'customer_id',
        $customerId
    )->get();

    foreach ($openingBalances as $opening) {

        $entries[] = [
            'type' => 'opening_balance',
            'date' => $opening->created_at,
            'data' => $opening,
        ];
    }

    // Sales
    $sales = Sale::where('customer_id', $customerId)->get();

    foreach ($sales as $sale) {

        $entries[] = [
            'type' => 'sale',
            'date' => $sale->bill_date,
            'data' => $sale,
        ];
    }

    // General Payments (without bill)
    $payments = CustomerPayment::where('customer_id', $customerId)
        ->whereNull('sale_id')
        ->get();

    foreach ($payments as $payment) {

        $entries[] = [
            'type' => 'payment',
            'date' => $payment->payment_date,
            'data' => $payment,
        ];
    }

    // Sort by date
    usort($entries, function ($a, $b) {
        return strtotime($a['date']) <=> strtotime($b['date']);
    });

    $ledgerBalance = 0;

    $data = [];

    foreach ($entries as $entry) {

        // Opening Balance
        if ($entry['type'] == 'opening_balance') {

            $opening = $entry['data'];

            $ledgerBalance += $opening->amount;

            $data[] = [
                'sale_id' => null,
                'bill_id' => 'Opening Balance',
                'date' => $opening->created_at->format('Y-m-d'),
                'received' => 0,
                'pending' => $opening->amount,
                'ledger_balance' => $ledgerBalance,
                 'remarks' => $opening->remarks,
                
            ];
        }

        // Sale
        elseif ($entry['type'] == 'sale') {

            $sale = $entry['data'];

            $received = CustomerPayment::where(
                'sale_id',
                $sale->id
            )->sum('amount');

            $pending = $sale->net_amount - $received;

            $ledgerBalance += $pending;

            $data[] = [
                'sale_id' => $sale->id,
                'bill_id' => $sale->bill_no,
                'date' => $sale->bill_date,
                'received' => $received,
                'pending' => $pending,
                'ledger_balance' => $ledgerBalance,
                 'remarks' => $sale->remarks,
            ];
        }

        // General Payment
        else {

            $payment = $entry['data'];

            $ledgerBalance -= $payment->amount;

            $data[] = [
                'sale_id' => null,
                'bill_id' => 'General Payment',
                'date' => $payment->payment_date,
                'received' => $payment->amount,
                'pending' => 0,
                'ledger_balance' => $ledgerBalance,
                 'remarks' => $payment->remarks,
            ];
        }
    }

    return response()->json($data);
}
}
