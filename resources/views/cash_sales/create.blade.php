@extends('layouts.auth')

@section('content')
<form method="POST"
      action="{{ route('cash_sales.store') }}"
      id="cashSaleForm">
    @csrf
<div class="container-fluid px-3 px-lg-4 py-3">

```
{{-- PAGE HEADING --}}
<div class="page-heading mb-3">
    <h1 class="h3 mb-1">Cash Sale</h1>
</div>


{{-- COMMON DETAILS --}}
<div class="card border-0 shadow-sm mb-4">

    <div class="card-body">

        <div class="row g-3">

            {{-- Date --}}
            <div class="col-md-4">

                <label class="form-label">
                    Date
                </label>

                <input type="date"
       id="saleDate"
       name="sale_date"
       class="form-control"
       value="{{ date('Y-m-d') }}">

            </div>


            {{-- Payment Method --}}
            <div class="col-md-4">

                <label class="form-label">
                    Payment Method
                </label>

                <select id="paymentMethod"
                        class="form-select">

                    <option value="cash" selected>
                        Cash
                    </option>

                </select>

            </div>

        </div>

    </div>

</div>


{{-- BILLS --}}
<div id="billsContainer"></div>


{{-- NEW BILL --}}
<div class="mb-4">

    <button type="button"
            class="btn btn-success"
            id="newBillBtn">

        <i class="bi bi-plus-circle me-1"></i>
        New Bill

    </button>

</div>


{{-- FOOTER --}}
<div class="card border-0 shadow-sm">

    <div class="card-body">

        <div class="row align-items-end g-3">

            <div class="col-md-8">

                <label class="form-label">
                    Notes
                </label>

                <textarea id="notes"
          name="notes"
          class="form-control"
          rows="3"
          placeholder="Notes"></textarea>

            </div>


            <div class="col-md-4">

                <div class="d-flex justify-content-end gap-2">

                    <button type="button"
                            class="btn btn-secondary"
                            id="cancelBtn">

                        Cancel

                    </button>

                    <button type="submit"
        class="btn btn-primary"
        id="saveBtn">

    Save All Bills

</button>

                </div>

            </div>

        </div>

    </div>

</div>
```

</div>

{{-- ========================================================= --}}
{{-- BILL TEMPLATE --}}
{{-- ========================================================= --}}

<template id="billTemplate">

```
<div class="card border-0 shadow-sm mb-4 bill-section">

    {{-- BILL HEADER --}}
    <div class="card-header bg-white d-flex justify-content-between align-items-center">

        <h5 class="mb-0 bill-number">
            Bill No :
        </h5>

        <button type="button"
                class="btn btn-sm btn-outline-danger bill-remove">

            <i class="bi bi-trash me-1"></i>
            Remove Bill

        </button>

    </div>


    {{-- PRODUCT ENTRY --}}
    <div class="card-body">

        <div class="row g-2 align-items-end">

            {{-- Product --}}
            <div class="col-md-2">

                <label class="form-label">
                    Product
                </label>

                <select class="form-select productSelect">

                    <option value="">
                        Select
                    </option>

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
                       class="form-control unit"
                       readonly>

            </div>


            {{-- Qty --}}
            <div class="col-md-1">

                <label class="form-label">
                    Qty
                </label>

                <input type="number"
                       class="form-control quantity"
                       min="0"
                       step="0.001"
                       value="1">

            </div>


            {{-- Tray --}}
            <div class="col-md-3">

                <label class="form-label">
                    Tray Details
                </label>

                <div class="input-group">

                    <select class="form-select trayType">

                        <option value="No Tray">
                            No Tray
                        </option>

                        <option value="Big">
                            Big
                        </option>

                        <option value="Small">
                            Small
                        </option>

                    </select>

                    <input type="number"
                           class="form-control trayCount"
                           value="0"
                           min="0"
                           placeholder="Count"
                           disabled>

                </div>

            </div>


            {{-- Price --}}
            <div class="col-md-1">

                <label class="form-label">
                    Price
                </label>

                <input type="number"
                       class="form-control price"
                       min="0"
                       step="0.01">

            </div>


            {{-- Total --}}
            <div class="col-md-2">

                <label class="form-label">
                    Total
                </label>

                <input type="text"
                       class="form-control lineTotal"
                       value="0.00"
                       readonly>

            </div>


            {{-- Add --}}
            <div class="col-md-2">

                <button type="button"
                        class="btn btn-success w-100 addProductBtn">

                    <i class="bi bi-cart-plus me-1"></i>
                    Add

                </button>

            </div>

        </div>

    </div>


    {{-- PRODUCTS TABLE --}}
    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table align-middle mb-0 saleTable">

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

                <tbody></tbody>

                <tfoot>

                    <tr>

                        <td colspan="7"
                            class="text-end fw-bold">

                            Bill Total

                        </td>

                        <td class="fw-bold text-success billTotal">
                            0.00
                        </td>

                        <td></td>

                    </tr>

                </tfoot>

            </table>

        </div>

    </div>

</div>
```

