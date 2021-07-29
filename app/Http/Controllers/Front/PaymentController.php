<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Front\Order;
use Illuminate\Http\Request;

class PaymentController extends Controller
{

    public function index()
    {
        $this->initPaymentGateway();

        $params = array(
            'transaction_details' => array(
                'order_id' => rand(),
                'gross_amount' => 10000,
            ),
            'customer_details' => array(
                'first_name' => 'budi',
                'last_name' => 'pratama',
                'email' => 'budi.pra@example.com',
                'phone' => '08111222333',
            ),
        );

        // $snapToken = \Midtrans\Snap::getSnapToken($params);

// dd($snapToken);
        return view('ecommerce.payment.index', compact('snapToken'));
    }

    public function getPaymentFromOrderId($id)
    {
        $order = Order::with(['orderproducts', 'orderproducts.products'])->findOrFail($id);
        $itemDetails = [];
        for ($i=0; $i<$order->orderproducts->count(); $i++)
        {
            $itemDetails[$i]['id']  = $order->orderproducts[$i]->products->id;
            $itemDetails[$i]['name']  = $order->orderproducts[$i]->products->name;
            $itemDetails[$i]['price'] = $order->orderproducts[$i]->products->price;
            $itemDetails[$i]['quantity'] = $order->orderproducts[$i]->amount;
        }

        $this->initPaymentGateway();
        // return $itemDetails;
        $params = array(
            'transaction_details' => array(
                'order_id' => $order->code,
                'gross_amount' => $order->sub_total,
            ),
            'item_details' => $itemDetails,
            'customer_details' => array(
                'first_name' => auth()->guard('members')->user()->first_name,
                'last_name' => auth()->guard('members')->user()->last_name,
                'email' => auth()->guard('members')->user()->email,
                'phone' => auth()->guard('members')->user()->phone,
            ),
            // 'enabled_payments' => array(
            //     'bca_va'
            // )
        );

        $snapToken = \Midtrans\Snap::getSnapToken($params);
        // $snapTokenn = \Midtrans\Snap::createTransaction($params)->redirect_url;

//         $token = [
//             'token1' => $snapToken,
//             'token2' => $snapTokenn
//         ];
// dd($snapToken);
        return view('ecommerce.payment.index', compact('snapToken'));
    }

    public function getPaymentStatusOrderId($id)
    {
        $this->initPaymentGateway();

        $status = \Midtrans\Transaction::status($id);

        return json_encode($status);
    }
}
