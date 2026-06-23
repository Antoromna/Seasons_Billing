@extends('layouts.auth')

@section('content')

<div class="container-fluid px-3 px-lg-4 py-4">

    <div class="page-heading">

        <div class="page-heading-copy">
            <span class="page-icon">
                <i class="bi bi-cart-check"></i>
            </span>

            <div>
                <h1 class="h3 mb-1">
                    Sales
                </h1>
            </div>
        </div>
        <form method="GET" action="{{ route('sales.index') }}">
            <div class="row">

                <div class="col-md-3">
                    <label>From Date</label>
                    <input type="date"
                        name="from_date"
                        value="{{ request('from_date') }}"
                        class="form-control">
                </div>

                <div class="col-md-3">
                    <label>To Date</label>
                    <input type="date"
                        name="to_date"
                        value="{{ request('to_date') }}"
                        class="form-control">
                </div>

                <div class="col-md-3">
                    <label>Customer</label>

                    <select name="customer_id"
                            class="form-select">

                        <option value="">All Customers</option>

                        @foreach($customers as $customer)

                            <option value="{{ $customer->id }}"
                                {{ request('customer_id') == $customer->id ? 'selected' : '' }}>

                                {{ $customer->name }}

                            </option>

                        @endforeach

                    </select>
                </div>

                <div class="col-md-3 d-flex align-items-end gap-2">

                    <button type="submit"
                            class="btn btn-primary"
                            title="Filter">
                        <i class="bi bi-search"></i>
                    </button>

                    <a href="{{ route('sales.index') }}"
                    class="btn btn-secondary"
                    title="Reset">
                        <i class="bi bi-arrow-clockwise"></i>
                    </a>

                </div>

            </div>
        </form> 

    </div>

    <section class="panel mt-3">

        <div class="panel-header">
            

            <div>
                <h2 class="h5 mb-1 section-title">
                    <i class="bi bi-table"></i>
                    <span>Sales List</span>
                </h2>
            </div>
            <div>
                <button type="button"
                        class="btn btn-primary"
                        id="bulkPrintBtn">
                    Bulk Print
                </button>
                <a class="btn btn-primary btn-bg"
                    href="{{ route('sales.create') }}">

                        <i class="bi bi-plus-circle"></i>
                        Create Sale

                </a>
            </div>

        </div>
        

        <div class="table-responsive">
            <div id="printSection">
            <table class="DataTable table table-sm align-middle mb-0">

                <thead>
                    <tr>
                        <th>
                            <input type="checkbox" id="selectAll">
                        </th>
                        <th>S.No</th>
                        <th>Bill No</th>
                        <th>Date</th>
                        <th>Customer</th>
                        <th>Bill Type</th>
                        <th>Amount</th>
                        <th>Balance</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($sales as $key => $sale)

                    <tr>
                        <td>
                            <input type="checkbox"
                                class="sale-checkbox"
                                value="{{ $sale->id }}">
                        </td>
                        <td>{{ $key + 1 }}</td>

                        <td>{{ $sale->bill_no }}</td>

                        <td>
                            {{ \Carbon\Carbon::parse($sale->bill_date)->format('d-m-Y') }}
                        </td>

                        <td>
                            {{ $sale->customer->name ?? '-' }}
                        </td>

                        <td>
                            {{ ucfirst($sale->bill_type) }}
                        </td>

                        <td>
                            ₹ {{ number_format($sale->net_amount, 2) }}
                        </td>

                        <td>
                            ₹ {{ number_format($sale->balance, 2) }}
                        </td>

                        <td class="text-end">

                           <div class="d-flex justify-content-end gap-2">

                                <a href="{{ route('sales.edit', $sale->id) }}"
                                class="btn btn-primary btn-sm"
                                title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <a href="{{ route('sales.print', $sale->id) }}"
                                target="_blank"
                                class="btn btn-secondary btn-sm"
                                title="Print">
                                    <i class="bi bi-printer"></i>
                                </a>

                                <button type="button"
                                        class="btn btn-danger btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteModal{{ $sale->id }}"
                                        title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>

                            </div>

                            {{-- Delete Modal --}}
                            <div class="modal fade"
                                 id="deleteModal{{ $sale->id }}"
                                 tabindex="-1">

                                <div class="modal-dialog modal-dialog-centered">

                                    <div class="modal-content">

                                        <div class="modal-header">

                                            <h5 class="modal-title">
                                                Delete Sale
                                            </h5>

                                            <button type="button"
                                                    class="btn-close"
                                                    data-bs-dismiss="modal"></button>

                                        </div>

                                        <div class="modal-body">

                                            Are you sure want to delete
                                            <strong>{{ $sale->bill_no }}</strong> ?

                                        </div>

                                        <div class="modal-footer">

                                            <button type="button"
                                                    class="btn btn-secondary"
                                                    data-bs-dismiss="modal">
                                                Cancel
                                            </button>

                                            <form action="{{ route('sales.destroy', $sale->id) }}"
                                                  method="POST">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="btn btn-danger">
                                                    Delete
                                                </button>

                                            </form>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="8" class="text-center py-4">
                            No Sales Found
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>
            </div>
        </div>

    </section>

</div>


@endsection