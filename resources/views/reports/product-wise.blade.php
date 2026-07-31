@extends('layouts.auth')

@section('content')

<div class="container-fluid px-3 px-lg-4 py-4">

    <div class="page-heading">

        <div class="page-heading-copy">
            <span class="page-icon">
                <i class="bi bi-bar-chart"></i>
            </span>

            <div>
                <h1 class="h3 mb-1">
                    Product Wise Report
                </h1>
            </div>
        </div>

    </div>

    <section class="panel mt-3">

        <div class="panel-header">

            <div>
                <h2 class="h5 mb-1 section-title">
                    <i class="bi bi-funnel"></i>
                    <span>Filters</span>
                </h2>
            </div>
            

        </div>

        <form method="GET"
              action="{{ route('reports.product-wise') }}">

            <div class="row g-3">

                <div class="col-md-2">

                    <label class="form-label">
                        From Date
                    </label>

                    <input type="date"
                           name="from_date"
                           value="{{ request('from_date') }}"
                           class="form-control">

                </div>

                <div class="col-md-2">

                    <label class="form-label">
                        To Date
                    </label>

                    <input type="date"
                           name="to_date"
                           value="{{ request('to_date') }}"
                           class="form-control">

                </div>
                <div class="col-md-3">

                    <label class="form-label">
                        Customer
                    </label>

                    <select name="customer_id"
                            class="form-select">

                        <option value="">
                            All Customers
                        </option>

                        @foreach($customers as $customer)

                            <option value="{{ $customer->id }}"
                                {{ request('customer_id') == $customer->id ? 'selected' : '' }}>

                                {{ $customer->name }}

                            </option>

                        @endforeach

                    </select>

                </div>
                <div class="col-md-3">

                    <label class="form-label">
                        Product
                    </label>

                    <select name="product_id"
                            class="form-select">

                        <option value="">
                            All Products
                        </option>

                        @foreach($products as $product)

                            <option value="{{ $product->id }}"
                                {{ request('product_id') == $product->id ? 'selected' : '' }}>

                                {{ $product->name }}

                            </option>

                        @endforeach

                    </select>

                </div>

                <div class="col-md-1 d-flex align-items-end gap-2">

                    <button class="btn btn-primary">
                        <i class="bi bi-search"></i>
                    </button>

                    <a href="{{ route('reports.product-wise') }}"
                       class="btn btn-secondary">

                        <i class="bi bi-arrow-clockwise"></i>

                    </a>

                    <button
    type="button"
    class="btn btn-primary"
    onclick="printProductReport({{ json_encode($totalAmount) }})">
    <i class="bi bi-printer"></i>Print
</button>

                </div>

            </div>

        </form>

    </section>

    <section class="panel mt-3">

        <div class="panel-header">

            <div>
                <h2 class="h5 mb-1 section-title">
                    <i class="bi bi-table"></i>
                    <span>Report Details</span>
                </h2>
            </div>

            <div>

                {{-- <strong>
                    Total Amount :
                    ₹ {{ number_format($totalAmount, 2) }}
                </strong> --}}

            </div>

        </div>

        <div class="table-responsive">
            <div id="printSection">
            <table id="tableBody" class="DataTable table table-sm align-middle mb-0">

                <thead>

                    <tr>

                        <th>S.No</th>
                        <th>Date</th>
                        <th>Bill No</th>
                        <th>Customer</th>
                        <th>Product</th>
                        <th>Unit</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Total</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($items as $key => $item)

                    <tr>

                        <td>{{ $key + 1 }}</td>

                        <td>
                            {{ \Carbon\Carbon::parse($item->sale->bill_date)->format('d-m-Y') }}
                        </td>

                        <td>
                            {{ $item->sale->bill_no }}
                        </td>

                        <td>
                            {{ $item->sale->customer->name ?? '-' }}
                        </td>

                        <td>
                            {{ $item->product ?? '-' }}
                        </td>

                        <td>
                            {{ $item->unit }}
                        </td>

                        <td>
                            {{ $item->quantity }}
                        </td>

                        <td>
                            ₹ {{ number_format($item->price, 2) }}
                        </td>

                        <td>
                            ₹ {{ number_format($item->total, 2) }}
                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="9"
                            class="text-center py-4">

                            No Records Found

                        </td>

                    </tr>

                    @endforelse

                </tbody>
                <tfoot>
                    <tr class="table-light fw-bold">
                        <td colspan="6" class="text-end">
                            Total
                        </td>

                        <td>
                            {{ number_format($totalQty, 2) }}
                        </td>

                        <td>
                            ₹ {{ number_format($totalPrice, 2) }}
                        </td>

                        <td>
                            ₹ {{ number_format($totalAmount, 2) }}
                        </td>
                    </tr>
                </tfoot>

            </table>
            </div>

        </div>

    </section>

</div>
<script>
    window.totalAmount = @json($totalAmount);
</script>
@endsection