@extends('layouts.auth')

@section('content')

<div class="container-fluid px-3 px-lg-4 py-4">

    <!-- Executive Page Header Card -->
    <div class="index-header-card d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="index-header-icon">
                <i class="bi bi-journal-text"></i>
            </div>
            <div>
                <h1 class="h3 text-white mb-1 fw-bold">Customer Ledger</h1>
                <p class="text-white-50 mb-0 small">Track customer dues, pending opening balances, and payment receipts.</p>
            </div>
        </div>
        <div class="d-flex flex-wrap align-items-center gap-2">
            <button type="button" class="index-action-btn" data-bs-toggle="modal" data-bs-target="#pendingModal" style="background: rgba(245, 158, 11, 0.2); border-color: rgba(245, 158, 11, 0.4);">
                <i class="bi bi-wallet2"></i> Opening Balance
            </button>
            <button type="button" class="index-action-btn" data-bs-toggle="modal" data-bs-target="#paymentModal" style="background: rgba(16, 185, 129, 0.2); border-color: rgba(16, 185, 129, 0.4);">
                <i class="bi bi-cash"></i> Amount Received
            </button>
            <a href="{{ route('customer-ledger.print') }}" target="_blank" class="index-action-btn">
                <i class="bi bi-printer"></i> Print Summary
            </a>
        </div>
    </div>

    <!-- Table List Box Panel -->
    <section class="panel-custom mt-3">
        <div class="panel-header-custom">
            <div class="panel-header-title">
                <i class="bi bi-table text-primary fs-5"></i>
                <span>Customer Balances & Dues</span>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill fs-7 ms-2">
                    {{ count($customers) }} Accounts
                </span>
            </div>

            <div class="d-flex align-items-center gap-2">
                <form method="GET" action="{{ route('customer-ledger.index') }}" class="d-flex align-items-center gap-1">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Search customer..." value="{{ request('search') }}" style="min-width: 220px; border-radius: 8px;">
                    <button class="btn btn-primary btn-sm rounded-2" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </form>
            </div>
        </div>

        <div class="table-responsive">

            <table class="table align-middle mb-0">

                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Date</th>
                        <th>Customer Name</th>
                        <th class="text-end">Amount To Receive</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($customers as $key => $customer)

                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $customer->created_at->format('d-m-Y') }}</td>
                       <td>
                            <a href="javascript:void(0)"
                            class="customerLedger"
                            data-id="{{ $customer->id }}"
                            data-name="{{ $customer->name }}"
                            data-opening="{{ $customer->opening_balance }}">
                                {{ $customer->name }}
                            </a>
                        </td>

                        <td class="text-end">
                            ₹ {{ number_format($customer->due_amount, 2) }}
                        </td>
                    </tr>

                    @empty

                    <tr>
                        <td colspan="5" class="text-center py-4">
                            No Customers Found
                        </td>
                    </tr>

                    @endforelse
                    </tbody>

            </table>

        </div>

    </section>

</div>
<div class="modal fade" id="ledgerModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    Customer Ledger -
                    <span id="customer_name"></span>
                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"></button>
            </div>
            <div class="row w-100">
                <div class="col-md-3">
                    <input type="date" id="from_date" class="form-control">
                </div>

                <div class="col-md-3">
                    <input type="date" id="to_date" class="form-control">
                </div>

                <div class="col-md-3">         
                    <button type="button"
                            id="printSimpleLedgerBtn"
                            class="btn btn-success">
                        Print
                    </button>
                    <button type="button"
                            id="printLedgerBtn"
                            class="btn btn-primary">
                        Print Items
                    </button>
                </div>
            </div>

            <div class="modal-body">

                <div class="table-responsive">

                    <table class="table table-bordered">

                        <thead>
                            <tr>
                                <th>Bill ID</th>
                                <th>Date</th>
                                <th>To Be Received</th>
                                <th>Amount Received</th>
                                <th>Ledger Balance</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>

                        <tbody id="ledgerBody">

                        </tbody>

                    </table>

                </div>

            </div>

        </div>
    </div>
