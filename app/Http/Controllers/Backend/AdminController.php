<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Brand;
use App\Models\Category;
use App\Models\NewsletterSubscriber;
use App\Models\Order;
use App\Models\ProductReview;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Stripe\Review;
use Illuminate\Support\Facades\DB;


class AdminController extends Controller
{
    public function dashboard()
    {
        $revenueDaily = Order::selectRaw('DATE(created_at) as date, SUM(sub_total) as revenue')
            ->whereNotIn('order_status', ['canceled', 'null']) // Loại bỏ trạng thái "canceled" và "null"
            ->where('payment_status', 1) // Chỉ lấy các đơn hàng đã thanh toán
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();
        // dd($revenueDaily);
        $revenueWeekly = Order::selectRaw('WEEK(created_at) as week, SUM(sub_total) as revenue')
            ->whereNotIn('order_status', ['canceled', 'null']) // Loại bỏ trạng thái "canceled" và "null"
            ->where('payment_status', 1) // Chỉ lấy các đơn hàng đã thanh toán
            ->groupBy('week')
            ->orderBy('week', 'asc')
            ->get();
        // dd($revenueWeekly);
        $revenueMonthly = Order::selectRaw('MONTH(created_at) as month, SUM(sub_total) as revenue')
            ->whereNotIn('order_status', ['canceled', 'null']) // Loại bỏ trạng thái "canceled" và "null"
            ->where('payment_status', 1) // Chỉ lấy các đơn hàng đã thanh toán
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

            $topProducts = DB::table('order_products')
            ->join('products', 'order_products.product_id', '=', 'products.id') // Join với bảng products
            ->select('products.name as product_name', DB::raw('SUM(order_products.qty) as total_sold'))
            ->groupBy('products.name')
            ->orderByDesc('total_sold')
            ->limit(value: 3)
            ->get();
        
        $mostStockedProducts = DB::table('products')
            ->select('name', 'qty')
            ->where('qty', '>', 0) // Chỉ lấy sản phẩm có tồn kho
            ->orderByDesc('qty') // Sắp xếp theo số lượng tồn kho giảm dần
            ->limit(3)
            ->get();
        

        $popularCategories = DB::table('order_products')
            ->join('products', 'order_products.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('categories.name as category_name', DB::raw('SUM(order_products.qty) as total_sold'))
            ->groupBy('categories.name')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        // Tỷ lệ đóng góp doanh thu
        $revenueShare = DB::table('order_products')
            ->join('vendors', 'order_products.vendor_id', '=', 'vendors.id')
            ->selectRaw('vendors.shop_name, SUM(order_products.qty * order_products.unit_price) as revenue')
            ->groupBy('order_products.vendor_id', 'vendors.shop_name')
            ->orderByDesc('revenue')
            ->get();

        // Hiệu suất giao hàng
        $deliveryPerformance = DB::table('orders')
            ->join('order_products', 'orders.id', '=', 'order_products.order_id')
            ->join('vendors', 'order_products.vendor_id', '=', 'vendors.id')
            ->selectRaw('vendors.shop_name, 
        COUNT(CASE WHEN orders.order_status = "delivered" THEN 1 END) AS delivered_orders, 
        COUNT(*) AS total_orders, 
        (COUNT(CASE WHEN orders.order_status = "delivered" THEN 1 END) / COUNT(*)) * 100 AS delivery_performance')
            ->groupBy('order_products.vendor_id', 'vendors.shop_name')
            ->orderByDesc('delivery_performance')
            ->get();

        // Phân tích đánh giá
        $vendorReviews = DB::table('product_reviews')
            ->join('vendors', 'product_reviews.vendor_id', '=', 'vendors.id')
            ->selectRaw('vendors.shop_name, 
        AVG(CAST(product_reviews.rating AS DECIMAL)) AS average_rating, 
        COUNT(CASE WHEN CAST(product_reviews.rating AS DECIMAL) <= 2 THEN 1 END) AS negative_reviews')
            ->groupBy('product_reviews.vendor_id', 'vendors.shop_name')
            ->orderByDesc('average_rating')
            ->get();


        return view('admin.dashboard', compact(
            'revenueDaily',
            'revenueWeekly',
            'revenueMonthly',
            'topProducts',
            'mostStockedProducts',
            'popularCategories',
            'revenueShare',
            'deliveryPerformance',
            'vendorReviews'
        ));
    }

    public function login()
    {
        return view('admin.auth.login');
    }
}