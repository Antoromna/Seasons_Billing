@extends('layouts.auth')

@section('content')
    <main class="dashboard-content">
        <div class="container-fluid px-3 px-lg-4 py-4">
          <div class="page-heading">
            <div class="page-heading-copy">
              <span class="page-icon"><i class="bi bi-speedometer2" aria-hidden="true"></i></span>
              <div>
                {{-- <p class="eyebrow mb-1">Overview</p> --}}
                <h1 class="h3 mb-1">Dashboard</h1>
                {{-- <p class="text-muted mb-0">Monitor performance, sales, users, and support from one clean workspace.</p> --}}
              </div>
            </div>
            {{-- <div class="heading-actions"><button class="btn btn-outline-secondary btn-sm" type="button"><i class="bi bi-download" aria-hidden="true"></i> Export</button><button class="btn btn-primary btn-sm" type="button"><i class="bi bi-file-earmark-plus" aria-hidden="true"></i> Create Report</button></div> --}}
          </div>
         <section class="row g-3 mt-1">

    <!-- Customers -->
    <div class="col-12 col-sm-6 col-xl">
        <article class="dashboard-stat-card">
            <div class="stat-icon stat-icon-primary">
                <i class="bi bi-people"></i>
            </div>

            <div class="stat-content">
                <div class="stat-label">Customers</div>

                <div class="stat-value">
                    {{ number_format($customerCount) }}
                </div>

                <div class="stat-footer">
                    <span>Total Customers</span>
                </div>
            </div>
        </article>
    </div>

    <!-- Products -->
    {{-- <div class="col-12 col-sm-6 col-xl">
        <article class="dashboard-stat-card">
            <div class="stat-icon stat-icon-success">
                <i class="bi bi-box-seam"></i>
            </div>

            <div class="stat-content">
                <div class="stat-label">Products</div>

                <div class="stat-value">
                    {{ number_format($productCount) }}
                </div>

                <div class="stat-footer">
                    <span>Total Products</span>
                </div>
            </div>
        </article>
    </div> --}}

    <!-- DC Sales -->
    <div class="col-12 col-sm-6 col-xl">
        <article class="dashboard-stat-card">
            <div class="stat-icon stat-icon-info">
                <i class="bi bi-receipt"></i>
            </div>

            <div class="stat-content">
                <div class="stat-label">
                    DC Sales
                    <span class="today-badge">Today</span>
                </div>

                <div class="stat-value">
                    ₹{{ number_format($dcSaleAmount, 2) }}
                </div>

                <div class="stat-footer">
                    <span>
                        <i class="bi bi-file-text"></i>
                        {{ $dcSaleBills }} Bills
                    </span>
                </div>
            </div>
        </article>
    </div>

    <!-- Cash Sales -->
    <div class="col-12 col-sm-6 col-xl">
        <article class="dashboard-stat-card">
            <div class="stat-icon stat-icon-warning">
                <i class="bi bi-cash-stack"></i>
            </div>

            <div class="stat-content">
                <div class="stat-label">
                    Cash Sales
                    <span class="today-badge">Today</span>
                </div>

                <div class="stat-value">
                    ₹{{ number_format($cashSaleAmount, 2) }}
                </div>

                <div class="stat-footer">
                    <span>
                        <i class="bi bi-file-text"></i>
                        {{ $cashSaleBills }} Bills
                    </span>
                </div>
            </div>
        </article>
    </div>

    <!-- Total Sales -->
    <div class="col-12 col-sm-6 col-xl">
        <article class="dashboard-stat-card total-sales-card">
            <div class="stat-icon stat-icon-total">
                <i class="bi bi-graph-up-arrow"></i>
            </div>

            <div class="stat-content">
                <div class="stat-label">
                    Total Sales
                    <span class="today-badge">Today</span>
                </div>

                <div class="stat-value">
                    ₹{{ number_format($totalSaleAmount, 2) }}
                </div>

                <div class="stat-footer">
                    <span>
                        <i class="bi bi-file-text"></i>
                        {{ $totalSaleBills }} Bills
                    </span>
                </div>
            </div>
        </article>
    </div>

