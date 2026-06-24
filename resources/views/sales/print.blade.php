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
        .section-total{
            text-align: end;
        }
        .invoice{
            width:420px;
            margin:0 auto;
            background:#fff;
            border:1px solid #000;
            padding:10px;
        }
        
        .company{
            font-size:18px;
            font-weight:700;
            color:#5b2c2c; /* dark maroon */
            letter-spacing:1px;
            margin-bottom:2px;
        }

        .subtitle{
            font-size:13px;
            font-weight:600;
            color:#222;
            margin-bottom:2px;
        }

        .address{
            font-size:12px;
            font-weight:600;
            color:#333;
            line-height:1.2;
            margin-bottom:4px;
        }

        .phone{
            font-size:12px;
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
    @php
        $totalCrates = 0;
        $totalQty = 0;
        $totalAmount = 0;
    @endphp

@foreach($sales as $sale)
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

                        <div class="subtitle">
                            Fresh Fruits Merchants & Commission Agents
                        </div>

                        <div class="address">
                            1038, Bakyalakshmi Complex, Big Bazaar Street,<br>
                            Coimbatore - 641001
                        </div>

                        <div class="phone">
                            PH : 8144008056 , 8144008056
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
            font-size:16px;
            margin:8px 0;
            text-decoration:underline;
        ">
            INVOICE
        </h3>
        <table class="no-border">
            <tr>
                <td>
                    <span style="font-size:16px;">Bill No :</span>

                    <span style="font-size:16px; font-weight:700;">
                        {{ $sale->bill_no }}
                    </span>
                </td>

                <td class="text-right" style="font-size:16px;">
                    Date :
                    <span style="font-weight:700;">
                        {{ \Carbon\Carbon::parse($sale->bill_date)->format('d/m/Y') }}
                    </span>
                </td>
            </tr>

            <tr>
                <td colspan="2" style="font-size:16px;">
                    To :
                    <span style="font-weight:700;">
                        {{ $sale->customer->name ?? '' }}
                    </span>
                </td>
            </tr>
        </table>

        <br>

        <table>

            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Particulars</th>
                    <th>Crates</th>
                    <th>Qty</th>
                    <th>Rate</th>
                    <th>Amount</th>
                </tr>
            </thead>

            <tbody>

                @foreach($sale->items as $key => $item)

                @php
                    $totalCrates += $item->tray_qty ?? 0;
                    $totalQty += $item->quantity;
                    $totalAmount += $item->total;
                @endphp

                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $item->product }}</td>
                    <td>{{ $item->tray_qty }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->price,2) }}</td>
                    <td>{{ number_format($item->total,2) }}</td>
                </tr>

                @endforeach

                <tr>
                    <td colspan="2" style="text-align:right;">
                        <strong>Total</strong>
                    </td>
                    <td>
                        <strong>{{ $totalCrates }}</strong>
                    </td>
                    <td>
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

    <table class="no-border" style="margin-top:5px;">
            <tr>

            {{-- Left Side --}}
            <td width="45%" valign="top">

                <table style="width:100%; border-collapse:collapse; border:1px solid #000;">
                    <tr>
                        <th style="border:1px solid #000;">Crates</th>
                        <th style="border:1px solid #000;">B</th>
                        <th style="border:1px solid #000;">S</th>
                    </tr>

                    <tr>
                        <td style="border:1px solid #000;">Pre Balance</td>
                        <td style="border:1px solid #000;"></td>
                        <td style="border:1px solid #000;"></td>
                    </tr>

                    <tr>
                        <td style="border:1px solid #000;">Todays Sales</td>
                        <td style="border:1px solid #000;">{{ $totalCrates }}</td>
                        <td style="border:1px solid #000;"></td>
                    </tr>

                    <tr>
                        <td style="border:1px solid #000;">Balance</td>
                        <td style="border:1px solid #000;"></td>
                        <td style="border:1px solid #000;"></td>
                    </tr>
                </table>

            </td>

            {{-- Right Side --}}
            <td width="55%" valign="top">

                <table>
                    <tr>
                        <td class="section-total">Prev. Balance</td>
                        <td style="text-align:center;">: ₹</td>
                        <td style="text-align:right;">
                            {{ number_format($previousBalance,2) }}
                        </td>
                    </tr>

                    <tr>
                        <td class="section-total">Bill Amount</td>
                        <td style="text-align:center;">: ₹</td>
                        <td style="text-align:right;">
                            {{ number_format($sale->net_amount,2) }}
                        </td>
                    </tr>

                    <tr>
                        <td class="section-total">
                            <strong>Net Balance</strong>
                        </td>

                        <td style="text-align:center;">
                            <strong>: ₹</strong>
                        </td>

                        <td style="text-align:right;">
                            <strong>
                                {{ number_format($ledgerBalance,2) }}
                            </strong>
                        </td>
                    </tr>
                </table>

            </td>

            </tr>
        </table>
        <br>
        <pre style="font-size:11px; font-family:Arial,sans-serif; margin:0;">
        Bank Details                   : <strong>For SEASONS FRUITS TRADERS</strong>
        Bank Name / A/c No       : <strong>HDFC BANK / 50200028709842</strong>
        Branch / IFSC Code       : 
        </pre>
        <br>

        <div style="text-align:right;">
            <strong style=" color: darkred;font-size: small;">
                For Seasons Fruits Traders
            </strong>
        </div>

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