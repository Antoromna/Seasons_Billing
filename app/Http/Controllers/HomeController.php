<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    private function getPeriodDates($period)
    {
        if ($period === 'today') {
            $fromDate = now()->startOfDay();
            $toDate   = now()->endOfDay();
        } elseif ($period === 'month') {
            $fromDate = now()->startOfMonth();
            $toDate   = now()->endOfDay();
        } else {
            $days = max((int) $period, 1);
            $fromDate = now()->subDays($days - 1)->startOfDay();
            $toDate   = now()->endOfDay();
        }
        return [$fromDate, $toDate];
    }

    public function index(Request $request)
    {
        $customerCount = Customer::count();
        $productCount  = Product::count();

        $today = now()->toDateString();

        // Today's DC Sales
        $dcSale = Sale::whereDate('bill_date', $today)->where('bill_type', 'credit');
        $dcSaleAmount = (float) $dcSale->sum('net_amount');
        $dcSaleBills  = $dcSale->count();

        // Today's Cash Sales
        $cashSale = Sale::whereDate('bill_date', $today)->where('bill_type', 'cash');
        $cashSaleAmount = (float) $cashSale->sum('net_amount');
        $cashSaleBills  = $cashSale->count();

        // Today's Total Sales
        $totalSaleAmount = $dcSaleAmount + $cashSaleAmount;
        $totalSaleBills  = $dcSaleBills + $cashSaleBills;

        $recentUsers = Customer::latest()->take(5)->get();

        // Period filter for overview and product charts
        $period = $request->get('sales_period', '7');
        [$fromDate, $toDate] = $this->getPeriodDates($period);

        // 1. Sales Overview Data
        $salesOverview = $this->fetchSalesOverviewData($fromDate, $toDate);

        // 2. Product Sales Distribution (Top Products)
        $topProductsData = $this->fetchTopProductsData($fromDate, $toDate);

        // 3. Per Day Product Sales Breakdown
        $dailyProductSalesData = $this->fetchDailyProductSalesData($fromDate, $toDate);

        return view('auth.dashboard', compact(
            'customerCount',
            'productCount',
            'recentUsers',
            'dcSaleAmount',
            'dcSaleBills',
            'cashSaleAmount',
            'cashSaleBills',
            'totalSaleAmount',
            'totalSaleBills',
            'salesOverview',
            'topProductsData',
            'dailyProductSalesData'
        ));
    }

    public function salesOverview(Request $request)
    {
        $period = $request->get('sales_period', '7');
        [$fromDate, $toDate] = $this->getPeriodDates($period);
        $salesOverview = $this->fetchSalesOverviewData($fromDate, $toDate);
        return response()->json($salesOverview);
    }

    public function productSales(Request $request)
    {
        $period = $request->get('sales_period', '7');
        [$fromDate, $toDate] = $this->getPeriodDates($period);
        $topProducts = $this->fetchTopProductsData($fromDate, $toDate);
        return response()->json($topProducts);
    }

    public function dailyProductSales(Request $request)
    {
        $period = $request->get('sales_period', '7');
        [$fromDate, $toDate] = $this->getPeriodDates($period);
        $dailyData = $this->fetchDailyProductSalesData($fromDate, $toDate);
        return response()->json($dailyData);
    }

    private function fetchSalesOverviewData($fromDate, $toDate)
    {
        $salesData = Sale::selectRaw("
                DATE(bill_date) as sale_date,
                SUM(CASE WHEN bill_type = 'credit' THEN net_amount ELSE 0 END) as dc_sales,
                SUM(CASE WHEN bill_type = 'cash' THEN net_amount ELSE 0 END) as cash_sales
            ")
            ->whereBetween('bill_date', [$fromDate, $toDate])
            ->groupBy(DB::raw('DATE(bill_date)'))
            ->orderBy('sale_date')
            ->get()
            ->keyBy('sale_date');

        $salesOverview = collect();
        $currentDate = $fromDate->copy()->startOfDay();

        while ($currentDate->lte($toDate)) {
            $date = $currentDate->toDateString();
            $salesOverview->push([
                'date'       => $date,
                'label'      => $currentDate->format('d M'),
                'dc_sales'   => (float) ($salesData[$date]->dc_sales ?? 0),
                'cash_sales' => (float) ($salesData[$date]->cash_sales ?? 0),
            ]);
            $currentDate->addDay();
        }

        return $salesOverview;
    }

    private function fetchTopProductsData($fromDate, $toDate)
    {
        $items = SaleItem::join('sales', 'sales.id', '=', 'sale_items.sale_id')
            ->whereBetween('sales.bill_date', [$fromDate, $toDate])
            ->selectRaw("
                COALESCE(NULLIF(sale_items.product, ''), 'Product #' || sale_items.product_id) as product_name,
                SUM(sale_items.quantity) as total_qty,
                SUM(sale_items.total) as total_amount
            ")
            ->groupBy('product_name')
            ->orderByDesc('total_qty')
            ->limit(6)
            ->get();

        return $items->map(function ($item) {
            return [
                'name' => $item->product_name ?: 'Unknown',
                'qty'  => (float) $item->total_qty,
                'amount' => (float) $item->total_amount,
            ];
        });
    }

    private function fetchDailyProductSalesData($fromDate, $toDate)
    {
        // Fetch top 5 products in range
        $topProducts = SaleItem::join('sales', 'sales.id', '=', 'sale_items.sale_id')
            ->whereBetween('sales.bill_date', [$fromDate, $toDate])
            ->selectRaw("COALESCE(NULLIF(sale_items.product, ''), 'Product #' || sale_items.product_id) as product_name, SUM(sale_items.quantity) as total_qty")
            ->groupBy('product_name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->pluck('product_name')
            ->toArray();

        if (empty($topProducts)) {
            return [
                'labels' => [],
                'series' => []
            ];
        }

        $dailyData = SaleItem::join('sales', 'sales.id', '=', 'sale_items.sale_id')
            ->whereBetween('sales.bill_date', [$fromDate, $toDate])
            ->whereIn('sale_items.product', $topProducts)
            ->selectRaw("
                DATE(sales.bill_date) as sale_date,
                sale_items.product as product_name,
                SUM(sale_items.quantity) as qty,
                SUM(sale_items.total) as amount
            ")
            ->groupBy(DB::raw('DATE(sales.bill_date)'), 'sale_items.product')
            ->get();

        $matrix = [];
        foreach ($dailyData as $row) {
            $matrix[$row->sale_date][$row->product_name] = (float) $row->qty;
        }

        $labels = [];
        $seriesData = [];
        foreach ($topProducts as $prod) {
            $seriesData[$prod] = [];
        }

        $currentDate = $fromDate->copy()->startOfDay();
        while ($currentDate->lte($toDate)) {
            $dateStr = $currentDate->toDateString();
            $labels[] = $currentDate->format('d M');

            foreach ($topProducts as $prod) {
                $seriesData[$prod][] = (float) ($matrix[$dateStr][$prod] ?? 0);
            }

            $currentDate->addDay();
        }

        $series = [];
        foreach ($topProducts as $prod) {
            $series[] = [
                'name' => $prod,
                'data' => $seriesData[$prod]
            ];
        }

        return [
            'labels' => $labels,
            'series' => $series
        ];
    }
}

