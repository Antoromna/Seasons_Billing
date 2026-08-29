@extends('layouts.auth')

@section('content')

<form method="POST"
       action="{{ route('cash_sales.update', $sale->id) }}"
      id="cashSaleEditForm">

    @csrf
    @method('PUT')

    <div class="container-fluid px-3 px-lg-4 py-3">

        {{-- PAGE HEADING --}}
        <div class="page-heading mb-3">
            <h1 class="h3 mb-1">Edit Cash Sale</h1>
        </div>


        {{-- BILL DETAILS --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-body">

                <div class="row g-3">

                    {{-- Bill No --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Bill No
                        </label>

                        <input type="text"
                               class="form-control"
                               value="{{ $sale->bill_no }}"
                               readonly>

                    </div>


                    {{-- Date --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Date
                        </label>

                        <input type="date"
                               name="bill_date"
                               class="form-control"
                               value="{{ \Carbon\Carbon::parse($sale->bill_date)->format('Y-m-d') }}"
                               required>

                    </div>


                    {{-- Payment Method --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Payment Method
                        </label>

                        <select class="form-select"
                                disabled>

                            <option selected>
                                Cash
                            </option>

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

                <div class="row g-2 align-items-end">

                    {{-- Product --}}
                    <div class="col-md-2">

                        <label class="form-label">
                            Product
                        </label>

                        <select id="productSelect"
                                class="form-select">

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
                               id="unit"
                               class="form-control"
                               readonly>

                    </div>


                    {{-- Quantity --}}
                    <div class="col-md-1">

                        <label class="form-label">
                            Qty
                        </label>

                        <input type="number"
                               id="quantity"
                               class="form-control"
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

                            <select id="trayType"
                                    class="form-select">

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
                                   id="trayCount"
                                   class="form-control"
                                   value="0"
                                   min="0"
                                   disabled>

                        </div>

                    </div>


                    {{-- Price --}}
                    <div class="col-md-1">

                        <label class="form-label">
                            Price
                        </label>

                        <input type="number"
                               id="price"
                               class="form-control"
                               min="0"
                               step="0.01">

                    </div>


                    {{-- Total --}}
                    <div class="col-md-2">

                        <label class="form-label">
                            Total
                        </label>

                        <input type="text"
                               id="lineTotal"
                               class="form-control"
                               value="0.00"
                               readonly>

                    </div>


                    {{-- Add --}}
                    <div class="col-md-2">

                        <button type="button"
                                id="addProductBtn"
                                class="btn btn-success w-100">

                            <i class="bi bi-cart-plus me-1"></i>
                            Add

                        </button>

                    </div>

                </div>

            </div>

        </div>


       
       {{-- PRODUCTS --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white">
                <h5 class="mb-0">
                    Products
                </h5>
            </div>

            <div class="card-body p-0">

                <div class="table-responsive">

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

                        <tbody id="saleItemsBody">

                            @foreach($sale->items as $key => $item)

                                <tr>

                                    {{-- S.No --}}
                                    <td class="row-no">
                                        {{ $loop->iteration }}
                                    </td>


                                    {{-- Product --}}
                                    <td>

                                        {{ $item->product }}

                                        <input type="hidden"
                                            name="products[{{ $key }}][product_id]"
                                            value="{{ $item->product_id }}">

                                        <input type="hidden"
                                            name="products[{{ $key }}][product]"
                                            value="{{ $item->product }}">

                                    </td>


                                    {{-- Unit --}}
                                    <td>

                                        {{ $item->unit }}

                                        <input type="hidden"
                                            name="products[{{ $key }}][unit]"
                                            value="{{ $item->unit }}">

                                    </td>


                                    {{-- Qty --}}
                                    <td style="width: 110px;">

                                        <input type="number"
                                            class="form-control qty"
                                            name="products[{{ $key }}][quantity]"
                                            value="{{ $item->quantity }}"
                                            min="0"
                                            step="0.001">

                                    </td>


                                    {{-- Tray --}}
                                    <td style="width: 130px;">

                                        <select class="form-select tray"
                                                name="products[{{ $key }}][tray]">

                                            <option value="No Tray"
                                                {{ $item->tray == 'No Tray' ? 'selected' : '' }}>
                                                No Tray
                                            </option>

                                            <option value="Big"
                                                {{ $item->tray == 'Big' ? 'selected' : '' }}>
                                                Big
                                            </option>

                                            <option value="Small"
                                                {{ $item->tray == 'Small' ? 'selected' : '' }}>
                                                Small
                                            </option>

                                        </select>

                                    </td>


                                    {{-- Tray Count --}}
                                    <td style="width: 110px;">

                                        <input type="number"
                                            class="form-control trayQty"
                                            name="products[{{ $key }}][tray_qty]"
                                            value="{{ $item->tray_qty }}"
                                            min="0">

                                    </td>


                                    {{-- Price --}}
                                    <td style="width: 120px;">

                                        <input type="number"
                                            class="form-control price"
                                            name="products[{{ $key }}][price]"
                                            value="{{ $item->price }}"
                                            min="0"
                                            step="0.01">

                                    </td>


                                    {{-- Total --}}
                                    <td style="width: 130px;">

                                        <input type="number"
                                            class="form-control row-total"
                                            name="products[{{ $key }}][total]"
                                            value="{{ $item->total }}"
                                            readonly>

                                    </td>


                                    {{-- Action --}}
                                    <td>

                                        <button type="button"
                                                class="btn btn-sm btn-danger removeRow">

                                            <i class="bi bi-trash"></i>

                                        </button>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>


                        <tfoot>

                            <tr>

                                <td colspan="7"
                                    class="text-end fw-bold">

                                    Bill Total

                                </td>

                                <td class="fw-bold text-success"
                                    id="billTotal">

                                    {{ number_format($sale->net_amount, 2) }}

                                </td>

                                <td></td>

                            </tr>

                        </tfoot>

                    </table>

                </div>

            </div>

        </div>


        {{-- SUMMARY --}}
        <div class="row">

            <div class="col-md-6">

                <div class="card border-0 shadow-sm">

                    <div class="card-body">

                        <label class="form-label">
                            Notes
                        </label>

                        <textarea name="notes"
                                  rows="6"
                                  class="form-control">{{ $sale->notes }}</textarea>

                    </div>

                </div>

            </div>


            <div class="col-md-6">

                <div class="card border-0 shadow-sm">

                    <div class="card-body">

                       


                        <div class="d-flex justify-content-between mb-3">

                            <strong>
                                Paid Amount
                            </strong>

                            <strong>
                                {{ number_format($sale->paid_amount, 2) }}
                            </strong>

                        </div>


                        


                        <div class="d-flex gap-2">

                            <a href="{{ route('sales.index') }}"
                               class="btn btn-secondary w-100">

                                Cancel

                            </a>


                            <button type="submit"
                                    class="btn btn-success w-100">

                                Update Cash Sale

                            </button>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</form>


<script>

document.addEventListener('DOMContentLoaded', function () {

    const productSelect = document.getElementById('productSelect');
    const unit = document.getElementById('unit');
    const quantity = document.getElementById('quantity');
    const trayType = document.getElementById('trayType');
    const trayCount = document.getElementById('trayCount');
    const price = document.getElementById('price');
    const lineTotal = document.getElementById('lineTotal');
    const addProductBtn = document.getElementById('addProductBtn');

    const tbody = document.getElementById('saleItemsBody');

    const billTotal = document.getElementById('billTotal');
    const netAmount = document.getElementById('netAmount');
    const balance = document.getElementById('balance');

    /*
    |--------------------------------------------------------------------------
    | Paid Amount
    |--------------------------------------------------------------------------
    */

    const paidAmount = {{ (float) $sale->paid_amount }};


    /*
    |--------------------------------------------------------------------------
    | Calculate New Product Entry Total
    |--------------------------------------------------------------------------
    */

    function calculateLineTotal()
    {
        const qty =
            parseFloat(quantity.value) || 0;

        const amount =
            parseFloat(price.value) || 0;

        lineTotal.value =
            (qty * amount).toFixed(2);
    }


    /*
    |--------------------------------------------------------------------------
    | Product Change
    |--------------------------------------------------------------------------
    */

    productSelect.addEventListener('change', function () {

        const selected =
            this.options[this.selectedIndex];

        if (!selected || !selected.value) {

            unit.value = '';
            price.value = '';
            lineTotal.value = '0.00';

            trayType.value = 'No Tray';
            trayType.disabled = false;

            trayCount.value = 0;
            trayCount.disabled = true;

            return;
        }

        unit.value =
            selected.dataset.unit || '';

        price.value =
            selected.dataset.price || '';

        const trayRequired =
            parseInt(selected.dataset.trayRequired) || 0;

        trayType.value = 'No Tray';
        trayCount.value = 0;
        trayCount.disabled = true;

        if (trayRequired === 1) {

            trayType.disabled = false;

        } else {

            trayType.disabled = true;
        }

        calculateLineTotal();

        quantity.focus();

    });


    /*
    |--------------------------------------------------------------------------
    | New Product Quantity / Price
    |--------------------------------------------------------------------------
    */

    quantity.addEventListener(
        'input',
        calculateLineTotal
    );

    price.addEventListener(
        'input',
        calculateLineTotal
    );


    /*
    |--------------------------------------------------------------------------
    | New Product Tray
    |--------------------------------------------------------------------------
    */

    trayType.addEventListener('change', function () {

        if (this.value === 'No Tray') {

            trayCount.value = 0;
            trayCount.disabled = true;

        } else {

            trayCount.disabled = false;
            trayCount.focus();

        }

    });


    /*
    |--------------------------------------------------------------------------
    | Add Product
    |--------------------------------------------------------------------------
    */

    addProductBtn.addEventListener('click', function () {

        const selected =
            productSelect.options[
                productSelect.selectedIndex
            ];

        if (!selected || !selected.value) {

            alert('Please select a product');

            productSelect.focus();

            return;
        }


        const qty =
            parseFloat(quantity.value) || 0;

        if (qty <= 0) {

            alert('Enter valid quantity');

            quantity.focus();

            return;
        }


        const productPrice =
            parseFloat(price.value) || 0;

        if (productPrice <= 0) {

            alert('Enter valid price');

            price.focus();

            return;
        }


        const trayRequired =
            parseInt(selected.dataset.trayRequired) || 0;


        let selectedTray =
            trayType.value;

        let selectedTrayCount =
            parseInt(trayCount.value) || 0;


        /*
        | Tray validation
        */

        if (
            trayRequired === 1 &&
            selectedTray !== 'No Tray' &&
            selectedTrayCount <= 0
        ) {

            alert('Please enter tray quantity');

            trayCount.focus();

            return;
        }


        if (trayRequired !== 1) {

            selectedTray = 'No Tray';

            selectedTrayCount = 0;
        }


        const productName =
            selected.dataset.name ||
            selected.textContent.trim();

        const productUnit =
            selected.dataset.unit || '';

        const total =
            qty * productPrice;


        /*
        |--------------------------------------------------------------------------
        | Generate unique index
        |--------------------------------------------------------------------------
        */

        const index =
            Date.now();


        const row =
            document.createElement('tr');


        row.innerHTML = `

            <td class="row-no"></td>

            <td>

                ${escapeHtml(productName)}

                <input type="hidden"
                       name="products[${index}][product_id]"
                       value="${selected.value}">

                <input type="hidden"
                       name="products[${index}][product]"
                       value="${escapeHtml(productName)}">

            </td>


            <td>

                ${escapeHtml(productUnit)}

                <input type="hidden"
                       name="products[${index}][unit]"
                       value="${escapeHtml(productUnit)}">

            </td>


            <td style="width: 110px;">

                <input type="number"
                       class="form-control qty"
                       name="products[${index}][quantity]"
                       value="${qty}"
                       min="0"
                       step="0.001">

            </td>


            <td style="width: 130px;">

                <select class="form-select tray"
                        name="products[${index}][tray]">

                    <option value="No Tray"
                            ${selectedTray === 'No Tray' ? 'selected' : ''}>
                        No Tray
                    </option>

                    <option value="Big"
                            ${selectedTray === 'Big' ? 'selected' : ''}>
                        Big
                    </option>

                    <option value="Small"
                            ${selectedTray === 'Small' ? 'selected' : ''}>
                        Small
                    </option>

                </select>

            </td>


            <td style="width: 110px;">

                <input type="number"
                       class="form-control trayQty"
                       name="products[${index}][tray_qty]"
                       value="${selectedTrayCount}"
                       min="0">

            </td>


            <td style="width: 120px;">

                <input type="number"
                       class="form-control price"
                       name="products[${index}][price]"
                       value="${productPrice}"
                       min="0"
                       step="0.01">

            </td>


            <td style="width: 130px;">

                <input type="number"
                       class="form-control row-total"
                       name="products[${index}][total]"
                       value="${total.toFixed(2)}"
                       readonly>

            </td>


            <td>

                <button type="button"
                        class="btn btn-sm btn-danger removeRow">

                    <i class="bi bi-trash"></i>

                </button>

            </td>

        `;


        tbody.appendChild(row);


        renumberRows();

        updateTotals();

        clearEntry();

    });


    /*
    |--------------------------------------------------------------------------
    | Edit Existing / Added Rows
    |--------------------------------------------------------------------------
    */

    tbody.addEventListener('input', function (event) {

        if (
            event.target.classList.contains('qty') ||
            event.target.classList.contains('price')
        ) {

            const row =
                event.target.closest('tr');

            updateRowTotal(row);

            updateTotals();
        }

    });


    /*
    |--------------------------------------------------------------------------
    | Tray Change
    |--------------------------------------------------------------------------
    */

    tbody.addEventListener('change', function (event) {

        if (!event.target.classList.contains('tray')) {
            return;
        }

        const row =
            event.target.closest('tr');

        const tray =
            event.target.value;

        const trayQty =
            row.querySelector('.trayQty');


        if (tray === 'No Tray') {

            trayQty.value = 0;
        }

    });


    /*
    |--------------------------------------------------------------------------
    | Remove Product
    |--------------------------------------------------------------------------
    */

    tbody.addEventListener('click', function (event) {

        const button =
            event.target.closest('.removeRow');

        if (!button) {
            return;
        }

        button.closest('tr').remove();

        renumberRows();

        updateTotals();

    });


    /*
    |--------------------------------------------------------------------------
    | Update Row Total
    |--------------------------------------------------------------------------
    */

    function updateRowTotal(row)
    {
        const qtyInput =
            row.querySelector('.qty');

        const priceInput =
            row.querySelector('.price');

        const totalInput =
            row.querySelector('.row-total');


        const qty =
            parseFloat(qtyInput?.value) || 0;

        const price =
            parseFloat(priceInput?.value) || 0;


        const total =
            qty * price;


        if (totalInput) {

            totalInput.value =
                total.toFixed(2);
        }


        return total;
    }


    /*
    |--------------------------------------------------------------------------
    | Update All Totals
    |--------------------------------------------------------------------------
    */

    function updateTotals()
    {
        let total = 0;


        tbody.querySelectorAll('tr').forEach(function (row) {

            total += updateRowTotal(row);

        });


        /*
        | Bill Total
        */

        billTotal.textContent =
            total.toFixed(2);


        /*
        | Net Amount
        */

        netAmount.textContent =
            total.toFixed(2);


        /*
        | Balance
        */

        const newBalance =
            total - paidAmount;


        balance.textContent =
            newBalance.toFixed(2);

    }


    /*
    |--------------------------------------------------------------------------
    | Renumber Rows
    |--------------------------------------------------------------------------
    */

    function renumberRows()
    {
        tbody.querySelectorAll('tr').forEach(
            function (row, index) {

                const rowNumber =
                    row.querySelector('.row-no');

                if (rowNumber) {

                    rowNumber.textContent =
                        index + 1;
                }

            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Clear New Product Entry
    |--------------------------------------------------------------------------
    */

    function clearEntry()
    {
        productSelect.value = '';

        unit.value = '';

        quantity.value = 1;

        trayType.value = 'No Tray';
        trayType.disabled = false;

        trayCount.value = 0;
        trayCount.disabled = true;

        price.value = '';

        lineTotal.value = '0.00';
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
| Form Submit
|--------------------------------------------------------------------------
*/

const form = document.getElementById('cashSaleEditForm');

form.addEventListener('submit', function (event) {

    const rows = tbody.querySelectorAll('tr');

    if (rows.length === 0) {

        event.preventDefault();

        alert('Please add at least one product.');

        return;
    }

    // Update all row totals before submitting
    updateTotals();

});
    

/*
|--------------------------------------------------------------------------
| Initial Calculation
|--------------------------------------------------------------------------
*/

renumberRows();

updateTotals();


});

</script>

@endsection