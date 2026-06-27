<!DOCTYPE html>

<html>
<head>
    <title>Customer Ledger</title>
<style>

body{
    font-family: Arial, sans-serif;
    font-size:12px;
    margin:15px;
}

table{
    width:100%;
    border-collapse:collapse;
}

th{
    padding:8px 6px;
    text-align:left;
    color:#800000; /* maroon */
    font-weight:bold;
    border-top:1px solid #000;
    border-bottom:1px solid #000;
}

td{
    padding:8px 6px;
    vertical-align:top;
    border:none;
}

.ledger-row{
    border:none; /* remove bottom line */
}

.text-right{
    text-align:right;
}

.text-center{
    text-align:center;
}

.header-table td{
    border:none;
    padding:4px 10px;
}

.company-title{
    color:#800000;
    font-weight:bold;
}

.description{
    line-height:1.8;
    white-space:pre-wrap;
}

.no-print{
    margin-bottom:15px;
}

@media print{
    .no-print{
        display:none;
    }

    body{
        margin:5px;
    }
}

</style>

</head>
<body>

<div class="no-print">
    <button onclick="window.print()">Print</button>
</div>

<h2 class="text-center">
    Customer Account
</h2>

<table class="header-table">

    <tr>
        <td><strong>Customer :</strong> {{ $customer->name }}</td>
        <td><strong>Address :</strong> {{ $customer->address ?? '' }}</td>
    </tr>

   <tr>
        <td>
            <strong>Phone :</strong>
            {{ $customer->mobile ?? '' }}
        </td>

        <td>
            <strong>From :</strong>
            {{ request('from_date') ?: 'Beginning' }}
        </td>

        <td>
            <strong>To :</strong>
            {{ request('to_date') ?: date('Y-m-d') }}
        </td>
    </tr>

</table>

<br>

<table>

<thead>
    <tr>
        <th width="10%">Date</th>
        <th width="12%">Bill No</th>
        <th width="35%">Description</th>
        <th width="10%">Bill Amount</th>
        <th width="10%">Amount Paid</th>
        <th width="10%">Balance</th>
        <th width="13%">Remarks</th>
    </tr>
</thead>

<tbody>

@php
    $balance = 0;
@endphp

@foreach($ledger as $row)

    @php
        $balance += $row['debit'];
        $balance -= $row['credit'];
    @endphp

    <tr class="ledger-row">

        <td width="10%">
            {{ \Carbon\Carbon::parse($row['date'])->format('d/m/Y') }}
        </td>

        <td width="12%">
            {{ $row['bill_no'] }}
        </td>

        <td width="38%">

            @if($row['type'] == 'sale')

                @foreach($row['sale']->items as $item)

                    {{ $item->product }}
                    -
                    {{ number_format($item->quantity,2) }}

                    @

                    {{ number_format($item->price,2) }}

                    =

                    {{ number_format($item->total,2) }}

                    <br>

                @endforeach

            @else

                {{ $row['bill_no'] }}

            @endif

        </td>

        <td width="10%" class="text-right">
            {{ $row['debit'] > 0 ? number_format($row['debit'],2) : '0.00' }}
        </td>

        <td width="10%" class="text-right">
            {{ $row['credit'] > 0 ? number_format($row['credit'],2) : '0.00' }}
        </td>

        <td width="10%" class="text-right">
            {{ number_format($balance,2) }}
        </td>

        <td width="10%">
            {{ $row['remarks'] }}
        </td>

    </tr>

@endforeach

</tbody>
</table>

<script>
window.onload = function () {
    window.print();
};
</script>

</body>
</html>
