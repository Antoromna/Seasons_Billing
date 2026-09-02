<!DOCTYPE html>
<html>
<head>
    <title>Invoice</title>

    <style>

        body{
            margin:0;
            padding:20px;
            background:#f5f5f5;
            font-family:"Times New Roman", serif;
            font-size:12px;
        }
        .section-total{
            text-align: end;
        }
        .invoice{
            width:500px;
            margin:0 auto;
            background:#fff;
            padding:25px;
        }
        .print_header{
            font-size: 14px !important;
        }
        .company{
            font-size:20px;
            font-weight:700;
            color:#5b2c2c; /* dark maroon */
            margin-bottom:2px;
        }

        .subtitle{
            font-size:15px;
            font-weight:600;
            color:#222;
            margin-bottom:2px;
        }

        .address{
            font-size:15px;
            font-weight:600;
            color:#333;
            line-height:1.2;
            margin-bottom:4px;
        }

        .phone{
            font-size:15px;
            font-weight:700;
            color:#333;
        }

        .text-center{
            text-align:center;
        }

        .text-right{
            text-align:right;
        }

        .header{
            padding-bottom:5px;
            margin-bottom:5px;
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
        thead th,
        .total-row td{
            border:1px solid #000;
        }

        .item-row td{
            padding:4px 4px;
            border-top:none;
            border-bottom:none;
        }

        .empty-row td{
            height:30px;
            border-left:1px solid #000;
            border-right:1px solid #000;
            border-top:none;
            border-bottom:none;
        }
        .balance-table{
            width:100%;
            border-collapse:collapse;
        }

        .balance-table td{
            border:none !important;
            padding:2px 12px;
            font-size:16px;
        }

        .balance-table .label{
            text-align:right;
        }

        .balance-table .colon{
            text-align:center;
        }

        .balance-table .amount{
            text-align:right;
        }
        .crate-table{
            width:100%;
            border-collapse:collapse;
            border:1px solid #000;
        }

        .crate-table th{
            border:1px solid #000 !important;
        }

        .crate-table td{
            border-left:1px solid #000 !important;
            border-right:1px solid #000 !important;
            border-top:none !important;
            border-bottom:none !important;
        }

        .crate-table tr:last-child td{
            border-bottom:1px solid #000 !important;
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
                margin:0 auto;
            }

            @page{
                margin:5mm;
            }
            .page-break{
                page-break-after: always;
                break-after: page;
            }

            .invoice{
                page-break-inside: avoid;
            }
        }

    </style>
</head>
<body>

    @if(!isset($isBulk) || !$isBulk)
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
    @endif
   @foreach($sales as $sale)

    @php
        $bigCrates = 0;
        $smallCrates = 0;
        $totalCrates = 0;
        $totalQty = 0;
        $totalAmount = 0;
    @endphp
    <div class="invoice">

        <div class="header">

            <table class="no-border">
                <tr>
                    <td width="25%">
                        {{-- Logo --}}
                        <img src="{{ asset('images/Seasons_Logo.png') }}" style="width:110px;height:auto;">
                    </td>

                    <td class="text-center">
                        <div class="company">
                            SEASONS FRUITS TRADERS
                        </div>

                        <div class="subtitle">
                            Fresh Fruits Merchants & Commission Agents
                        </div>

                        <div class="address">
                            1038, Bakyalakshmi Complex, Big Bazaar Street,<br>
                            Coimbatore - 641001
                        </div>

                        <div class="phone">
                            PH : 9566221566 , 8144008056
                        </div>
                    </td>
                </tr>
            </table>

        </div>
        <h3 style="
            text-align:center;
            color:darkred;
            font-family:'Times New Roman', serif;
            font-weight:700;
            font-size:22px;
            margin:8px 0;
            text-decoration:underline;
        ">
            INVOICE
        </h3>
        <table class="no-border" style="width:100%;">
    <tr>
        <td style="font-size:18px;">
            <span style="font-size:18px;">Bill No :</span>
            <span style="font-size:18px; font-weight:700;">
                {{ $sale->bill_no }}
            </span>
        </td>

        <td class="text-right" style="font-size:18px;">
            Date :
            <span style="font-weight:700;">
                {{ \Carbon\Carbon::parse($sale->bill_date)->format('d/m/Y') }}
            </span>
        </td>
    </tr>

    <tr>
        <td colspan="2" style="padding-top:8px; font-size:18px;">
            <span style="display:inline-block; width:55px;">
                To
            </span>
            :
            <span style="font-weight:700; margin-left:5px;">
                {{ $sale->customer->name ?? '' }}
            </span>

          
        </td>
    </tr>
