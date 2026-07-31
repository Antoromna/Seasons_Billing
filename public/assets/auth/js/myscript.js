//customer-print
function printCustomers() {

        let printContents = document.getElementById('printSection').innerHTML;

        let printWindow = window.open('', '', 'width=900,height=650');

        printWindow.document.write(`
            <html>
            <head>
                <title>Customers List</title>

                <style>
                    @page {
                        size: A6 portrait;
                        margin: 10mm;
                    }

                    body {
                        font-family: Arial, sans-serif;
                        font-size: 10px;
                        padding: 10px;
                    }

                    table {
                        width: 100%;
                        border-collapse: collapse;
                    }

                    table, th, td {
                        border: 1px solid #000;
                    }

                    th, td {
                        padding: 4px;
                        text-align: left;
                        word-break: break-word;
                    }

                    .btn,
                    .modal,
                    .table-search,
                    .heading-actions,
                    .panel-header .d-flex {
                        display: none !important;
                    }

                    .badge {
                        border: 1px solid #000;
                        padding: 2px 5px;
                    }
                </style>

            </head>

            <body>
                ${printContents}
            </body>

            </html>
        `);

        printWindow.document.close();

        printWindow.focus();

        setTimeout(() => {
            printWindow.print();
            printWindow.close();
        }, 500);
    }
//tray
  $('#unit').on('change', function () {

        if ($(this).val() != 'box') {

            $('#trayRequiredDiv').show();

        } else {

            $('#trayRequiredDiv').hide();

            $('input[name="tray_required"]').prop('checked', false);
        }

    });
let rowCount = 1;

// Product select
$('#productSelect').on('change', function () {

    let productId = this.tomselect.getValue();

    let selected = $('#productSelect option[value="' + productId + '"]');

    console.log(selected.data('tray-required'));

    let trayRequired = parseInt(selected.data('tray-required')) || 0;

    console.log('Tray Required:', trayRequired);

    if (trayRequired === 0) {

        $('#trayType').val('No Tray').prop('disabled', true);
        $('#trayCount').val(0).prop('disabled', true);

    } else {

        $('#trayType').prop('disabled', false);
        $('#trayCount').prop('disabled', false);

    }

    let unit = selected.data('unit') || '';

    $('#unit').val(unit);
    $('#price').val(selected.data('price') || '');

    calculateLineTotal();

    $('#quantity').focus();
});
$('#productSelect').on('focus', function () {

    if ($('#customer_id').val() == '') {

        toastr.error('Please select a customer first');

        let customerSelect = document.getElementById('customer_id');

        if (customerSelect.tomselect) {
            customerSelect.tomselect.focus();
        }

        $(this).blur();
    }
});
// Total calculate
$('#quantity, #price').on('keyup change', function () {

    calculateLineTotal();
});

function calculateLineTotal()
{
    let qty   = parseFloat($('#quantity').val()) || 0;

    let price = parseFloat($('#price').val()) || 0;

    let total = qty * price;

    $('#lineTotal').val(total.toFixed(2));
}

// Add product button
$('#addProductBtn').on('click', function () {

    addProductRow();
});

// Enter key add row


// Prevent form submit accidentally
$('form').on('keydown', function(e){

    if(e.key === 'Enter' &&
       !$(e.target).is('textarea')) {

        e.preventDefault();
    }
});

