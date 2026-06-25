<!DOCTYPE html>
<html>
<head>
    <title>Customer Due Report</title>

    <style>
        body{
            font-family: Arial, sans-serif;
            font-size:14px;
        }

        h2{
            text-align:center;
            margin-bottom:20px;
        }

        table{
            width:100%;
            border-collapse:collapse;
        }

        table, th, td{
            border:1px solid #000;
        }

        th, td{
            padding:8px;
        }

        th{
            text-align:center;
        }

        .text-end{
            text-align:right;
        }

        @media print{
            .no-print{
                display:none;
            }
        }
    </style>
</head>
<body>

    <div class="no-print" style="margin-bottom:15px;">
        <button onclick="window.print()">Print</button>
    </div>

    <h2>Customer Amount To Receive Report</h2>

    <table>
        <thead>
            <tr>
                <th width="10%">S.No</th>
                <th width="60%">Customer Name</th>
                <th width="30%">Amount To Receive</th>
            </tr>
        </thead>

        <tbody>

            @php $total = 0; @endphp

            @foreach($customers as $key => $customer)

                @php $total += $customer->due_amount; @endphp

                <tr>
                    <td align="center">{{ $key + 1 }}</td>
                    <td>{{ $customer->name }}</td>
                    <td class="text-end">
                        {{ number_format($customer->due_amount, 2) }}
                    </td>
                </tr>

            @endforeach

            <tr>
                <th colspan="2" class="text-end">
                    Total
                </th>
                <th class="text-end">
                    {{ number_format($total, 2) }}
                </th>
            </tr>

        </tbody>
    </table>

</body>
</html>