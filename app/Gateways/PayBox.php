<?php


namespace App\Gateways;


use App\Models\Order;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use SimpleXMLElement;

class PayBox
{
    protected $config;

    public function __construct()
    {
        $this->config = config('gateways.payBox');
    }

    public function processPayment(Order $order, $description = '')
    {
        $params = [
            'pg_merchant_id' => env('PAYBOX_MERCHANT_ID'),
            'pg_amount' => $order->total,
            'pg_salt' => Str::random(16),
            'pg_order_id' => $order->getFormattedId(),
            'pg_description' => $description,
            'pg_result_url' => route('webhooks.paybox.result'),
            'pg_success_url' => route('webhooks.paybox.success'),
            'pg_testing_mode' => env('PAYBOX_TEST_MODE'),
        ];

        ksort($params);
        array_unshift($params, 'payment.php');
        array_push($params, env('PAYBOX_SECRET_KEY'));
        $params['pg_sig'] = md5(implode(';', $params));
        unset($params[0], $params[1]);

        $query = http_build_query($params);

        header('Location:' . $this->config['requestUrls']['browser'] . '?' . $query);
        exit;
    }

    public function checkPaymentStatus($orderNumber, $paymentId, $salt)
    {
        $client = new Client();
        $url = $this->config['requestUrls']['getStatus'];

        $rawParams = [
            'pg_merchant_id' => env('PAYBOX_MERCHANT_ID'),
            'pg_order_id' => $orderNumber,
            'pg_payment_id' => $paymentId,
            'pg_salt' => $salt,
        ];

        ksort($rawParams);
        array_unshift($rawParams, 'get_status.php');
        array_push($rawParams, env('PAYBOX_SECRET_KEY'));

        $params = [
            [
                'name' => 'pg_merchant_id',
                'contents' => env('PAYBOX_MERCHANT_ID')
            ],
            [
                'name' => 'pg_order_id',
                'contents' => $orderNumber
            ],
            [
                'name' => 'pg_payment_id',
                'contents' => $paymentId
            ],
            [
                'name' => 'pg_salt',
                'contents' => $salt
            ]
        ];

        $params[] = [
            'name' => 'pg_sig',
            'contents' => md5(implode(';', $rawParams))
        ];

        $response = $client->request('POST', $url, ['multipart' => $params]);

        $responseData = new SimpleXMLElement($response->getBody()->getContents());

        return isset($responseData->pg_transaction_status) && $responseData->pg_transaction_status == 'ok';
    }
}