</div>
{{-- opening balance --}}
<div class="modal fade" id="pendingModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <form method="POST"
                  action="{{ route('customers.opening-balance.store') }}">

                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">
                        Opening Balance
                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">
                            Customer
                        </label>

                        <select name="customer_id"
                                class="form-select searchable-select"
                                required>

                            <option value="">
                                Select Customer
                            </option>

                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">
                                    {{ $customer->name }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    <div class="mb-3">
                            <label class="form-label">
                                 Date
                            </label>

                            <input type="date"
                                name="entry_date"
                                class="form-control"
                                value="{{ date('Y-m-d') }}"
                                required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">
                            Opening Balance
                        </label>

                        <input type="number"
                               name="opening_balance"
                               class="form-control"
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            Remarks
                        </label>

                        <textarea name="remarks"
                                  rows="3"
                                  class="form-control"></textarea>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">
                        Close
                    </button>

                    <button type="submit"
                            class="btn btn-warning">
                        Save
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
<div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <form action="{{ route('customer-payment.store') }}"
                  method="POST">

                @csrf

                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">
                        Receive Payment
                    </h5>

                    <button type="button"
                            class="btn-close btn-close-white"
                            data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <input type="hidden"
                           name="customer_id"
                           id="payment_customer_id">

                    <div class="mb-3">

                        <label class="form-label">
                            Payment Type
                        </label>

                        <select name="payment_type"
                                id="payment_type"
                                class="form-select">

                            <option value="general">
                                General Payment
                            </option>

                            <option value="bill">
                                Bill Payment
                            </option>

                        </select>

                    </div>
                     <div class="mb-3">
                        <label class="form-label">
                            Customer
                        </label>

                        <select name="customer_id"
                                id="customer_id_payment"
                                class="form-select"
                                required>

                            <option value="">
                                Select Customer
                            </option>

                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">
                                    {{ $customer->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3 d-none" id="sale_div">

                        <label class="form-label">
                            Select Bill
                        </label>

                        <select name="sale_id"
                                id="sale_id"
                                class="form-select">

                            <option value="">
                                Select Bill
                            </option>

                        </select>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Amount
                        </label>

                        <input type="number"
                               name="amount"
                               class="form-control"
                               required>

                    </div>
                    <div class="mb-3 d-none" id="discount_div">

                        <label class="form-label">
                            Discount Amount
                        </label>

                        <input type="number"
                            name="discount_amount"
                            id="discount_amount"
                            class="form-control"
                            value="0"
                            min="0"
                            step="0.01">

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Payment Date
                        </label>

                        <input type="date"
                               name="payment_date"
                               value="{{ date('Y-m-d') }}"
                               class="form-control">

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Remarks
                        </label>

                        <textarea name="remarks"
                                  class="form-control"></textarea>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="submit"
                            class="btn btn-success">
                        Save Payment
                    </button>

                </div>

            </form>

        </div>
    </div>
</div>
<script>

document.getElementById('printSimpleLedgerBtn')
    .addEventListener('click', function () {

        const customerName =
            document.getElementById('customer_name').textContent.trim();

        const table = document.querySelector(
            '#ledgerModal .modal-body table'
        ).cloneNode(true);

        const printWindow = window.open(
            '',
            '_blank',
            'width=1000,height=700'
        );

        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>

                <title>Customer Ledger - ${customerName}</title>

                <style>

                    @page {
                        size: A4 landscape;
                        margin: 15mm;
                    }

                    body {
                        font-family: Arial, sans-serif;
                        color: #000;
                        margin: 0;
                        padding: 20px;
                    }

                    h2 {
                        margin-bottom: 20px;
                        font-size: 20px;
                    }

                    table {
                        width: 100%;
                        border-collapse: collapse;
                    }

                    th,
                    td {
                        border: 1px solid #000;
                        padding: 8px;
                        text-align: left;
                        color: #000;
                    }

                    th {
                        font-weight: bold;
                        background: #f2f2f2;
                    }

                    a {
                        color: #000;
                        text-decoration: none;
                    }

                </style>

            </head>

            <body>

                <h2>
                    Customer Ledger - ${customerName}
                </h2>

                ${table.outerHTML}

            </body>
            </html>
        `);

        printWindow.document.close();

        printWindow.focus();

        setTimeout(function () {
            printWindow.print();
            printWindow.close();
        }, 300);

    });

</script>
@endsection
