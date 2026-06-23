<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Product;
use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        $customerCount = Customer::count();
        $productCount  = Product::count();

        $recentUsers = Customer::latest()->take(5)->get();

        return view('auth.dashboard', compact(
            'customerCount',
            'productCount',
            'recentUsers'
        ));
    }
}
