<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItems;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::latest('orders.created_at')->select('orders.*','users.name','users.email');
        $orders = $orders->leftJoin('users','users.id','orders.user_id');

        if(!empty($request ->get('keyword'))){
            $orders = $orders->where('users.name','like','%'.$request->keyword.'%');
            $orders = $orders->orWhere('users.email','like','%'.$request->keyword.'%');
            $orders = $orders->orWhere('orders.id','like','%'.$request->keyword.'%');

        }
        $orders = $orders->paginate(10);
        $email=env('admin_email');
        return view('admin.order.list',[
            'orders'=> $orders,
            'email'=> $email,
        ]);
    }
    public function detail($orderId)
    {
        $order = Order::select('orders.*','countries.name as countryName')
            ->where('orders.id',$orderId)
            ->leftJoin('countries','countries.id','orders.country_id')
            ->first();

        $orderItems = OrderItems::where('order_id',$orderId)->get();

        return view('admin.order.details',[
            'order'=> $order,
            'orderItems'=> $orderItems,
        ]);
    }

    public function changeOrderStatus(Request $request, $orderId)
    {
        $order = Order::find($orderId);
        $order->status = $request->status;
        $order->shipped_date = $request->shipped_date;
        $order->save();

        $message = 'Order status updated successfully';
        session()->flash('success',$message);
        return response()->json([
           'status'=> true,
           'message'=>$message,
        ]);
    }

    public function sendInvoiceEmail(Request $request, $orderId)
    {
        orderEmail($orderId,$request->userType);

        $message = 'Order status sent successfully';

        session()->flash('success',$message);
        return response()->json([
            'status'=> true,
            'message'=>$message,
        ]);
    }
}
