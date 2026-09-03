<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
class HomeController extends Controller
{
    public function index()
    {
        $customerCount = Customer::count();
        $productCount  = Product::count();

        $today = now()->toDateString();

        // Today's DC Sales
        $dcSale = Sale::whereDate('bill_date', $today)
            ->where('bill_type', 'credit');

        $dcSaleAmount = $dcSale->sum('net_amount');
        $dcSaleBills  = $dcSale->count();

        // Today's Cash Sales
        $cashSale = Sale::whereDate('bill_date', $today)
            ->where('bill_type', 'cash');

        $cashSaleAmount = $cashSale->sum('net_amount');
        $cashSaleBills  = $cashSale->count();

        // Today's Total Sales
        $totalSaleAmount = $dcSaleAmount + $cashSaleAmount;
        $totalSaleBills  = $dcSaleBills + $cashSaleBills;

        $recentUsers = Customer::latest()
            ->take(5)
            ->get();
        // Sales Overview - Last 7 Days
        // Sales Overview - Last 7 Days
$period = request('sales_period', '7');

if ($period === 'month') {

    $fromDate = now()->startOfMonth();
    $toDate   = now()->endOfDay();

} else {

    $days = (int) $period;

    $fromDate = now()->subDays($days - 1)->startOfDay();
    $toDate   = now()->endOfDay();
}

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
             'salesOverview'
        ));
    }
    public function salesOverview(Request $request)
{
    $period = $request->get('sales_period', '7');

    if ($period === 'month') {

        $fromDate = now()->startOfMonth();
        $toDate   = now()->endOfDay();

    } else {

        $days = max((int) $period, 1);

        $fromDate = now()->subDays($days - 1)->startOfDay();
        $toDate   = now()->endOfDay();
    }

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

    return response()->json($salesOverview);
}
}
