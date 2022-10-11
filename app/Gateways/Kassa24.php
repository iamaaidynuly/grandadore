<?php

namespace App\Gateways;

use App\Models\Order;
use Exception;
use GuzzleHttp\Client;

class Kassa24
{
    protected $createPaymentUri = 'https://ecommerce.pult24.kz/payment/create';
    protected $paymentStatusUri = 'https://ecommerce.pult24.kz/payment/status';
    protected $approvePaymentUri = 'https://ecommerce.pult24.kz/payment/processing/end';

    public function processPayment(Order $order, $description = '')
    {
        $paymentData = [
            "merchantId"   => env('KASSA24_USERNAME'),
            "callbackUrl"  => route('webhooks.kassa24.result'),
            "orderId"      => (string) $order->id,
            "description"  => 'Покупка товаров на сайте Grandadore.com',
            "demo"         => env('KASSA24_TEST_MODE', true),
            "returnUrl"    => route('webhooks.kassa24.success'),
            "amount"       => ((int) $order->total) * 100, // the amount must be multiplied by 100
            'customerData' => [
                'email' => $order->user->email,
                'phone' => $order->user->phone,
            ],
        ];

        $paymentDataString = json_encode($paymentData, JSON_UNESCAPED_UNICODE);

        $curl = curl_init($this->createPaymentUri);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $paymentDataString);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: Basic ".base64_encode(env('KASSA24_USERNAME').':'.env('KASSA24_PASSWORD')),
            'Content-Length: '.strlen($paymentDataString)
        ]);

        $result = curl_exec($curl);

        curl_close($curl);

        return json_decode($result, true);
    }

    public function getPaymentStatus(Order $order)
    {
        $client = new Client();

        $query = http_build_query([
            'orderid' => $order->id,
            'id'      => $order->order_id,
        ]);

        $response = $client->get($this->paymentStatusUri.'?'.$query, [
            'headers' => [
                'Authorization' => "Basic ".base64_encode(env('KASSA24_USERNAME').':'.env('KASSA24_PASSWORD')),
            ]
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @throws \Exception
     */
    public function approvePayment(Order $order)
    {
        $data_string = json_encode([
            'ID'     => $order->order_id,
            'action' => 'approve',
        ], JSON_UNESCAPED_UNICODE);

        $curl = curl_init($this->approvePaymentUri);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        $headers = array(
            "Content-Type: application/json",
            "Authorization: Basic ".base64_encode(env('KASSA24_USERNAME').':'.env('KASSA24_PASSWORD')),
            'Content-Length: '.strlen($data_string)
        );
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        if (curl_exec($curl) === false) {
            throw new Exception(curl_error($curl));
        }
        $result = curl_exec($curl);
        curl_close($curl);

        $result = json_decode($result, true);

        if ($result['status'] != 1) {
            return $result['error'];
        }

        return true;
    }
}