// Add row function
function addProductRow()
{
    let customerId = $('#customer_id').val();

    if (customerId == '') {
        toastr.error('Please select a customer');

        let customerSelect = document.getElementById('customer_id');

        if (customerSelect.tomselect) {
            customerSelect.tomselect.focus();
        } else {
            $('#customer_id').focus();
        }

        return;
    }

    let ts = document.getElementById('productSelect').tomselect;

    let productId = ts.getValue();

    if (productId == '') {
        toastr.error('Please select a product');
        ts.focus();
        return;
    }

    let selected = $('#productSelect').find(':selected');
    let productName = selected.data('name');
    let unit = selected.data('unit');
    let trayRequired = parseInt(selected.data('tray-required')) || 0;

    let quantity    = $('#quantity').val();

    let trayType    = $('#trayType').val();

    let trayCount   = $('#trayCount').val();
        // Tray validation
        if (trayRequired === 1) {

            trayType = $('#trayType').val() || 'No Tray';
            trayCount = parseInt($('#trayCount').val()) || 0;
            // Validate only when a tray type other than "No Tray" is selected
            if (trayType !== 'No Tray' && trayCount <= 0) {
                toastr.error('Please enter tray quantity');
                $('#trayCount').focus();
                return;
            }

        } else {

            trayType = 'No Tray';
            trayCount = 0;
        }
    let price       = $('#price').val();

    if(quantity == '' || quantity <= 0) {
        toastr.error('Enter valid quantity');
        $('#quantity').focus();
        return;
    }

    if(price == '' || price <= 0) {
        toastr.error('Enter valid price');
        $('#price').focus();
        return;
    }

    let total = parseFloat(quantity) * parseFloat(price);

    let row = `
    <tr>

        <td class="row-no">${rowCount}</td>

        

        <td>
            ${productName}

            <input type="hidden"
                name="products[${rowCount}][product]"
                value="${productName}">

            <input type="hidden"
                name="products[${rowCount}][product_id]"
                value="${productId}">
        </td>

        <td>
            ${unit}

            <input type="hidden"
                   name="products[${rowCount}][unit]"
                   value="${unit}">
        </td>

        <td>
            ${quantity}

            <input type="hidden"
                   name="products[${rowCount}][quantity]"
                   value="${quantity}">
        </td>

        <td>
            ${trayType}

            <input type="hidden"
                   name="products[${rowCount}][tray]"
                   value="${trayType}">
        </td>

        <td>
            ${trayCount}

            <input type="hidden"
                   name="products[${rowCount}][tray_qty]"
                   value="${trayCount}">
        </td>

        <td>
            ${price}

            <input type="hidden"
                   name="products[${rowCount}][price]"
                   value="${price}">
        </td>

        <td class="row-total">
            ${total.toFixed(2)}

            <input type="hidden"
                   name="products[${rowCount}][total]"
                   value="${total}">
        </td>

       <td class="text-center">
    <button type="button"
            class="btn btn-sm text-danger removeRow"
            title="Delete">
        <i class="bi bi-x-circle-fill"></i>
    </button>
</td>

    </tr>
    `;

    $('#saleTable tbody').append(row);

    rowCount++;

    calculateTotals();

    clearEntry();
}

// Clear fields
function clearEntry()
{
    let ts = document.getElementById('productSelect').tomselect;

    ts.clear();

    $('#unit').val('');
    $('#quantity').val(1);

    $('#trayType')
        .val('No Tray')
        .prop('disabled', false);

    $('#trayCount')
        .val(0)
        .prop('disabled', false);

    $('#price').val('');
    $('#lineTotal').val('');

    ts.focus();
}

// Remove row
$(document).on('click', '.removeRow', function () {

    $(this).closest('tr').remove();

    $('#saleTable tbody tr').each(function(index){

        $(this)
            .find('.row-no')
            .text(index + 1);

    });

    calculateTotals();
});

// Net total
function calculateTotals()
{
    let netAmount = 0;

    $('#saleTable tbody tr').each(function () {

        let totalField = $(this).find('.row-total-input');

        if (totalField.length) {

            // Edit page
            netAmount += parseFloat(totalField.val()) || 0;

        } else {

            // Create page
            netAmount += parseFloat(
                $(this).find('.row-total input[type="hidden"]').val()
            ) || 0;
        }

    });

    $('#netAmount').text(netAmount.toFixed(2));

    calculateBalance();
}

// Balance
$('#paidAmount, #previousBalance').on('keyup change', function () {

    calculateBalance();
});

function calculateBalance()
{
    let netAmount = parseFloat($('#netAmount').text()) || 0;

    let previous  = parseFloat($('#previousBalance').val()) || 0;

    let paid      = parseFloat($('#paidAmount').val()) || 0;

    let balance = (netAmount + previous) - paid;

    $('#balance').text(balance.toFixed(2));
}
function moveOnEnter(current, next)
{
    $(document).on('keydown', current, function (e) {

        if (e.key !== 'Enter') {
            return;
        }

        e.preventDefault();

        if (typeof next === 'function') {
            next();
        } else {
            $(next).focus();
        }
    });
}
moveOnEnter('#quantity', function () {

    if ($('#trayType').prop('disabled')) {
        $('#price').focus();
    } else {
        $('#trayType').focus();
    }
});

