<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ProductReview;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserDashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::user()->id;

        // 1. Lịch sử mua sắm
        $purchaseHistory = DB::table('orders')
            ->where('user_id', $userId)
            ->selectRaw('DATE(created_at) as date, COUNT(id) as total_orders')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // 2. Giá trị mua sắm
        $purchaseValue = DB::table('orders')
            ->where('user_id', $userId)
            ->selectRaw('DATE(created_at) as date, SUM(amount) as total_spent')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // 3. Sản phẩm đã mua nhiều nhất
        $topPurchasedProducts = DB::table('order_products')
            ->join('orders', 'order_products.order_id', '=', 'orders.id') // Join với bảng orders
            ->join('products', 'order_products.product_id', '=', 'products.id') // Join với bảng products
            ->where('orders.user_id', $userId) // Lọc theo user_id từ bảng orders
            ->selectRaw('products.name, SUM(order_products.qty) as total_quantity')
            ->groupBy('products.name')
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->get();


        return view('frontend.dashboard.dashboard', compact(
            'purchaseHistory',
            'purchaseValue',
            'topPurchasedProducts'
        ));
    }
}
