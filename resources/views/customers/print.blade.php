<!DOCTYPE html>
<html>
<head>
    <title>Customers List</title>
    <style>
        table{
            width:100%;
            border-collapse:collapse;
        }

        th,td{
            border:1px solid #000;
            padding:8px;
        }
    </style>
</head>
<body onload="window.print()">

<h2 align="center">Customers List</h2>

<table>
    <thead>
        <tr>
            <th>S.No</th>
            <th>Name</th>
            <th>Mobile</th>
            <th>Landline</th>
            <th>Email</th>
            <th>Address</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($customers as $key => $customer)
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $customer->name }}</td>
            <td>{{ $customer->mobile_no }}</td>
            <td>{{ $customer->landline }}</td>
            <td>{{ $customer->email }}</td>
            <td>{{ $customer->address }}</td>
            <td>{{ $customer->status ? 'Active' : 'Inactive' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>