moveOnEnter('#trayType', '#trayCount');

moveOnEnter('#trayCount', '#price');

moveOnEnter('#price', function () {
    addProductRow();
});
//customer-ledger
let selectedCustomerId = '';
$(document).on('click', '.customerLedger', function () {
console.log($(this).data());
    selectedCustomerId = $(this).data('id');
    let customerId = $(this).data('id');
    let customerName = $(this).data('name');
    // let openingBalance = $(this).data('opening');
    $('#customer_name').text(customerName);

    let openingBalance = $(this).data('opening') || 0;

    $('#customer_opening_balance').text(
        Number(openingBalance).toFixed(2)
    );

    $.get('/customer-ledger/' + customerId + '/bills', function (response) {

        let html = '';

        $.each(response, function (i, row) {
            let updateIcon = '';

            if (row.is_updated) {
                updateIcon = `
                    <i class="bi bi-arrow-repeat text-warning ms-1"
                    title="Last updated: ${row.updated_at}">
                    </i>
                `;
            }

            html += `
                <tr>
                    <td>
                        <a href="javascript:void(0)"
                        class="viewBill"
                        data-id="${row.sale_id}">
                            ${row.bill_id}
                        </a>
                        ${updateIcon}
                    </td>
                    <td>${row.date} </td>
                    <td>₹ ${row.pending}</td>
                    <td>₹ ${row.received}</td>
                    <td>₹ ${row.ledger_balance}</td>
                    <td>${row.remarks ?? '-'}</td>
                </tr>
            `;
        });

        $('#ledgerBody').html(html);

        $('#ledgerModal').modal('show');
    });
});
//open bal
$(document).on('click', '.pendingBtn', function () {

    $('#pending_customer_id').val($(this).data('id'));

    $('#opening_balance').val($(this).data('balance'));

    $('#pendingModal').modal('show');
});
$('#payment_type').on('change', function () {

    if ($(this).val() === 'bill') {

        $('#sale_div').removeClass('d-none');

    } else {

        $('#sale_div').addClass('d-none');

        $('#sale_id').val('');
    }
});
$(document).on('click', '.paymentBtn', function () {

    let customerId = $(this).data('id');

    $('#payment_customer_id').val(customerId);

    $.get('/customer/' + customerId + '/sales', function (sales) {

        let options = '<option value="">Select Bill</option>';

        $.each(sales, function (i, sale) {

            let pending = sale.net_amount - sale.paid_amount;

            options += `
                <option value="${sale.id}">
                    ${sale.bill_no} - Pending ₹${pending}
                </option>
            `;
        });

        $('#sale_id').html(options);

        $('#paymentModal').modal('show');
    });
});
$('#customer_id_payment').on('change', function () {

    let customerId = $(this).val();

    $('#sale_id').html('<option value="">Select Bill</option>');

    if (!customerId) {
        return;
    }

    $.get('/customer/' + customerId + '/sales', function (sales) {

        let options = '<option value="">Select Bill</option>';

        $.each(sales, function (i, sale) {

            let pending = sale.net_amount - sale.paid_amount;

            options += `
                <option value="${sale.id}">
                    ${sale.bill_no} - Pending ₹${pending}
                </option>
            `;
        });

        $('#sale_id').html(options);
    });
});
$(document).on('click', '.viewBill', function () {

    let saleId = $(this).data('id');

    window.open('/sales/' + saleId + '/print', '_blank');
});
//edit sale load
$(document).ready(function () {

    calculateTotals();

    rowCount =
        $('#saleTable tbody tr').length + 1;
});
$(document).on('keyup change', '.qty, .price', function () {

    let row = $(this).closest('tr');

    let qty = parseFloat(
        row.find('.qty').val()
    ) || 0;

    let price = parseFloat(
        row.find('.price').val()
    ) || 0;

    let total = qty * price;

    row.find('.row-total-input')
       .val(total.toFixed(2));

    calculateTotals();
});
//pagination
if ($.fn.DataTable) {
    $('.DataTable').DataTable({
        paging: true
    });
}

function printTable() {
    let table = $('.DataTable').DataTable();
    table.page.len(-1).draw(); // show all rows
    window.print();
    table.page.len(10).draw(); // reset
}
//bulk print
$('#selectAll').on('change', function () {

    $('.sale-checkbox').prop(
        'checked',
        $(this).prop('checked')
    );

});

