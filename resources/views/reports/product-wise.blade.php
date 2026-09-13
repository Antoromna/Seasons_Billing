@extends('layouts.auth')

@section('content')
<style>
    .stat-card-modern {
        background: #ffffff;
        border-radius: 14px;
        padding: 1.25rem 1.5rem;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    .stat-card-modern:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 24px -6px rgba(0, 0, 0, 0.08);
    }
    .stat-card-modern .card-accent-bg {
        position: absolute;
        top: -15px;
        right: -15px;
        width: 90px;
        height: 90px;
        border-radius: 50%;
        opacity: 0.08;
    }
    .icon-box {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
    }
    .icon-primary { background: #eff6ff; color: #2563eb; }
    .icon-success { background: #ecfdf5; color: #059669; }
    .icon-info { background: #f0f9ff; color: #0284c7; }
</style>

<div class="container-fluid px-3 px-lg-4 py-4">

    <!-- Top Executive Header Banner -->
    <div class="index-header-card d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="index-header-icon">
                <i class="bi bi-bar-chart-line-fill"></i>
            </div>
            <div>
                <h1 class="h3 text-white mb-1 fw-bold">Product Wise Sales Report</h1>
                <p class="text-white-50 mb-0 small">Product sales volume, unit breakdown, customer orders, and revenue summary.</p>
            </div>
        </div>
        <div class="d-flex flex-wrap align-items-center gap-2">
            <button type="button" class="index-action-btn index-action-btn-primary" onclick="printProductReport({{ json_encode($totalAmount) }})">
                <i class="bi bi-printer"></i> Print Report
            </button>
        </div>
    </div>

    <!-- Summary KPI Stat Cards Bar -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-4">
            <div class="stat-card-modern">
                <div class="card-accent-bg bg-primary"></div>
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted fs-7 fw-semibold text-uppercase">Total Sales Revenue</span>
                    <div class="icon-box icon-primary">
                        <i class="bi bi-currency-rupee"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-1 text-primary">₹ {{ number_format($totalAmount, 2) }}</h3>
                <div class="d-flex align-items-center gap-1 text-muted small">
                    <i class="bi bi-graph-up text-primary"></i> Cumulative Filter Total
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="stat-card-modern">
                <div class="card-accent-bg bg-success"></div>
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted fs-7 fw-semibold text-uppercase">Total Quantity Sold</span>
                    <div class="icon-box icon-success">
                        <i class="bi bi-box-seam-fill"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-1 text-dark">{{ number_format($totalQty, 2) }} <span class="fs-6 fw-normal text-muted">units</span></h3>
                <div class="d-flex align-items-center gap-1 text-muted small">
                    <i class="bi bi-check-circle text-success"></i> Dispatched Volume
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="stat-card-modern">
                <div class="card-accent-bg bg-info"></div>
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted fs-7 fw-semibold text-uppercase">Line Items Count</span>
                    <div class="icon-box icon-info">
                        <i class="bi bi-receipt"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-1 text-dark">{{ count($items) }} <span class="fs-6 fw-normal text-muted">records</span></h3>
                <div class="d-flex align-items-center gap-1 text-muted small">
                    <i class="bi bi-file-earmark-text text-info"></i> Sales Entries
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Form Section -->
    <div class="panel-custom mb-4">
        <div class="panel-header-custom">
            <div class="panel-header-title">
                <i class="bi bi-funnel-fill text-primary fs-5"></i>
                <span>Report Filter Controls</span>
            </div>
        </div>

        <div class="p-3">
            <form method="GET" action="{{ route('reports.product-wise') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-12 col-sm-6 col-md-3">
                        <label class="form-label fw-semibold text-muted small mb-1">From Date</label>
                        <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control form-control-sm">
                    </div>

                    <div class="col-12 col-sm-6 col-md-3">
                        <label class="form-label fw-semibold text-muted small mb-1">To Date</label>
                        <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control form-control-sm">
                    </div>

                    <div class="col-12 col-sm-6 col-md-3">
                        <label class="form-label fw-semibold text-muted small mb-1">Customer</label>
                        <select name="customer_id" class="form-select form-select-sm">
                            <option value="">All Customers</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-sm-6 col-md-3">
                        <label class="form-label fw-semibold text-muted small mb-1">Product</label>
                        <select name="product_id" class="form-select form-select-sm">
                            <option value="">All Products</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 d-flex justify-content-end gap-2 mt-3">
                        <button type="submit" class="btn btn-primary btn-sm px-4">
                            <i class="bi bi-search me-1"></i> Apply Filter
                        </button>
                        <a href="{{ route('reports.product-wise') }}" class="btn btn-outline-secondary btn-sm px-3" title="Reset Filters">
                            <i class="bi bi-arrow-clockwise me-1"></i> Reset
                        </a>
                        <button type="button" class="btn btn-secondary btn-sm px-3" onclick="printProductReport({{ json_encode($totalAmount) }})">
                            <i class="bi bi-printer me-1"></i> Print
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Data Table Section -->
    <section class="panel-custom">
        <div class="panel-header-custom">
            <div class="panel-header-title">
                <i class="bi bi-table text-success fs-5"></i>
                <span>Product Sales Detail Records</span>
                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill fs-7 ms-2">
                    {{ count($items) }} Items
                </span>
            </div>
        </div>

        <div class="table-responsive">
            <div id="printSection">
                <table id="tableBody" class="DataTable table table-sm align-middle mb-0">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Date</th>
                            <th>Bill No</th>
                            <th>Customer</th>
                            <th>Product</th>
                            <th>Unit</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $key => $item)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td class="text-nowrap">{{ \Carbon\Carbon::parse($item->sale->bill_date)->format('d-m-Y') }}</td>
                            <td>
                                <span class="badge bg-light text-dark border fw-mono">{{ $item->sale->bill_no }}</span>
                            </td>
                            <td>{{ $item->sale->customer->name ?? '-' }}</td>
                            <td class="fw-semibold text-dark">{{ $item->product ?? '-' }}</td>
                            <td><span class="badge bg-secondary-subtle text-secondary">{{ ucfirst($item->unit) }}</span></td>
                            <td class="fw-bold">{{ number_format($item->quantity, 2) }}</td>
                            <td>₹ {{ number_format($item->price, 2) }}</td>
                            <td class="text-end fw-bold text-primary">₹ {{ number_format($item->total, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-2 d-block text-black-50 mb-2"></i>
                                No Product Sales Records Found
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr class="table-light fw-bold">
                            <td colspan="6" class="text-end text-uppercase fs-7">Grand Total:</td>
                            <td class="text-dark">{{ number_format($totalQty, 2) }}</td>
                            <td>₹ {{ number_format($totalPrice, 2) }}</td>
                            <td class="text-end text-primary fs-6">₹ {{ number_format($totalAmount, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </section>

</div>

<script>
    window.totalAmount = @json($totalAmount);
</script>
@endsection