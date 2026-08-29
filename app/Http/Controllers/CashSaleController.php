<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CashSaleController extends Controller
{
    public function create()
    {
        $products = Product::orderBy('name')->get();

        $lastCashSale = Sale::where('bill_type', 'cash')
            ->where('bill_no', 'like', 'CASH-%')
            ->orderByDesc('id')
            ->first();

        if ($lastCashSale) {
            $lastNumber = (int) str_replace('CASH-', '', $lastCashSale->bill_no);
            $nextBillNumber = $lastNumber + 1;
        } else {
            $nextBillNumber = 1;
        }

        return view('cash_sales.create', compact(
            'products',
            'nextBillNumber'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sale_date' => ['required', 'date'],
            'bills' => ['required', 'array', 'min:1'],
            'notes' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($request) {

            foreach ($request->bills as $billNo => $bill) {

                $items = $bill['items'] ?? [];

                // Generate the next cash bill number from the database
                $lastCashSale = Sale::where('bill_type', 'cash')
                    ->where('bill_no', 'like', 'CASH-%')
                    ->orderByDesc('id')
                    ->first();

                if ($lastCashSale) {
                    $lastNumber = (int) str_replace('CASH-', '', $lastCashSale->bill_no);
                    $nextNumber = $lastNumber + 1;
                } else {
                    $nextNumber = 1;
                }

                $billNo = 'CASH-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

                // Don't create empty bills
                if (empty($items)) {
                    continue;
                }

                $subtotal = collect($items)->sum(function ($item) {
                    return (float) $item['quantity'] * (float) $item['price'];
                });

                $sale = Sale::create([
                    'bill_no' => $billNo,
                    'bill_date' => $request->sale_date,
                    'bill_type' => 'cash',
                    'customer_id' => null,
                    'sales_man_id' => null,
                    'subtotal' => $subtotal,
                    'discount' => 0,
                    'net_amount' => $subtotal,
                    'previous_balance' => 0,
                    'paid_amount' => $subtotal,
                    'balance' => 0,
                    'tray_count' => collect($items)->sum(function ($item) {
                        return (int) ($item['tray_qty'] ?? 0);
                    }),
                    'notes' => $request->notes,
                ]);

                foreach ($items as $item) {

                    $quantity = (float) $item['quantity'];
                    $price = (float) $item['price'];

                    $total = $quantity * $price;

                    $sale->items()->create([
                        'product_id' => $item['product_id'],
                        'product' => $item['product'],
                        'unit' => $item['unit'],
                        'quantity' => $quantity,
                        'price' => $price,
                        'discount' => 0,
                        'total' => $total,
                        'tray' => $item['tray'] ?? 'No Tray',
                        'tray_qty' => (int) ($item['tray_qty'] ?? 0),
                    ]);
                }
            }
        });

        return redirect()
            ->route('sales.index')
            ->with('success', 'Cash sale(s) created successfully.');
    }
    public function edit(Sale $sale)
    {
        abort_if($sale->bill_type !== 'cash', 404);

        $sale->load('items');

        $products = Product::where('status', 1)
            ->orderBy('name')
            ->get();

        return view('cash_sales.edit', compact(
            'sale',
            'products'
        ));
    }
    public function update(Request $request, Sale $sale)
    {
        
        $request->validate([
            'bill_date' => 'required|date',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|numeric|min:0.001',
            'products.*.price' => 'required|numeric|min:0',
            'products.*.tray_qty' => 'nullable|integer|min:0',
            'products.*.total' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {

            $netAmount = collect($request->products)->sum(function ($item) {
                return (float) $item['quantity'] * (float) $item['price'];
            });

            // Cash sale = fully paid
            $paidAmount = $netAmount;
            $balance = 0;

            $sale->update([
                'bill_date'        => $request->bill_date,
                'bill_type'        => 'cash',
                'customer_id'      => null,
                'net_amount'       => $netAmount,
                'discount'         => 0,
                'previous_balance' => 0,
                'paid_amount'      => $paidAmount,
                'balance'          => $balance,
                'notes'            => $request->notes,
            ]);

            // Replace existing items
            $sale->items()->delete();

            foreach ($request->products as $item) {

                $product = Product::findOrFail($item['product_id']);

                SaleItem::create([
                    'sale_id'    => $sale->id,
                    'product_id' => $product->id,
                    'product'    => $product->name,
                    'unit'       => $item['unit'],
                    'quantity'   => $item['quantity'],
                    'tray'       => $item['tray'] ?? 'No Tray',
                    'tray_qty'   => $item['tray_qty'] ?? 0,
                    'price'      => $item['price'],
                    'discount'   => 0,
                    'total'      => (float) $item['quantity'] *
                                    (float) $item['price'],
                ]);
            }

            DB::commit();

            return redirect()
                ->route('sales.index')
                ->with('success', 'Cash Sale Updated Successfully');

        } catch (\Exception $e) {

            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }
    public function destroy($id)
    {
        $sale = Sale::findOrFail($id);

        $sale->items()->delete();

        $sale->delete();

        return redirect()
            ->route('sales.index')
            ->with('success', 'Cash Sale deleted successfully.');
    }
}