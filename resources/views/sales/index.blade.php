@extends('layouts.auth')

@section('content')

<div class="container-fluid px-3 px-lg-4 py-4">

    <!-- Executive Page Header Card -->
    <div class="index-header-card mb-4">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-3">
            <div class="d-flex align-items-center gap-3">
                <div class="index-header-icon">
                    <i class="bi bi-cart-check-fill"></i>
                </div>
                <div>
                    <h1 class="h3 text-white mb-1 fw-bold">Sales Register</h1>
                    <p class="text-white-50 mb-0 small">Filter, manage, print, and track all cash and credit sales transactions.</p>
                </div>
            </div>
            <div class="d-flex flex-wrap align-items-center gap-2">
                <a class="index-action-btn index-action-btn-primary" href="{{ route('sales.create') }}">
                    <i class="bi bi-plus-circle"></i> DC Sale
                </a>
                <a class="index-action-btn index-action-btn-primary" href="{{ route('cash_sales.create') }}" style="background: #059669; border-color: #10b981;">
                    <i class="bi bi-plus-circle"></i> Cash Sale
                </a>
            </div>
        </div>

        <!-- Integrated Filter Form -->
        <form method="GET" action="{{ route('sales.index') }}" class="pt-2 border-top border-white border-opacity-10">
            <div class="row g-2 align-items-end">
                <div class="col-6 col-md-3">
                    <label class="form-label text-white-50 fs-7 mb-1">From Date</label>
                    <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control form-control-sm bg-white bg-opacity-10 text-white border-white border-opacity-25">
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label text-white-50 fs-7 mb-1">To Date</label>
                    <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control form-control-sm bg-white bg-opacity-10 text-white border-white border-opacity-25">
                </div>
                <div class="col-12 col-md-4">
                    <label class="form-label text-white-50 fs-7 mb-1">Customer Filter</label>
                    <select name="customer_id" class="form-select form-select-sm bg-white bg-opacity-10 text-white border-white border-opacity-25">
                        <option value="" class="text-dark">All Customers</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" class="text-dark" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-2 d-flex align-items-center gap-2">
                    <button type="submit" class="index-action-btn index-action-btn-primary w-100 justify-content-center" title="Apply Filter">
                        <i class="bi bi-search me-1"></i> Filter
                    </button>
                    <a href="{{ route('sales.index') }}" class="index-action-btn justify-content-center" title="Reset Filter">
                        <i class="bi bi-arrow-clockwise"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Table List Box Panel -->
    <section class="panel-custom mt-3">
        <div class="panel-header-custom">
            <div class="panel-header-title">
                <i class="bi bi-table text-primary fs-5"></i>
                <span>Sales Invoices</span>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill fs-7 ms-2">
                    {{ count($sales) }} Bills
                </span>
            </div>

            <div class="d-flex flex-wrap align-items-center gap-2">
                {{-- Bill Type Toggle --}}
                <div class="btn-group btn-group-sm" role="group">
                    <input type="radio" class="btn-check" name="bill_type" id="billTypeAll" value="" {{ request('bill_type', '') == '' ? 'checked' : '' }}>
                    <label class="btn btn-outline-primary" for="billTypeAll">All</label>

                    <input type="radio" class="btn-check" name="bill_type" id="billTypeCash" value="cash" {{ request('bill_type') == 'cash' ? 'checked' : '' }}>
                    <label class="btn btn-outline-success" for="billTypeCash">Cash</label>

                    <input type="radio" class="btn-check" name="bill_type" id="billTypeCredit" value="credit" {{ request('bill_type') == 'credit' ? 'checked' : '' }}>
                    <label class="btn btn-outline-warning" for="billTypeCredit">Credit</label>
                </div>

                {{-- Bulk Print --}}
                <button type="button" class="btn btn-outline-secondary btn-sm rounded-2" id="bulkPrintBtn">
                    <i class="bi bi-printer me-1"></i> Bulk Print
                </button>
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

                                @if($sale->bill_type === 'cash')

                                    <a href="{{ route('cash_sales.edit', $sale->id) }}"
                                    class="btn-action btn-action-primary"
                                    title="Edit">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>

                                @else

                                    <a href="{{ route('sales.edit', $sale->id) }}"
                                    class="btn-action btn-action-primary"
                                    title="Edit">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>

                                @endif

                                <a href="{{ route('sales.print', $sale->id) }}"
                                target="_blank"
                                class="btn-action btn-action-secondary"
                                title="Print">
                                    <i class="bi bi-printer"></i> Print
                                </a>

                                <button type="button"
                                        class="btn-action btn-action-danger"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteModal{{ $sale->id }}"
                                        title="Delete">
                                    <i class="bi bi-trash"></i> Delete
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

                                            <form action="{{ $sale->bill_type === 'cash'
                                                                ? route('cash_sales.destroy', $sale->id)
                                                                : route('sales.destroy', $sale->id) }}"
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
<script>
document.querySelectorAll('input[name="bill_type"]').forEach(function (radio) {

    radio.addEventListener('change', function () {

        const url = new URL(window.location.href);

        if (this.value) {
            url.searchParams.set('bill_type', this.value);
        } else {
            url.searchParams.delete('bill_type');
        }

        url.searchParams.delete('page');

        window.location.href = url.toString();
    });

});
</script>

@endsection