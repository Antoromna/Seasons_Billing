@extends('layouts.auth')

@section('content')

<div class="container-fluid px-3 px-lg-4 py-3">

    {{-- Page Heading --}}
    

    <form action="{{ route('sales.store') }}"
          method="POST">

        @csrf

        {{-- BILL DETAILS --}}
        <div class="card border-0 shadow-sm mb-4 product_entry_body">


            <div class="card-body">

                <div class="row g-3">

                    {{-- Bill No --}}
                    <div class="col-md-3">

                        <label class="form-label">
                            Bill No
                        </label>

                        <input type="text"
                               name="bill_no"
                               value="{{ $billNo }}"
                               class="form-control"
                               readonly>

                    </div>

                    {{-- Date --}}
                    <div class="col-md-3">

                        <label class="form-label">
                            Date
                        </label>

                        <input type="date"
                               name="bill_date"
                               value="{{ date('Y-m-d') }}"
                               class="form-control">

                    </div>

                    {{-- Bill Type --}}
                    <div class="col-md-3">

                        <label class="form-label">
                            Bill Type
                        </label>

                        <select name="bill_type"
                                class="form-select">
                            <option value="credit">
                                Credit
                            </option>
                            <option value="cash">
                                Cash
                            </option>

                            

                        </select>

                    </div>

                    {{-- Customer --}}
                    <div class="col-md-3">
                        <label class="form-label">
                            Customer
                        </label>

                        <select name="customer_id" id="customer_id" class="form-select searchable-select">
                        <option value="">Select Customer</option>

                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}">
                                {{ $customer->name }}
                            </option>
                        @endforeach
                        </select>
                    </div>

                </div>

            </div>

        </div>

        {{-- PRODUCT ENTRY --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    Product Entry
                </h5>

            </div>


            <div class="card-body">

                <div class="row g-2 align-items-end product_entry_body">

                
                    {{-- Product --}}
                    <div class="col-md-2">
                    <label class="form-label">
                        Product
                    </label>

                    <select id="productSelect" class="form-select searchable-select">
                        <option value="">Select</option>

                        @foreach($products as $product)
                            <option value="{{ $product->id }}"
                                    data-name="{{ $product->name }}"
                                    data-unit="{{ $product->unit }}"
                                    data-price="{{ $product->selling_price }}"
                                    data-tray-required="{{ $product->tray_required }}">
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                    {{-- Unit --}}
                    <div class="col-md-1">

                        <label class="form-label">
                            Unit
                        </label>

                        <input type="text"
                            id="unit"
                            class="form-control"
                            readonly>

                    </div>

                    {{-- Qty --}}
                    <div class="col-md-1">

                        <label class="form-label">
                            Qty
                        </label>

                        <input type="number"
                            id="quantity"
                            class="form-control"
                            value="1">

                    </div>

                    {{-- Tray --}}
                   <div class="col-md-3">
                        <label class="form-label">
                            Tray Details
                        </label>

                        <div class="input-group">
                            <select id="trayType" class="form-select">
                                <option value="No Tray">No Tray</option>
                                <option value="Big">Big</option>
                                <option value="Small">Small</option>
                            </select>

                            <input type="number"
                                id="trayCount"
                                class="form-control"
                                value="0"
                                min="0"
                                placeholder="Count">
                        </div>
                    </div>

                    {{-- Price --}}
                    <div class="col-md-1">

                        <label class="form-label">
                            Price
                        </label>

                        <input type="number"
                            id="price"
                            class="form-control">

                    </div>

                    {{-- Total --}}
                    <div class="col-md-2">

                        <label class="form-label">
                            Total
                        </label>

                        <input type="text"
                            id="lineTotal"
                            class="form-control"
                            readonly>

                    </div>
                    <div class="col-md-1">
                        <button type="button"
                                id="addProductBtn"
                                class="btn btn-success rounded-pill px-3 w-100 ms-5">
                            <i class="bi bi-cart-plus me-1"></i> Add
                        </button>
                    </div>

        </div>

    </div>

</div>

        {{-- PRODUCT TABLE --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    Products
                </h5>

            </div>

            <div class="card-body p-0">

                <div class="table-responsive table-scroll">
                <table class="table align-middle mb-0" id="saleTable">

                        <thead class="table-light">

                            <tr>

                                <th>S.No</th>
                                <th>Product</th>
                                <th>Unit</th>
                                <th>Qty</th>
                                <th>Tray</th>
                                <th>Tray Count</th>
                                <th>Price</th>
                                <th>Total</th>
                                <th>Action</th>

                            </tr>

                        </thead>

                        <tbody>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

        {{-- SUMMARY --}}
        <div class="row">

            <div class="col-md-6">

                <div class="card border-0 shadow-sm">

                    

                    <div class="card-body">

                        <textarea name="notes"
                                  rows="6"
                                  class="form-control" placeholder="Notes"></textarea>

                    </div>

                </div>

            </div>

            <div class="col-md-6">

                <div class="card border-0 shadow-sm">

                    

                    <div class="card-body">

                        <div class="d-flex justify-content-between mb-3">
                            <strong>Current Bill Amount</strong>
                            <strong id="netAmount">0.00</strong>
                        </div>

                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <strong class="me-3">Previous Balance</strong>

                            <input type="number"
                                name="previous_balance"
                                id="previousBalance"
                                class="form-control"
                                value="{{ $previousBalance ?? 0 }}"
                                readonly style="max-width: 150px;">
                        </div>

                        {{-- <div class="mb-3">

                            <label class="form-label">
                                Paid Amount
                            </label>

                            <input type="number"
                                   name="paid_amount"
                                   id="paidAmount"
                                   class="form-control"
                                   value="0">

                        </div> --}}

                        <div class="d-flex justify-content-between mb-3">
                            <strong>Total Balance</strong>
                            <strong id="balance">0.00</strong>
                        </div>

                        <div class="d-flex gap-2">

                            <a href="{{ route('sales.index') }}"
                            class="btn btn-secondary w-100">
                                Cancel
                            </a>

                            <button type="submit"
                                    class="btn btn-success w-100">
                                Save Sale
                            </button>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </form>

</div>

@endsection
@if(session('success'))
<div class="toast-container position-fixed top-0 end-0 p-3">
    <div class="toast show text-white bg-success border-0">
        <div class="d-flex">
            <div class="toast-body">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
            </div>

            <button type="button"
                    class="btn-close btn-close-white me-2 m-auto"
                    data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>
@endif