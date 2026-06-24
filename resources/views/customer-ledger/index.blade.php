@extends('layouts.auth')

@section('content')

<div class="container-fluid px-3 px-lg-4 py-4">

    <div class="heading-actions">

       <button type="button"
                class="btn btn-warning btn-sm"
                data-bs-toggle="modal"
                data-bs-target="#pendingModal">
            Opening Balance
        </button>

        <button type="button"
                class="btn btn-success btn-sm"
                data-bs-toggle="modal"
                data-bs-target="#paymentModal">
            Amount Received
        </button>

    <form method="GET" action="{{ route('customer-ledger.index') }}">
        
        

        <div class="input-group">

            <input type="text"
                   name="search"
                   class="form-control"
                   placeholder="Search Customer..."
                   value="{{ request('search') }}">

            <button class="btn btn-primary" type="submit">
                <i class="bi bi-search"></i>
            </button>

        </div>

    </form>

</div>

    <section class="panel mt-3">

        <div class="panel-header">

            <div>
                <h2 class="h5 mb-1 section-title">
                    <i class="bi bi-table"></i>
                    <span>Customers</span>
                </h2>
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
@endsection