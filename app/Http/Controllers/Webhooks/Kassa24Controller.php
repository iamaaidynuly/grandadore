<?php

namespace App\Http\Controllers\Webhooks;

use App\Gateways\Kassa24;
use App\Http\Controllers\Site\BaseController;
use App\Models\Order;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;

class Kassa24Controller extends BaseController
{
    protected $successCodes = [
        1,
        2,
    ];

    public function result(Request $request)
    {
        file_put_contents(public_path('payment.log'), json_encode($request->all()));

        if (in_array($request->input('status'), $this->successCodes)) {
            $order = Order::query()->where('id', $request->input('orderId'))->first();
            $order->paid = 1;
            $order->save();

            return json_encode([
                'accepted' => true
            ]);
        }

        return json_encode([
            'accepted' => false
        ]);
    }

    public function success(Request $request)
    {
        /** @var Order $order */
        $order = Order::query()->where('order_id', $request->input('ecom_transaction_id'))->first();

        $gateway = new Kassa24();
        $response = [];

        try {
            $response = $gateway->getPaymentStatus($order);
        } catch (ClientException $e) {
            $response['errMessage'] = 'Произошла неизвестная ошибка. Попробуйте снова через некоторое время.';
        }

        if (isset($response['status']) && in_array($response['status'], $this->successCodes)) {
            return view('site.pages.order-result.success');
        } else {
            return view('site.pages.order-result.error', [
                'errorText' => $response['errMessage']
            ]);
        }
    }
}