</section>
<!-- Sales Overview -->
<section class="panel mt-3">

    <div class="panel-header">
    <div>
        <h2 class="h5 mb-1 section-title">
            <i class="bi bi-bar-chart-line" aria-hidden="true"></i>
            <span>Sales Overview</span>
        </h2>

        <p class="text-muted mb-0">
            DC and Cash sales
        </p>
    </div>

    <div class="d-flex align-items-center gap-3">

        <div class="sales-filter">
            <i class="bi bi-calendar3"></i>

            <select id="salesPeriodFilter">
                <option value="7" selected>Last 7 Days</option>
                <option value="15">Last 15 Days</option>
                <option value="30">Last 30 Days</option>
                <option value="month">This Month</option>
            </select>
        </div>

        <div class="sales-overview-legend">
            <span>
                <i class="bi bi-circle-fill"></i>
                DC Sales
            </span>

            <span>
                <i class="bi bi-circle-fill"></i>
                Cash Sales
            </span>
        </div>

    </div>
</div>

    <div class="sales-chart-wrapper">
        <div id="salesOverviewChart"></div>
    </div>

</section>


          <section class="panel mt-3">
            <div class="panel-header">
              <div>
                <h2 class="h5 mb-1 section-title"><i class="bi bi-people" aria-hidden="true"></i><span>Recent Users</span></h2>
                {{-- <p class="text-muted mb-0">Latest account activity across the workspace.</p> --}}
              </div>
              <a class="btn btn-outline-secondary btn-sm" href="{{route('customers.index')}}">Manage Users</a>
            </div>
            <div class="table-responsive">
              <table class="table align-middle mb-0">
                <thead><tr><th scope="col">User</th><th scope="col">Status</th><th scope="col">Joined</th></tr></thead>
                <tbody>
                    @forelse($recentUsers as $user)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div>
                                    <p class="fw-semibold mb-0">{{ $user->name }}</p>
                                </div>
                            </div>
                        </td>

                        

                        <td>
                            <span class="badge text-bg-success">
                                Active
                            </span>
                        </td>

                        <td>{{ $user->created_at->format('d M, Y') }}</td>

                        
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">No users found</td>
                    </tr>
                    @endforelse
                    </tbody>
              </table>
            </div>
          </section>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const salesData = @json($salesOverview);

    const options = {

        chart: {
            type: 'area',
            height: 350,
            toolbar: {
                show: false
            },
            zoom: {
                enabled: false
            },
            parentHeightOffset: 0
        },

        series: [
            {
                name: 'DC Sales',
                data: salesData.map(item => Number(item.dc_sales))
            },
            {
                name: 'Cash Sales',
                data: salesData.map(item => Number(item.cash_sales))
            }
        ],

        xaxis: {
            categories: salesData.map(item => item.label),

            axisBorder: {
                show: false
            },

            axisTicks: {
                show: false
            },

            labels: {
                style: {
                    colors: '#9ca3af',
                    fontSize: '11px'
                }
            }
        },

        yaxis: {
            labels: {
                formatter: function (value) {
                    return '₹' + Number(value).toLocaleString('en-IN');
                }
            }
        },

        stroke: {
            curve: 'smooth',
            width: 3
        },

        markers: {
            size: 4,
            strokeWidth: 2,
            hover: {
                size: 6
            }
        },

        fill: {
            type: 'gradient',
            gradient: {
                opacityFrom: 0.25,
                opacityTo: 0.03
            }
        },

        dataLabels: {
            enabled: false
        },

        grid: {
            borderColor: '#f1f3f5',
            strokeDashArray: 4,
            padding: {
                left: 10,
                right: 10,
                top: 5,
                bottom: 0
            }
        },

        tooltip: {
            shared: true,
            intersect: false,

            y: {
                formatter: function (value) {
                    return '₹' + Number(value).toLocaleString('en-IN', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                }
            }
        },

        legend: {
            show: false
        },

        colors: ['#2563eb', '#f59e0b']
    };

    const chartElement = document.querySelector('#salesOverviewChart');

    if (!chartElement) {
        console.error('Sales chart element not found');
        return;
    }

    const chart = new ApexCharts(chartElement, options);

    chart.render();


    // Filter change
    document.getElementById('salesPeriodFilter')
        .addEventListener('change', function () {

            const period = this.value;

            fetch(`{{ route('dashboard.sales-overview') }}?sales_period=${period}`)
                .then(response => {

                    if (!response.ok) {
                        throw new Error('Failed to fetch sales data');
                    }

                    return response.json();
                })
                .then(data => {

                    chart.updateOptions({
                        xaxis: {
                            categories: data.map(item => item.label)
                        }
                    });

                    chart.updateSeries([
                        {
                            name: 'DC Sales',
                            data: data.map(item => Number(item.dc_sales))
                        },
                        {
                            name: 'Cash Sales',
                            data: data.map(item => Number(item.cash_sales))
                        }
                    ]);

                })
                .catch(error => {
                    console.error('Sales overview error:', error);
                });

        });

});
</script>
@endsection