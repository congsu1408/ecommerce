<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class VendorController extends Controller
{
    public function dashboard()
    {
        $vendorId = Auth::user()->id;

        // 1.1 Doanh thu theo thời gian
        $revenueDaily = DB::table('order_products')
            ->where('vendor_id', $vendorId)
            ->selectRaw('DATE(created_at) as date, SUM(qty * unit_price) as revenue')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // 1.2 Tỷ lệ đóng góp doanh thu theo sản phẩm
        $revenueByProduct = DB::table('order_products')
            ->where('vendor_id', $vendorId)
            ->selectRaw('product_name, SUM(qty * unit_price) as revenue')
            ->groupBy('product_name')
            ->orderByDesc('revenue')
            ->get();

        // 1.3 So sánh doanh thu giữa các tháng
        $revenueMonthly = DB::table('order_products')
            ->where('vendor_id', $vendorId)
            ->selectRaw('MONTH(created_at) as month, SUM(qty * unit_price) as revenue')
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        // 2.1 Top sản phẩm bán chạy
        $topProducts = DB::table('order_products')
            ->where('vendor_id', $vendorId)
            ->selectRaw('product_name, SUM(qty) as total_sold')
            ->groupBy('product_name')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        // 2.2 Sản phẩm còn tồn kho nhiều nhất
        $mostStockedProducts = DB::table('products')
            ->where('vendor_id', $vendorId)
            ->selectRaw('name, qty')
            ->orderByDesc('qty')
            ->limit(5)
            ->get();

        // 2.3 Danh mục sản phẩm bán chạy nhất
        $popularCategories = DB::table('order_products')
            ->join('products', 'order_products.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->where('order_products.vendor_id', $vendorId)
            ->selectRaw('categories.name as category_name, SUM(order_products.qty) as total_sold')
            ->groupBy('categories.name')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        return view('vendor.dashboard.dashboard', compact(
            'revenueDaily',
            'revenueByProduct',
            'revenueMonthly',
            'topProducts',
            'mostStockedProducts',
            'popularCategories'
        ));
    }
}
