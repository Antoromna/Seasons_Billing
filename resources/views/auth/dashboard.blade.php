@extends('layouts.auth')

@section('content')
<style>
    /* Custom Styling for Executive Dashboard */
    .dashboard-content {
        background-color: #f8fafc;
        min-height: calc(100vh - 60px);
        padding-bottom: 2rem;
    }
    .dashboard-header-card {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        color: #ffffff;
        border-radius: 16px;
        padding: 1.5rem 1.75rem;
        box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.15);
    }
    .quick-action-btn {
        background: rgba(255, 255, 255, 0.12);
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(8px);
        transition: all 0.2s ease;
        border-radius: 8px;
        font-weight: 500;
    }
    .quick-action-btn:hover {
        background: rgba(255, 255, 255, 0.25);
        color: #ffffff;
        transform: translateY(-2px);
    }

    /* Stat Cards */
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
    .icon-warning { background: #fffbeb; color: #d97706; }
    .icon-purple { background: #faf5ff; color: #7c3aed; }

    .badge-today {
        background: #e0f2fe;
        color: #0369a1;
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.25rem 0.6rem;
        border-radius: 20px;
    }

    /* Panels & Charts */
    .dashboard-panel {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    .panel-header-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .filter-select {
        background-color: #f8fafc;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        padding: 0.45rem 1rem;
        font-size: 0.875rem;
        font-weight: 500;
        color: #334155;
        outline: none;
        transition: border-color 0.2s;
    }
    .filter-select:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }
    
    .table-custom {
        margin-bottom: 0;
    }
    .table-custom th {
        background: #f8fafc;
        color: #64748b;
        font-weight: 600;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 0.85rem 1rem;
        border-bottom: 1px solid #e2e8f0;
    }
    .table-custom td {
        padding: 0.9rem 1rem;
        color: #334155;
        font-size: 0.9rem;
        border-bottom: 1px solid #f1f5f9;
    }
</style>

<main class="dashboard-content">
    <div class="container-fluid px-3 px-lg-4 py-4">

        <!-- Top Header & Actions -->
        {{-- <div class="dashboard-header-card mb-4 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 rounded-pill fw-semibold">
                        <i class="bi bi-clock-history me-1"></i> Live Overview
                    </span>
                    <span class="text-white-50 small">{{ \Carbon\Carbon::now()->format('l, d F Y') }}</span>
                </div>
                <h1 class="h3 text-white mb-1 fw-bold">Executive Dashboard</h1>
                <p class="text-white-50 mb-0 small">Real-time performance, daily product movement, and sales metrics.</p>
            </div>
            <div class="d-flex flex-wrap align-items-center gap-2">
                <a href="{{ route('sales.index') }}" class="btn quick-action-btn btn-sm px-3 py-2">
                    <i class="bi bi-cart-plus me-1"></i> New Sale
                </a>
                <a href="{{ route('products.index') }}" class="btn quick-action-btn btn-sm px-3 py-2">
                    <i class="bi bi-box me-1"></i> Products
                </a>
                <a href="{{ route('customers.index') }}" class="btn quick-action-btn btn-sm px-3 py-2">
                    <i class="bi bi-person-plus me-1"></i> Customers
                </a>
                <a href="{{ route('reports.product-wise') }}" class="btn quick-action-btn btn-sm px-3 py-2">
                    <i class="bi bi-bar-chart me-1"></i> Reports
                </a>
            </div>
        </div> --}}

        <!-- Metric Cards Grid -->
        <section class="row g-3 mb-4">
            <!-- Total Customers -->
            {{-- <div class="col-12 col-sm-6 col-xl">
                <div class="stat-card-modern">
                    <div class="card-accent-bg bg-primary"></div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted fs-7 fw-semibold text-uppercase">Customers</span>
                        <div class="icon-box icon-primary">
                            <i class="bi bi-people-fill"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold mb-1 text-dark">{{ number_format($customerCount) }}</h3>
                    <div class="d-flex align-items-center gap-1 text-muted small">
                        <i class="bi bi-person-check text-primary"></i> Total Registered
                    </div>
                </div>
            </div> --}}

            <!-- Total Products -->
            {{-- <div class="col-12 col-sm-6 col-xl">
                <div class="stat-card-modern">
                    <div class="card-accent-bg bg-success"></div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted fs-7 fw-semibold text-uppercase">Products</span>
                        <div class="icon-box icon-success">
                            <i class="bi bi-box-seam-fill"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold mb-1 text-dark">{{ number_format($productCount) }}</h3>
                    <div class="d-flex align-items-center gap-1 text-muted small">
                        <i class="bi bi-check-circle text-success"></i> Active Inventory
                    </div>
                </div>
            </div> --}}

            <!-- Today DC Sales -->
            <div class="col-12 col-sm-6 col-xl">
                <div class="stat-card-modern">
                    <div class="card-accent-bg bg-info"></div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="d-flex align-items-center gap-2">
                            <span class="text-muted fs-7 fw-semibold text-uppercase">DC Sales</span>
                            <span class="badge-today">Today</span>
                        </div>
                        <div class="icon-box icon-info">
                            <i class="bi bi-receipt"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold mb-1 text-dark">₹{{ number_format($dcSaleAmount, 2) }}</h3>
                    <div class="d-flex align-items-center gap-1 text-muted small">
                        <i class="bi bi-file-text"></i> {{ $dcSaleBills }} Credit Bills
                    </div>
                </div>
            </div>

            <!-- Today Cash Sales -->
            <div class="col-12 col-sm-6 col-xl">
                <div class="stat-card-modern">
                    <div class="card-accent-bg bg-warning"></div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="d-flex align-items-center gap-2">
                            <span class="text-muted fs-7 fw-semibold text-uppercase">Cash Sales</span>
                            <span class="badge-today">Today</span>
                        </div>
                        <div class="icon-box icon-warning">
                            <i class="bi bi-cash-stack"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold mb-1 text-dark">₹{{ number_format($cashSaleAmount, 2) }}</h3>
                    <div class="d-flex align-items-center gap-1 text-muted small">
                        <i class="bi bi-file-text"></i> {{ $cashSaleBills }} Cash Bills
                    </div>
                </div>
            </div>

            <!-- Total Sales -->
            <div class="col-12 col-sm-6 col-xl">
                <div class="stat-card-modern border-primary border-opacity-25" style="background: linear-gradient(180deg, #ffffff 0%, #f4f7ff 100%);">
                    <div class="card-accent-bg bg-purple"></div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="d-flex align-items-center gap-2">
                            <span class="text-primary fs-7 fw-bold text-uppercase">Total Sales</span>
                            <span class="badge bg-primary text-white px-2 py-0.5 rounded-pill" style="font-size:0.7rem;">Today</span>
                        </div>
                        <div class="icon-box icon-purple">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold mb-1 text-primary">₹{{ number_format($totalSaleAmount, 2) }}</h3>
                    <div class="d-flex align-items-center gap-1 text-muted small">
                        <i class="bi bi-receipt-cutoff text-primary"></i> {{ $totalSaleBills }} Total Invoices
                    </div>
                </div>
            </div>
        </section>

        <!-- Global Date Range Filter Header -->
        <div class="d-flex align-items-center justify-content-between mb-3 px-1">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-funnel-fill text-primary fs-5"></i>
                <h5 class="fw-bold text-dark mb-0">Analytics & Sales Breakdown</h5>
            </div>
            <div class="d-flex align-items-center gap-2">
                <label for="globalPeriodFilter" class="form-label mb-0 fw-semibold text-muted small me-1 d-none d-sm-inline-block">Period:</label>
                <select id="globalPeriodFilter" class="filter-select">
                    <option value="today">Today</option>
                    <option value="7" selected>Last 7 Days</option>
                    <option value="15">Last 15 Days</option>
                    <option value="30">Last 30 Days</option>
                    <option value="month">This Month</option>
                </select>
            </div>
        </div>

        <!-- Row 1 Charts: Sales Overview Area Chart & Top Products Donut Chart -->
        <div class="row g-3 mb-4">
            <!-- Sales Overview Area Chart -->
            <div class="col-12 col-lg-7 col-xl-8">
                <div class="dashboard-panel h-100">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="panel-header-title">
                            <i class="bi bi-bar-chart-line text-primary fs-4"></i>
                            <div>
                                <span class="d-block">Sales Revenue Overview</span>
                                <span class="text-muted fw-normal fs-7">DC (Credit) vs Cash Sales comparison</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3 fs-7 fw-semibold">
                            <span class="text-primary"><i class="bi bi-circle-fill me-1"></i> DC Sales</span>
                            <span class="text-warning"><i class="bi bi-circle-fill me-1"></i> Cash Sales</span>
                        </div>
                    </div>
                    <div id="salesOverviewChart" style="min-height: 320px;"></div>
                </div>
            </div>

            <!-- Top Selling Products Donut/Bar Chart -->
            <div class="col-12 col-lg-5 col-xl-4">
                <div class="dashboard-panel h-100">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="panel-header-title">
                            <i class="bi bi-pie-chart text-success fs-4"></i>
                            <div>
                                <span class="d-block">Top Products</span>
                                <span class="text-muted fw-normal fs-7">Quantity share by product</span>
                            </div>
                        </div>
                    </div>
                    <div id="topProductsChart" style="min-height: 320px;"></div>
                </div>
            </div>
        </div>

        <!-- Row 2 Chart: Per-Day Product Sales Breakdown Chart -->
        <div class="row g-3 mb-4">
            <div class="col-12">
                <div class="dashboard-panel">
                    <div class="d-flex flex-wrap align-items-center justify-content-between mb-3 gap-2">
                        <div class="panel-header-title">
                            <i class="bi bi-layers-fill text-purple fs-4"></i>
                            <div>
                                <span class="d-block">Per Day Product Sales Breakdown</span>
                                <span class="text-muted fw-normal fs-7">Daily quantity sold for top products</span>
                            </div>
                        </div>
                        <span class="badge bg-light text-secondary border px-3 py-2 rounded-pill fs-7">
                            <i class="bi bi-info-circle me-1"></i> Daily product distribution
                        </span>
                    </div>
                    <div id="dailyProductSalesChart" style="min-height: 350px;"></div>
                </div>
            </div>
        </div>

        <!-- Recent Customers Section -->
        {{-- <div class="row g-3">
            <div class="col-12">
                <div class="dashboard-panel">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="panel-header-title">
                            <i class="bi bi-people text-info fs-4"></i>
                            <div>
                                <span class="d-block">Recently Added Customers</span>
                                <span class="text-muted fw-normal fs-7">Latest customer profiles in workspace</span>
                            </div>
                        </div>
                        <a class="btn btn-outline-secondary btn-sm rounded-pill px-3" href="{{ route('customers.index') }}">
                            View All Customers <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-custom align-middle">
                            <thead>
                                <tr>
                                    <th scope="col">Customer Name</th>
                                    <th scope="col">Phone</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Joined Date</th>
                                    <th scope="col" class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentUsers as $user)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center fw-bold" style="width: 38px; height: 38px; font-size: 0.9rem;">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="fw-bold mb-0 text-dark">{{ $user->name }}</p>
                                                <span class="text-muted fs-7">{{ $user->place ?? 'Local Customer' }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="font-monospace text-muted fs-7">{{ $user->phone ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill">
                                            <i class="bi bi-check-circle-fill me-1"></i> Active
                                        </span>
                                    </td>
                                    <td class="text-muted fs-7">
                                        {{ $user->created_at ? $user->created_at->format('d M, Y') : 'N/A' }}
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('customers.index') }}" class="btn btn-light btn-sm rounded-circle" title="View details">
                                            <i class="bi bi-chevron-right text-muted"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        <i class="bi bi-inbox fs-2 d-block text-black-50 mb-2"></i>
                                        No recent customers found
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div> --}}

    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // Initial Data Passed from Backend Controller
    const initialSalesOverview = @json($salesOverview);
    const initialTopProducts = @json($topProductsData);
    const initialDailyProducts = @json($dailyProductSalesData);

    // -------------------------------------------------------------
    // 1. Sales Overview Area Chart (DC vs Cash)
    // -------------------------------------------------------------
    const salesOverviewOptions = {
        chart: {
            type: 'area',
            height: 330,
            toolbar: { show: false },
            zoom: { enabled: false }
        },
        series: [
            {
                name: 'DC Sales',
                data: initialSalesOverview.map(item => Number(item.dc_sales))
            },
            {
                name: 'Cash Sales',
                data: initialSalesOverview.map(item => Number(item.cash_sales))
            }
        ],
        xaxis: {
            categories: initialSalesOverview.map(item => item.label),
            axisBorder: { show: false },
            axisTicks: { show: false },
            labels: {
                style: { colors: '#64748b', fontSize: '11px' }
            }
        },
        yaxis: {
            labels: {
                style: { colors: '#64748b', fontSize: '11px' },
                formatter: function (val) {
                    return '₹' + Number(val).toLocaleString('en-IN');
                }
            }
        },
        stroke: { curve: 'smooth', width: 3 },
        markers: { size: 4, strokeWidth: 2, hover: { size: 6 } },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.45,
                opacityTo: 0.05
            }
        },
        dataLabels: { enabled: false },
        grid: {
            borderColor: '#f1f5f9',
            strokeDashArray: 4,
            padding: { left: 10, right: 10, top: 10, bottom: 0 }
        },
        tooltip: {
            shared: true,
            intersect: false,
            y: {
                formatter: function (val) {
                    return '₹' + Number(val).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                }
            }
        },
        legend: { show: false },
        colors: ['#2563eb', '#f59e0b']
    };

    const salesOverviewChart = new ApexCharts(document.querySelector("#salesOverviewChart"), salesOverviewOptions);
    salesOverviewChart.render();


    // -------------------------------------------------------------
    // 2. Top Selling Products Donut Chart
    // -------------------------------------------------------------
    const topProductsOptions = {
        chart: {
            type: 'donut',
            height: 330
        },
        series: initialTopProducts.length ? initialTopProducts.map(p => Number(p.qty)) : [1],
        labels: initialTopProducts.length ? initialTopProducts.map(p => p.name) : ['No Data'],
        colors: ['#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#ec4899', '#06b6d4'],
        legend: {
            position: 'bottom',
            fontSize: '12px',
            labels: { colors: '#475569' }
        },
        plotOptions: {
            pie: {
                donut: {
                    size: '70%',
                    labels: {
                        show: true,
                        total: {
                            show: true,
                            label: 'Total Sold',
                            fontSize: '14px',
                            fontWeight: 600,
                            color: '#64748b',
                            formatter: function (w) {
                                if (!initialTopProducts.length) return 0;
                                const total = w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                return total.toLocaleString('en-IN');
                            }
                        }
                    }
                }
            }
        },
        tooltip: {
            y: {
                formatter: function (val, { seriesIndex }) {
                    if (!initialTopProducts.length) return '0';
                    const amount = initialTopProducts[seriesIndex]?.amount ?? 0;
                    return val + ' units (₹' + Number(amount).toLocaleString('en-IN') + ')';
                }
            }
        },
        dataLabels: { enabled: false }
    };

    const topProductsChart = new ApexCharts(document.querySelector("#topProductsChart"), topProductsOptions);
    topProductsChart.render();


    // -------------------------------------------------------------
    // 3. Per Day Product Sales Breakdown Chart (Stacked Column)
    // -------------------------------------------------------------
    const dailyProductOptions = {
        chart: {
            type: 'bar',
            height: 350,
            stacked: true,
            toolbar: { show: false },
            zoom: { enabled: false }
        },
        series: initialDailyProducts.series && initialDailyProducts.series.length ? initialDailyProducts.series : [],
        xaxis: {
            categories: initialDailyProducts.labels || [],
            labels: {
                style: { colors: '#64748b', fontSize: '11px' }
            },
            axisBorder: { show: false },
            axisTicks: { show: false }
        },
        yaxis: {
            title: {
                text: 'Quantity Sold (Units)',
                style: { color: '#64748b', fontSize: '12px', fontWeight: 500 }
            },
            labels: {
                style: { colors: '#64748b', fontSize: '11px' }
            }
        },
        legend: {
            position: 'top',
            horizontalAlign: 'right',
            fontSize: '12px',
            labels: { colors: '#475569' }
        },
        fill: { opacity: 1 },
        colors: ['#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#ec4899'],
        grid: {
            borderColor: '#f1f5f9',
            strokeDashArray: 4
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val + ' units';
                }
            }
        },
        plotOptions: {
            bar: {
                borderRadius: 4,
                columnWidth: '45%'
            }
        }
    };

    const dailyProductSalesChart = new ApexCharts(document.querySelector("#dailyProductSalesChart"), dailyProductOptions);
    dailyProductSalesChart.render();


    // -------------------------------------------------------------
    // 4. Global Period Filter Event Listener
    // -------------------------------------------------------------
    const filterSelect = document.getElementById('globalPeriodFilter');
    if (filterSelect) {
        filterSelect.addEventListener('change', function () {
            const period = this.value;

            // Update Sales Overview Chart
            fetch(`{{ route('dashboard.sales-overview') }}?sales_period=${period}`)
                .then(res => res.json())
                .then(data => {
                    salesOverviewChart.updateOptions({
                        xaxis: { categories: data.map(item => item.label) }
                    });
                    salesOverviewChart.updateSeries([
                        { name: 'DC Sales', data: data.map(item => Number(item.dc_sales)) },
                        { name: 'Cash Sales', data: data.map(item => Number(item.cash_sales)) }
                    ]);
                })
                .catch(err => console.error('Sales overview update error:', err));

            // Update Top Products Chart
            fetch(`{{ route('dashboard.product-sales') }}?sales_period=${period}`)
                .then(res => res.json())
                .then(data => {
                    if (data && data.length) {
                        topProductsChart.updateOptions({
                            labels: data.map(p => p.name)
                        });
                        topProductsChart.updateSeries(data.map(p => Number(p.qty)));
                    } else {
                        topProductsChart.updateOptions({ labels: ['No Data'] });
                        topProductsChart.updateSeries([1]);
                    }
                })
                .catch(err => console.error('Top products update error:', err));

            // Update Daily Product Sales Chart
            fetch(`{{ route('dashboard.daily-product-sales') }}?sales_period=${period}`)
                .then(res => res.json())
                .then(data => {
                    dailyProductSalesChart.updateOptions({
                        xaxis: { categories: data.labels || [] }
                    });
                    dailyProductSalesChart.updateSeries(data.series || []);
                })
                .catch(err => console.error('Daily product sales update error:', err));
        });
    }

});
</script>
@endsection