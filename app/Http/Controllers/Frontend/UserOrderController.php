<?php

namespace App\Http\Controllers\Frontend;

use App\DataTables\UserOrderDataTable;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class UserOrderController extends Controller
{
    public function index(UserOrderDataTable $dataTable)
    {
        return $dataTable->render('frontend.dashboard.order.index');
    }

    public function show(string $id)
    {
        $id = (int) $id;
        $order = Order::findOrFail($id);
        return view('frontend.dashboard.order.show', compact('order', 'id'));
    }
    public function destroy(string $id)
    {
        $order = Order::findOrFail($id);
        $totalAmount = $order->amount;
        $user = User::find($order->user_id);
        if ($user) {
            $user->wallet_balance += $totalAmount;
            $user->save();
        }
        $order->orderProducts()->delete();
        $order->transaction()->delete();
        $order->delete();
        return response([
            'status' => 'success',
            'message' => 'Order deleted successfully and amount refunded to wallet!',
            'wallet_balance' => $user ? $user->wallet_balance : null
        ]);
    }

}
