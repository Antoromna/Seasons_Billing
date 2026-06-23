<!DOCTYPE html>
<html>
<head>
    <title>Products List</title>

    <style>
        body{
            font-family: Arial, sans-serif;
            padding:20px;
        }

        h2{
            text-align:center;
            margin-bottom:20px;
        }

        table{
            width:100%;
            border-collapse:collapse;
        }

        th,td{
            border:1px solid #000;
            padding:8px;
            text-align:left;
        }

        th{
            background:#f2f2f2;
        }
    </style>
</head>
<body onload="window.print()">

<h2>Products List</h2>

<table>
    <thead>
        <tr>
            <th>S.No</th>
            <th>Name</th>
            <th>Unit</th>
            <th>Tray Required</th>
            <th>Status</th>
        </tr>
    </thead>

    <tbody>
        @foreach($products as $key => $product)
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $product->name }}</td>
            <td>{{ ucfirst($product->unit) }}</td>
            <td>{{ $product->tray_required ? 'Yes' : 'No' }}</td>
            <td>{{ $product->status ? 'Active' : 'Inactive' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>