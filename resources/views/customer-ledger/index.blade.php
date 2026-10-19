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
                    <input type="text" name="search" class="form-control form-control-sm table-search" placeholder="Search customer..." value="{{ request('search') }}" data-table-search="customerLedgerTable" style="min-width: 220px; border-radius: 8px;">
                    <button class="btn btn-primary btn-sm rounded-2" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </form>
            </div>
        </div>

        <div class="table-responsive">

            <table class="table align-middle mb-0" id="customerLedgerTable">

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
                        <td colspan="4" class="text-center py-4">
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

                            @foreach($allCustomers ?? $customers as $customer)
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
    <div class="modal-dialog modal-dialog-centered modal-lg" style="max-width: 680px;">
        <div class="modal-content">

            <form action="{{ route('customer-payment.store') }}"
                  method="POST">

                @csrf

                <div class="modal-header py-3 px-4 bg-success text-white">
                    <h5 class="modal-title fw-bold mb-0">
                        <i class="bi bi-cash-stack me-2"></i> Receive Payment
                    </h5>

                    <button type="button"
                            class="btn-close btn-close-white"
                            data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body px-4 py-3">

                    <input type="hidden"
                           name="customer_id"
                           id="payment_customer_id">

                    <!-- Row 1: Payment Type & Customer in one row -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-white-50 mb-1 fw-semibold">
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

                        <div class="col-md-6">
                            <label class="form-label text-white-50 mb-1 fw-semibold">
                                Customer
                            </label>
                            <select name="customer_id"
                                    id="customer_id_payment"
                                    class="form-select"
                                    autofocus
                                    required>
                                <option value="">
                                    Select Customer
                                </option>
                                @foreach($allCustomers ?? $customers as $customer)
                                    <option value="{{ $customer->id }}" data-due="{{ $customer->due_amount }}">
                                        {{ $customer->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Customer Balance Display Card -->
                    <div id="customer_balance_card" class="mb-3 d-none">
                        <div class="p-2.5 px-3 rounded-3 d-flex justify-content-between align-items-center" style="background: rgba(15, 23, 42, 0.75); border: 1px solid rgba(59, 130, 246, 0.35);">
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: rgba(59, 130, 246, 0.2); color: #60a5fa;">
                                    <i class="bi bi-wallet2 fs-6"></i>
                                </div>
                                <div>
                                    <span class="text-white-50 small" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">Current Balance:</span>
                                    <span class="fw-bold fs-6 text-white ms-1" id="customer_balance_amount">₹ 0.00</span>
                                </div>
                            </div>
                            <div id="customer_balance_status">
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">Due</span>
                            </div>
                        </div>
                    </div>

                    <!-- Bill selection (shown only if Bill Payment is selected) -->
                    <div class="mb-3 d-none" id="sale_div">
                        <label class="form-label text-white-50 mb-1 fw-semibold">
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

                    <!-- Row 2: Amount & Discount Amount in one row -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-white-50 mb-1 fw-semibold">
                                Amount (₹)
                            </label>
                            <input type="number"
                                   name="amount"
                                   id="payment_amount_input"
                                   class="form-control"
                                   placeholder="Enter payment amount"
                                   step="0.01"
                                   min="0.01"
                                   required>
                        </div>

                        <div class="col-md-6" id="discount_div">
                            <label class="form-label text-white-50 mb-1 fw-semibold">
                                Discount Amount (₹)
                            </label>
                            <input type="number"
                                   name="discount_amount"
                                   id="discount_amount"
                                   class="form-control"
                                   value="0"
                                   min="0"
                                   step="0.01">
                        </div>
                    </div>

                    <!-- Live Payment Calculation Breakdown -->
                    <div id="payment_calc_breakdown" class="p-3 rounded-3 mb-3 d-none" style="background: rgba(15, 23, 42, 0.75); border: 1px solid rgba(16, 185, 129, 0.35);">
                        <div class="row g-2 mb-2" style="font-size: 13px;">
                            <div class="col-sm-6 d-flex justify-content-between align-items-center pe-sm-3 border-end" style="border-color: rgba(148, 163, 184, 0.2) !important;">
                                <span class="text-white-50"><i class="bi bi-person-badge me-1"></i> Current Balance:</span>
                                <span class="fw-semibold text-white fs-6" id="calc_current_bal_val">₹ 0.00</span>
                            </div>
                            <div class="col-sm-6 d-flex justify-content-between align-items-center ps-sm-3">
                                <span class="text-white-50"><i class="bi bi-arrow-down-circle me-1" style="color: #38bdf8 !important;"></i> Received Amount:</span>
                                <span class="fw-bold fs-6" id="calc_received_amt_val" style="color: #38bdf8 !important;">- ₹ 0.00</span>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2 d-none" id="calc_discount_row" style="font-size: 13px;">
                            <span class="text-white-50"><i class="bi bi-tag text-warning me-1"></i> Discount:</span>
                            <span class="fw-semibold text-warning fs-6" id="calc_discount_amt_val">- ₹ 0.00</span>
                        </div>
                        <div class="border-top my-2" style="border-color: rgba(148, 163, 184, 0.2) !important;"></div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-white" id="calc_remaining_label" style="font-size: 14px;">
                                <i class="bi bi-hourglass-split text-warning me-1"></i> Balance After Payment:
                            </span>
                            <div class="d-flex align-items-center gap-2">
                                <span class="fw-bold fs-5" id="calc_remaining_bal_val" style="color: #34d399;">
                                    ₹ 0.00
                                </span>
                                <span id="calc_settlement_badge">
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1">Pending Due</span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Date -->
                    <div class="mb-3">
                        <label class="form-label text-white-50 mb-1 fw-semibold">
                            Payment Date
                        </label>
                        <input type="date"
                               name="payment_date"
                               value="{{ date('Y-m-d') }}"
                               class="form-control">
                    </div>

                    <!-- Remarks in separate row as previous -->
                    <div class="mb-3">
                        <label class="form-label text-white-50 mb-1 fw-semibold">
                            Remarks
                        </label>
                        <textarea name="remarks"
                                  rows="2"
                                  class="form-control"
                                  placeholder="Optional remarks / notes"></textarea>
                    </div>

                </div>

                <div class="modal-footer py-3 px-4 border-top-0 d-flex justify-content-end gap-2">
                    <button type="button"
                            class="btn btn-secondary px-3"
                            data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit"
                            class="btn btn-success px-4 fw-semibold">
                        <i class="bi bi-check2-circle me-1"></i> Save Payment
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

    document.getElementById('paymentModal').addEventListener('shown.bs.modal', function () {
        const customerSelect = document.getElementById('customer_id_payment');
        if (customerSelect) {
            customerSelect.focus();
            if (typeof customerSelect.showPicker === 'function') {
                try {
                    customerSelect.showPicker();
                } catch (e) {}
            }
        }
    });

    $(document).on('click', '[data-bs-target="#paymentModal"]', function () {
        setTimeout(function() {
            const customerSelect = document.getElementById('customer_id_payment');
            if (customerSelect) {
                customerSelect.focus();
                if (typeof customerSelect.showPicker === 'function') {
                    try {
                        customerSelect.showPicker();
                    } catch (e) {}
                }
            }
        }, 150);
    });

</script>
@endsection