</template>

{{-- ========================================================= --}}
{{-- JAVASCRIPT --}}
{{-- ========================================================= --}}
</form>
<script>

document.addEventListener('DOMContentLoaded', function () {

    console.log('Cash Sale JS loaded');


    /*
    |--------------------------------------------------------------------------
    | Elements
    |--------------------------------------------------------------------------
    */

    const billsContainer = document.getElementById('billsContainer');

    const newBillBtn = document.getElementById('newBillBtn');

    const saleDate = document.getElementById('saleDate');

    const cancelBtn = document.getElementById('cancelBtn');

    const billTemplate = document.getElementById('billTemplate');


    /*
    |--------------------------------------------------------------------------
    | Default Date
    |--------------------------------------------------------------------------
    */

    function setTodayDate()
    {
        const today = new Date();

        const year = today.getFullYear();

        const month = String(today.getMonth() + 1).padStart(2, '0');

        const day = String(today.getDate()).padStart(2, '0');

        saleDate.value = year + '-' + month + '-' + day;
    }

    setTodayDate();


    /*
    |--------------------------------------------------------------------------
    | Bill Counter
    |--------------------------------------------------------------------------
    */

    let billCounter = {{ $nextBillNumber }};


    /*
    |--------------------------------------------------------------------------
    | Generate Bill Number
    |--------------------------------------------------------------------------
    */

    function generateBillNumber()
    {
        return 'CASH-' + String(billCounter).padStart(4, '0');
    }


    /*
    |--------------------------------------------------------------------------
    | Create Bill
    |--------------------------------------------------------------------------
    */

    function createBill()
    {
        console.log('Creating bill...');

        const billNumber = generateBillNumber();

        billCounter++;


        /*
        | Clone template
        */

        const fragment = billTemplate.content.cloneNode(true);


        /*
        | Get bill section
        */

        const billSection = fragment.querySelector('.bill-section');


        if (!billSection) {

            console.error('Bill template not found');

            return;
        }


        /*
        | Set bill number
        */

        billSection.dataset.billNo = billNumber;

        billSection.querySelector('.bill-number').textContent =
            'Bill No : ' + billNumber;


        /*
        | Add bill to page
        */

        billsContainer.appendChild(fragment);


        /*
        | Get the actual element now inside DOM
        */

        const actualBill =
            billsContainer.lastElementChild;


        /*
        | Initialize bill
        */

        initializeBill(actualBill);


        console.log(
            'Bill created:',
            billNumber
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Initialize Bill
    |--------------------------------------------------------------------------
    */

    function initializeBill(billSection)
    {
        if (!billSection) {
            return;
        }


        const productSelect =
            billSection.querySelector('.productSelect');

        const trayType =
            billSection.querySelector('.trayType');

        const trayCount =
            billSection.querySelector('.trayCount');

        const quantity =
            billSection.querySelector('.quantity');

        const price =
            billSection.querySelector('.price');


        /*
        |--------------------------------------------------------------------------
        | Product Change
        |--------------------------------------------------------------------------
        */

        productSelect.addEventListener(
            'change',
            function ()
            {
                const selected =
                    this.options[this.selectedIndex];


                if (!selected.value) {

                    billSection.querySelector('.unit').value = '';

                    price.value = '';

                    billSection.querySelector('.lineTotal').value =
                        '0.00';

                    trayType.value = 'No Tray';

                    trayCount.value = 0;

                    trayCount.disabled = true;

                    return;
                }


                /*
                | Product details
                */

                const unit =
                    selected.dataset.unit || '';

                const productPrice =
                    selected.dataset.price || '';

                const trayRequired =
                    parseInt(
                        selected.dataset.trayRequired
                    ) || 0;


                billSection.querySelector('.unit').value =
                    unit;


                price.value =
                    productPrice;


                /*
                | Tray logic
                */

                trayType.value = 'No Tray';

                trayCount.value = 0;

                trayCount.disabled = true;


                if (trayRequired === 1) {

                    trayType.disabled = false;

                } else {

                    trayType.disabled = true;

                }


                calculateLineTotal(
                    billSection
                );


                quantity.focus();
            }
        );


        /*
        |--------------------------------------------------------------------------
        | Tray Change
        |--------------------------------------------------------------------------
        */

        trayType.addEventListener(
            'change',
            function ()
            {
                if (this.value === 'No Tray') {

                    trayCount.value = 0;

                    trayCount.disabled = true;

                } else {

                    trayCount.disabled = false;

                    trayCount.focus();

                }
            }
        );


        /*
        |--------------------------------------------------------------------------
        | Quantity
        |--------------------------------------------------------------------------
        */

        quantity.addEventListener(
            'input',
            function ()
            {
                calculateLineTotal(
                    billSection
                );
            }
        );


        /*
        |--------------------------------------------------------------------------
        | Price
        |--------------------------------------------------------------------------
        */

        price.addEventListener(
            'input',
            function ()
            {
                calculateLineTotal(
                    billSection
                );
            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Calculate Line Total
    |--------------------------------------------------------------------------
    */

    function calculateLineTotal(billSection)
    {
        const quantity =
            parseFloat(
                billSection.querySelector('.quantity').value
            ) || 0;


        const price =
            parseFloat(
                billSection.querySelector('.price').value
            ) || 0;


        const total =
            quantity * price;


        billSection.querySelector('.lineTotal').value =
            total.toFixed(2);
    }


    /*
    |--------------------------------------------------------------------------
    | Add Product
    |--------------------------------------------------------------------------
    */

    billsContainer.addEventListener(
        'click',
        function (event)
        {
            const button =
                event.target.closest('.addProductBtn');


            if (!button) {
                return;
            }


            const billSection =
                button.closest('.bill-section');


            const productSelect =
                billSection.querySelector('.productSelect');


            const selected =
                productSelect.options[
                    productSelect.selectedIndex
                ];


            /*
            | Product validation
            */

            if (!selected || !selected.value) {

                showError(
                    'Please select a product'
                );

                productSelect.focus();

                return;
            }


            /*
            | Quantity
            */

            const quantity =
                parseFloat(
                    billSection.querySelector('.quantity').value
                ) || 0;


            if (quantity <= 0) {

                showError(
                    'Enter valid quantity'
                );

                billSection.querySelector('.quantity').focus();

                return;
            }


            /*
            | Price
            */

            const price =
                parseFloat(
                    billSection.querySelector('.price').value
                ) || 0;


            if (price <= 0) {

                showError(
                    'Enter valid price'
                );

                billSection.querySelector('.price').focus();

                return;
            }


            /*
            | Tray
            */

            const trayRequired =
                parseInt(
                    selected.dataset.trayRequired
                ) || 0;


            let trayType =
                billSection.querySelector('.trayType').value;


            let trayCount =
                parseInt(
                    billSection.querySelector('.trayCount').value
                ) || 0;


            if (trayRequired === 1) {

                if (
                    trayType !== 'No Tray' &&
                    trayCount <= 0
                ) {

                    showError(
                        'Please enter tray quantity'
                    );

                    billSection
                        .querySelector('.trayCount')
                        .focus();

                    return;
                }

            } else {

                trayType = 'No Tray';

                trayCount = 0;
            }


            /*
            | Product details
            */

            const productName =
                selected.dataset.name || selected.textContent.trim();


            const unit =
                selected.dataset.unit || '';


            const total =
                quantity * price;


            /*
            | Table
            */

            const tbody =
                billSection.querySelector('.saleTable tbody');


            const rowCount =
                tbody.querySelectorAll('tr').length + 1;


            const row =
                document.createElement('tr');


            row.innerHTML = `

                <td class="row-no">
                    ${rowCount}
                </td>

                <td>

                    ${escapeHtml(productName)}

                    <input type="hidden"
                           name="bills[${billSection.dataset.billNo}][items][${rowCount}][product_id]"
                           value="${selected.value}">

                    <input type="hidden"
                           name="bills[${billSection.dataset.billNo}][items][${rowCount}][product]"
                           value="${escapeHtml(productName)}">

                </td>


                <td>

                    ${escapeHtml(unit)}

                    <input type="hidden"
                           name="bills[${billSection.dataset.billNo}][items][${rowCount}][unit]"
                           value="${escapeHtml(unit)}">

                </td>


                <td>

                    ${quantity}

                    <input type="hidden"
                           name="bills[${billSection.dataset.billNo}][items][${rowCount}][quantity]"
                           value="${quantity}">

                </td>


                <td>

                    ${escapeHtml(trayType)}

                    <input type="hidden"
                           name="bills[${billSection.dataset.billNo}][items][${rowCount}][tray]"
                           value="${escapeHtml(trayType)}">

                </td>


                <td>

                    ${trayCount}

                    <input type="hidden"
                           name="bills[${billSection.dataset.billNo}][items][${rowCount}][tray_qty]"
                           value="${trayCount}">

                </td>


                <td>

                    ${price.toFixed(2)}

                    <input type="hidden"
                           name="bills[${billSection.dataset.billNo}][items][${rowCount}][price]"
                           value="${price}">

                </td>


                <td class="row-total">

                    ${total.toFixed(2)}

                    <input type="hidden"
                        name="bills[${billSection.dataset.billNo}][items][${rowCount}][total]"
                        value="${total}">

                </td>


                <td class="text-center">

                    <button type="button"
                            class="btn btn-sm text-danger removeRow">

                        <i class="bi bi-x-circle-fill"></i>

                    </button>

                </td>

            `;


            tbody.appendChild(row);


            updateBillTotal(
                billSection
            );


            clearEntry(
                billSection
            );
        }
    );


    /*
    |--------------------------------------------------------------------------
    | Clear Product Entry
    |--------------------------------------------------------------------------
    */

    function clearEntry(billSection)
    {
        billSection.querySelector('.productSelect').value = '';

        billSection.querySelector('.unit').value = '';

        billSection.querySelector('.quantity').value = 1;

        billSection.querySelector('.trayType').value =
            'No Tray';

        billSection.querySelector('.trayType').disabled =
            false;

        billSection.querySelector('.trayCount').value = 0;

        billSection.querySelector('.trayCount').disabled =
            true;

        billSection.querySelector('.price').value = '';

        billSection.querySelector('.lineTotal').value =
            '0.00';
    }


    /*
    |--------------------------------------------------------------------------
    | Update Bill Total
    |--------------------------------------------------------------------------
    */

    function updateBillTotal(billSection)
    {
        let total = 0;


        billSection
            .querySelectorAll('.saleTable tbody tr')
            .forEach(function (row)
            {
                const hiddenTotal =
                    row.querySelector(
                        '.row-total input[type="hidden"]'
                    );


                if (hiddenTotal) {

                    total +=
                        parseFloat(hiddenTotal.value) || 0;
                }
            });


        billSection.querySelector('.billTotal').textContent =
            total.toFixed(2);
    }


    /*
    |--------------------------------------------------------------------------
    | Remove Product
    |--------------------------------------------------------------------------
    */

    billsContainer.addEventListener(
        'click',
        function (event)
        {
            const button =
                event.target.closest('.removeRow');


            if (!button) {
                return;
            }


            const billSection =
                button.closest('.bill-section');


            button.closest('tr').remove();


            billSection
                .querySelectorAll('.saleTable tbody tr')
                .forEach(function (row, index)
                {
                    row.querySelector('.row-no').textContent =
                        index + 1;
                });


            updateBillTotal(
                billSection
            );
        }
    );


    /*
    |--------------------------------------------------------------------------
    | Remove Bill
    |--------------------------------------------------------------------------
    */

    billsContainer.addEventListener(
        'click',
        function (event)
        {
            const button =
                event.target.closest('.bill-remove');


            if (!button) {
                return;
            }


            const billSection =
                button.closest('.bill-section');


            billSection.remove();
        }
    );


    /*
    |--------------------------------------------------------------------------
    | NEW BILL BUTTON
    |--------------------------------------------------------------------------
    */

    newBillBtn.addEventListener(
        'click',
        function (event)
        {
            event.preventDefault();

            console.log('New Bill clicked');

            createBill();
        }
    );


    

    /*
    |--------------------------------------------------------------------------
    | Cancel
    |--------------------------------------------------------------------------
    */

    cancelBtn.addEventListener(
        'click',
        function ()
        {
            if (
                confirm(
                    'Are you sure you want to clear this page?'
                )
            ) {

                location.reload();
            }
        }
    );


    /*
    |--------------------------------------------------------------------------
    | Error Message
    |--------------------------------------------------------------------------
    */

    function showError(message)
    {
        if (
            typeof toastr !== 'undefined'
        ) {

            toastr.error(message);

        } else {

            alert(message);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Escape HTML
    |--------------------------------------------------------------------------
    */

    function escapeHtml(value)
    {
        const div =
            document.createElement('div');

        div.textContent = value;

        return div.innerHTML;
    }


    /*
    |--------------------------------------------------------------------------
    | INITIAL BILL
    |--------------------------------------------------------------------------
    */

    createBill();

});

</script>

@endsection
