<!DOCTYPE html>
<html>
<head>
    <title>Invoice</title>

    <style>

        body{
            margin:0;
            padding:20px;
            background:#f5f5f5;
            font-family:Arial, sans-serif;
            font-size:12px;
        }

        .invoice{
            width:420px;
            margin:0 auto;
            background:#fff;
            border:1px solid #000;
            padding:10px;
        }

        .text-center{
            text-align:center;
        }

        .text-right{
            text-align:right;
        }

        .header{
            border-bottom:1px solid #000;
            padding-bottom:5px;
            margin-bottom:5px;
        }

        .company{
            font-size:18px;
            font-weight:bold;
        }

        .small{
            font-size:11px;
        }

        table{
            width:100%;
            border-collapse:collapse;
        }

        table th,
        table td{
            border:1px solid #000;
            padding:4px;
            font-size:11px;
        }

        .no-border td{
            border:none;
            padding:2px;
        }

        .summary td{
            border:1px solid #000;
            padding:4px;
        }

        .btn-area{
            position:fixed;
            right:20px;
            top:50px;
        }

        .btn{
            display:block;
            width:140px;
            padding:10px;
            margin-bottom:10px;
            border:none;
            color:#fff;
            cursor:pointer;
            text-decoration:none;
            text-align:center;
        }

        .green{
            background:#198754;
        }

        .blue{
            background:#0d6efd;
        }

        .dark{
            background:#343a40;
        }

        @media print{

            .btn-area{
                display:none;
            }

            body{
                background:#fff;
                padding:0;
            }

            .invoice{
                border:1px solid #000;
                margin:0 auto;
            }

            @page{
                margin:5mm;
            }
        }

    </style>
</head>
<body>

<div class="btn-area">

    <button onclick="window.print()" class="btn green">
        Print Original
    </button>

    <button onclick="window.print()" class="btn blue">
        Print Duplicate
    </button>

    <a href="{{ url()->previous() }}" class="btn dark">
        Back
    </a>

</div>

<div class="invoice">

    <div class="header">

        <table class="no-border">
            <tr>
                <td width="25%">
                    {{-- Logo --}}
                    <img src="{{ asset('images/seasons.jpg') }}" width="70">
                </td>

                <td class="text-center">
                    <div class="company">
                        SEASONS FRUITS TRADERS
                    </div>

                    <div class="small">
                        Fresh Fruits Merchants & Commission Agents
                    </div>

                    <div class="small">
                        1038, Bakyalakshmi Complex,
                        Big Bazaar Street,
                        Coimbatore - 641001
                    </div>

                    <div class="small">
                        PH : 8144008056
                    </div>
                </td>
            </tr>
        </table>

    </div>

    <table class="no-border">
        <tr>
            <td>
                <strong>Bill No :</strong>
                {{ $sale->bill_no }}
            </td>

            <td class="text-right">
                <strong>Date :</strong>
                {{ \Carbon\Carbon::parse($sale->bill_date)->format('d/m/Y') }}
            </td>
        </tr>

        <tr>
            <td colspan="2">
                <strong>To :</strong>
                {{ $sale->customer->name ?? '' }}
            </td>
        </tr>
    </table>

    <br>

    <table>

        <thead>
            <tr>
                <th>S.No</th>
                <th>Particulars</th>
                <th>Qty</th>
                <th>Rate</th>
                <th>Amount</th>
            </tr>
        </thead>

        <tbody>

            @foreach($sale->items as $key => $item)

            <tr>
                <td>{{ $key + 1 }}</td>

                <td>{{ $item->product }}</td>

                <td>{{ $item->quantity }}</td>

                <td>{{ number_format($item->price,2) }}</td>

                <td>{{ number_format($item->total,2) }}</td>
            </tr>

            @endforeach

        </tbody>

    </table>

    @php

        $openingBalance =
            \App\Models\CustomerOpeningBalance::where(
                'customer_id',
                $sale->customer_id
            )->sum('amount');

        $previousSales =
            \App\Models\Sale::where('customer_id', $sale->customer_id)
                ->where('id', '<', $sale->id)
                ->sum('net_amount');

        $payments =
            \App\Models\CustomerPayment::where(
                'customer_id',
                $sale->customer_id
            )->sum('amount');

        $previousBalance =
            $openingBalance +
            $previousSales -
            $payments;

        $ledgerBalance =
            $previousBalance +
            $sale->net_amount;

    @endphp

    <br>

    <table class="summary">

        <tr>
            <td width="70%">
                Prev. Balance
            </td>

            <td class="text-right">
                ₹ {{ number_format($previousBalance,2) }}
            </td>
        </tr>

        <tr>
            <td>
                Bill Amount
            </td>

            <td class="text-right">
                ₹ {{ number_format($sale->net_amount,2) }}
            </td>
        </tr>

        <tr>
            <td>
                <strong>Net Balance</strong>
            </td>

            <td class="text-right">
                <strong>
                    ₹ {{ number_format($ledgerBalance,2) }}
                </strong>
            </td>
        </tr>

    </table>

    <br>

    <div class="small">
        Bank Details
    </div>

    <div class="small">
        HDFC BANK
    </div>

    <br><br>

    <div class="text-right">
        <strong>
            For Seasons Fruits Traders
        </strong>
    </div>

</div>

</body>
</html>