$('#bulkPrintBtn').on('click', function () {

    let ids = [];

    $('.sale-checkbox:checked').each(function () {

        ids.push($(this).val());

    });

    if (ids.length === 0) {

        toastr.error('Select at least one sale');

        return;
    }

    window.open(
        '/sales/bulk-print?ids=' + ids.join(','),
        '_blank'
    );
});
//product-wise report

function printProductReport() {

    const printContents = document.getElementById('printSection').innerHTML;

    const fromDateVal = document.querySelector('[name="from_date"]').value;
    const toDateVal   = document.querySelector('[name="to_date"]').value;

    const customer = document.querySelector('[name="customer_id"] option:checked')?.text || 'All Customers';
    const product  = document.querySelector('[name="product_id"] option:checked')?.text || 'All Products';
   
    let dateLine = '';

    if (fromDateVal || toDateVal) {
        dateLine = `
            <div>
                ${fromDateVal ? `<b>From:</b> ${fromDateVal}` : ''}
                ${fromDateVal && toDateVal ? ' &nbsp;&nbsp; ' : ''}
                ${toDateVal ? `<b>To:</b> ${toDateVal}` : ''}
            </div>
        `;
    }


    const printWindow = window.open('', '', 'width=1200,height=700');

    printWindow.document.write(`
        <html>
        <head>
            <title>Product Wise Report</title>
            <style>
                body { font-family: Arial; padding: 20px; font-size: 12px; }

                h2 { text-align: center; margin-bottom: 10px; }

                .info {
                    margin-bottom: 15px;
                    font-size: 13px;
                    line-height: 1.6;
                }

                table {
                    width: 100%;
                    border-collapse: collapse;
                }

                table, th, td {
                    border: 1px solid #000;
                }

                th, td {
                    padding: 6px;
                    text-align: left;
                }

                .row {
                    display: flex;
                    justify-content: space-between;
                }
            </style>
        </head>

        <body>

            <h2>Product Wise Sales Report</h2>

            <div class="info">
                 ${dateLine}
                <div><b>Customer:</b> ${customer}</div>
                <div><b>Product:</b> ${product}</div>
                <div><b>Total Amount:</b> ₹ ${totalAmount}</div>
            </div>

            ${printContents}

        </body>
        </html>
    `);

    printWindow.document.close();

    setTimeout(() => {
        printWindow.print();
        printWindow.close();
    }, 500);
}
//sale-previous balance
$(document).ready(function () {

    $('#customer_id').change(function () {

        console.log('customer changed');

        let customerId = $(this).val();

        if (!customerId) {
            $('#previousBalance').val(0);
            calculateBalance();
            return;
        }

        $.get('/customers/' + customerId + '/balance', function(response) {

            $('#previousBalance').val(response.balance);

            calculateBalance();
        });
    });

});

$(document).on('click', '.trayLedger', function () {

    let customerId = $(this).data('id');
    let customerName = $(this).data('name');

    $('#tray_customer_name').text(customerName);

    $.get('/tray-returns/' + customerId + '/ledger', function (response) {

        let html = '';

        $.each(response, function (i, row) {

            html += `
                <tr>
                    <td>${row.date}</td>
                    <td>${row.reference}</td>
                   <td>${row.big_given} / ${row.small_given}</td>

                    <td>${row.big_returned} / ${row.small_returned}</td>

                    <td>
                        <span class="badge bg-danger">
                            ${row.big_balance} / ${row.small_balance}
                        </span>
                    </td>
                </tr>
            `;
        });

        $('#trayLedgerBody').html(html);

        $('#trayLedgerModal').modal('show');
    });
});
$(document).on('click', '#printLedgerBtn', function () {

    let fromDate = $('#from_date').val() || '';
    let toDate   = $('#to_date').val() || '';

    let url =
        '/customer-ledger/' + selectedCustomerId +
        '/print?from_date=' + fromDate +
        '&to_date=' + toDate;

    window.open(url, '_blank');
});
$('#payment_type').change(function () {

    if ($(this).val() == 'bill') {

        $('#sale_div').removeClass('d-none');
        $('#discount_div').removeClass('d-none');

    } else {

        $('#sale_div').addClass('d-none');
        $('#discount_div').addClass('d-none');

        $('#discount_amount').val(0);
    }

});