</table>

        <br>

        <table>

            <thead>
                <tr class="print_header">
                    <th>S.No</th>
                    <th>Particulars</th>
                    <th>Crates</th>
                    <th>Qty</th>
                    <th>Rate</th>
                    <th>Amount</th>
                </tr>
            </thead>

            <tbody style="font-size: 15px;">

                @foreach($sale->items as $key => $item)

                @php
                    $totalCrates += $item->tray_qty ?? 0;

                    if ($item->tray == 'Big') {
                        $bigCrates += $item->tray_qty;
                    } elseif ($item->tray == 'Small') {
                        $smallCrates += $item->tray_qty;
                    }

                    $totalQty += $item->quantity;
                    $totalAmount += $item->total;
                @endphp

                <tr class="item-row">
                    <td  style="text-align: center;">{{ $key + 1 }}</td>
                    <td>{{ strtoupper($item->product) }}</td>
                    <td style="text-align:right;text-align: center;">{{ $item->tray_qty }}</td>
                    <td style="text-align:right;">{{ number_format($item->quantity, 2) }}</td>
                    <td style="text-align:right;">{{ number_format($item->price,2) }}</td>
                    <td style="text-align:right;">{{ number_format($item->total,2) }}</td>
                </tr>

                @endforeach
                @for($i = count($sale->items); $i < 6; $i++)
                <tr class="empty-row">
                    <td>&nbsp;</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                @endfor

                <tr style="font-weight:bold;">
                    <td colspan="2" style="text-align:right;">
                        <strong>Total</strong>
                    </td>
                    <td style="text-align:right;">
                        <strong>{{ $totalCrates }}</strong>
                    </td>
                    <td style="text-align:right;">
                        <strong>{{ $totalQty }}</strong>
                    </td>
                    <td colspan="2" style="text-align:right;">
                        <strong>{{ number_format($totalAmount,2) }}</strong>
                    </td>
                </tr>

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
                    ->whereDate('bill_date', '<', $sale->bill_date)
                    ->sum('net_amount');

            $payment = \App\Models\CustomerPayment::where('customer_id', $sale->customer_id)
                ->selectRaw('SUM(amount) as amount, SUM(discount_amount) as discount')
                ->first();

            $payments = $payment->amount ?? 0;
            $discount = $payment->discount ?? 0;

            $previousBalance =
                $openingBalance +
                $previousSales -
                ($payments + $discount);

            $ledgerBalance =
                $previousBalance +
                $sale->net_amount;

        @endphp

            <table class="no-border" style="margin-top:5px;">
                <tr>

                    <td width="45%" valign="top">

                        <table class="crate-table" style="font-size: 14px;">
                            <tr>
                    <th>Crates</th>
                    <th>B</th>
                    <th>S</th>
                </tr>

                <tr>
                    <td>Pre Balance</td>
                    <td style="text-align: end;">{{ $sale->previousBigBalance }}</td>
                    <td style="text-align: end;">{{ $sale->previousSmallBalance }}</td>
                </tr>

                <tr>
                    <td>Todays Sales</td>
                    <td style="text-align: end;">{{ $sale->bigCrates }}</td>
                    <td style="text-align: end;">{{ $sale->smallCrates }}</td>
                </tr>

                <tr>
                    <td>Balance</td>
                    <td style="text-align: end;">{{ $sale->currentBigBalance }}</td>
                    <td style="text-align: end;">{{ $sale->currentSmallBalance }}</td>
                </tr>
                            </table>

                        </td>

                        {{-- Right Side --}}
                        <td width="55%" valign="top">

                <table class="balance-table">
                    <tr>
                        <td class="label">Prev. Balance</td>
                        <td class="colon">: ₹</td>
                        <td class="amount">
                            {{ number_format($previousBalance,2) }}
                        </td>
                    </tr>

                    <tr>
                        <td class="label">Bill Amount</td>
                        <td class="colon">: ₹</td>
                        <td class="amount">
                            {{ number_format($sale->net_amount,2) }}
                        </td>
                    </tr>

                    </table>

                    <div style="border-top:2px solid #4b8beb; margin:2px 0 2px 15px;"></div>

                    <table class="balance-table">
                        <tr>
                            <td class="label"><strong>Net Balance</strong></td>
                            <td class="colon"><strong>: ₹</strong></td>
                            <td class="amount">
                                <strong>{{ number_format($ledgerBalance,2) }}</strong>
                            </td>
                        </tr>
                    </table>
                    <div style="border-top:2px solid #4b8beb; margin:2px 0 2px 15px;"></div>

            </td>

            </tr>
        </table>
       <table style="width:100%; border:none; font-size:13px; line-height:8px; margin-top:5px;">
            <tr>
                <td style="border:none; width:115px;">Bank Details</td>
                <td style="border:none; width:10px;">:</td>
                <td style="border:none;"><strong>For SEASONS FRUITS TRADERS</strong></td>
            </tr>

            <tr>
                <td style="border:none;">Bank Name / A/c No</td>
                <td style="border:none;">:</td>
                <td style="border:none;"><strong>HDFC BANK / 50200028709842</strong></td>
            </tr>

            <tr>
                <td style="border:none;">Branch / IFSC Code</td>
                <td style="border:none;">:</td>
                <td style="border:none;"><strong>VYSIAL STREET / HDFC0005194</strong></td>
            </tr>

            <tr>
            <td colspan="3" style="border:none;">
                <table style="width:100%; border:none;">
                    <tr>
                        <td style="border:none; text-align:left;">
                            E &amp; O.E
                        </td>

                        <td style="border:none; text-align:right;">
                            <strong style="color:#8b0000; font-size:15px;">
                                For Seasons Fruits Traders
                            </strong>
                        </td>
                    </tr>
                </table>
                
            </td>
        </tr>
        </table>
        <div style="border-top:2px solid #4b8beb; margin-top:3px;"></div>
        

    </div>
    @if(!$loop->last)
        <div class="page-break"></div>
    @endif
@endforeach
<script>
document.addEventListener('DOMContentLoaded', function () {
    window.print();
});
window.onload = function () {
    window.print();

    window.onafterprint = function () {
        window.close();
    };
};
</script>
</body>

</html>