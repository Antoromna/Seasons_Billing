@extends('layouts.auth')

@section('content')

<div class="container-fluid px-3 px-lg-4 py-4">

    <div class="page-heading">

        <div class="page-heading-copy">
            <span class="page-icon">
                <i class="bi bi-grid"></i>
            </span>

            <div>
                <h1 class="h3 mb-1">
                    Tray Ledger
                </h1>
            </div>
        </div>
        <div class="heading-actions">
            <button type="button"
                    class="btn btn-primary btn-sm"
                    id="printTraySummary">
                <i class="bi bi-printer"></i>
                Print
            </button>
            <a class="btn btn-primary btn-sm"
                data-bs-toggle="modal"
                data-bs-target="#trayReturnModal">

                    <i class="bi bi-plus-circle"></i>
                    Add Return

            </a>

        </div>

    </div>

    <section class="panel mt-3" id="traySummaryPrint">

        <div class="panel-header">

            <div>
                <h2 class="h5 mb-1 section-title">
                    <i class="bi bi-table"></i>
                    <span>Customer Tray Balance</span>
                </h2>
            </div>

        </div>

        <div class="table-responsive">

            <table class="table align-middle mb-0">

                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Customer</th>
                        <th>Big/Small [Given]</th>
                        <th>Big/Small [Returned]</th>
                        <th>Big/Small [Balance]</th>
                        <th class="no-print">Action</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($summary as $key => $row)

                        <tr>

                            <td>{{ $key + 1 }}</td>

                            <td>
                                {{ $row['customer']->name }}
                            </td>

                            <td>
                                {{ $row['big_given'] }} / {{ $row['small_given'] }}
                            </td>

                            <td>
                                {{ $row['big_returned'] }} / {{ $row['small_returned'] }}
                            </td>

                            <td>
                                <span class="badge text-bg-primary">
                                    {{ $row['big_balance'] }}
                                </span>
                                /
                                <span class="badge text-bg-success">
                                    {{ $row['small_balance'] }}
                                </span>
                            </td>

                            <td class="no-print">
                                <button
                                    class="btn btn-primary btn-sm trayLedger"
                                    data-id="{{ $row['customer']->id }}"
                                    data-name="{{ $row['customer']->name }}">
                                    View Ledger
                                </button>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="8" class="text-center py-4">
                                No Records Found
                            </td>
                        </tr>

                    @endforelse

                </tbody>

                @if($summary->count())

                    <tfoot>
                        <tr class="fw-bold">
                            <td colspan="2">
                                Grand Total
                            </td>

                            <td>
                                {{ $summary->sum('big_given') }}
                                /
                                {{ $summary->sum('small_given') }}
                            </td>

                            <td>
                                {{ $summary->sum('big_returned') }}
                                /
                                {{ $summary->sum('small_returned') }}
                            </td>

                            <td>
                                {{ $summary->sum('big_balance') }}
                                /
                                {{ $summary->sum('small_balance') }}
                            </td>
                        </tr>
                    </tfoot>

                @endif

            </table>

        </div>

    </section>

</div>
<div class="modal fade" id="trayLedgerModal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content" id="individualTrayLedgerPrint">

            <div class="modal-header">
                <h5 class="modal-title">
                    Tray Ledger - <span id="tray_customer_name"></span>
                </h5>

                <div class="d-flex align-items-center gap-2">
                    <button type="button"
                            class="btn btn-primary btn-sm"
                            id="printIndividualTrayLedger">
                        <i class="bi bi-printer"></i>
                        Print
                    </button>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"></button>
                </div>
            </div>

            <div class="modal-body">

                <table class="table table-bordered">

                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Bill ID</th>
                            <th>Big/Small [Given]</th>
                            <th>Big/Small [Returned]</th>
                            <th>Big/Small [Balance]</th>
                        </tr>
                    </thead>

                    <tbody id="trayLedgerBody"></tbody>

                </table>

            </div>

        </div>
    </div>
</div>
{{-- tray return model --}}
<div class="modal fade" id="trayReturnModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <form method="POST"
                  action="{{ route('tray-returns.store') }}">

                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">
                        Add Tray Return
                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="col-md-4 mb-3">
                            <label class="form-label">
                                Customer
                            </label>

                            <select name="customer_id"
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

                        <div class="col-md-4 mb-3">
                            <label class="form-label">
                                Return Date
                            </label>

                            <input type="date"
                                   name="return_date"
                                   class="form-control"
                                   value="{{ date('Y-m-d') }}"
                                   required>
                        </div>

                        <div class="col-md-2 mb-3">
                            <label class="form-label">
                                Big Tray
                            </label>

                            <input type="number"
                                   name="big_qty"
                                   class="form-control"
                                   value="0"
                                   min="0">
                        </div>

                        <div class="col-md-2 mb-3">
                            <label class="form-label">
                                Small Tray
                            </label>

                            <input type="number"
                                   name="small_qty"
                                   class="form-control"
                                   value="0"
                                   min="0">
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">
                                Remarks
                            </label>

                            <textarea name="remarks"
                                      rows="3"
                                      class="form-control"></textarea>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">
                        Close
                    </button>

                    <button type="submit"
                            class="btn btn-primary">
                        Save
                    </button>

                </div>

            </form>

        </div>
    </div>
</div>

<script>

function printContent(title, content) {

    const printWindow = window.open('', '_blank', 'width=1000,height=700');

    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>${title}</title>

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
                    margin: 0 0 20px 0;
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
            </style>
        </head>

        <body>

            <h2>${title}</h2>

            ${content}

        </body>
        </html>
    `);

    printWindow.document.close();

    printWindow.focus();

    setTimeout(function () {
        printWindow.print();
        printWindow.close();
    }, 300);
}


/*
|--------------------------------------------------------------------------
| Overall Tray Ledger Print
|--------------------------------------------------------------------------
*/

document.getElementById('printTraySummary').addEventListener('click', function () {

    const table = document.querySelector(
        '#traySummaryPrint table'
    ).cloneNode(true);

    // Remove Action column
    table.querySelectorAll('tr').forEach(function (row) {

        const cells = row.querySelectorAll('th, td');

        // Remove Action column only from rows containing 6 columns
        if (cells.length === 6) {
            cells[cells.length - 1].remove();
        }
    });

    printContent(
        'Customer Tray Balance',
        table.outerHTML
    );
});


/*
|--------------------------------------------------------------------------
| Individual Customer Tray Ledger Print
|--------------------------------------------------------------------------
*/

document.getElementById('printIndividualTrayLedger')
    .addEventListener('click', function () {

        const customerName =
            document.getElementById('tray_customer_name').textContent.trim();

        const table = document.querySelector(
            '#individualTrayLedgerPrint table'
        ).cloneNode(true);

        printContent(
            'Tray Ledger - ' + customerName,
            table.outerHTML
        );
    });

</script>
@endsection