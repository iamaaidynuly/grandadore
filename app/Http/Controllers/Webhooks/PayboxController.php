<?php

namespace App\Http\Controllers\Webhooks;

use App\Gateways\PayBox;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PayboxController extends Controller
{
    const PAYMENT_STATUS_OK = 'ok';
    const PAYMENT_STATUS_REJECTED = 'rejected';
    const PAYMENT_STATUS_ERROR = 'error';

    protected $gateway;

    public function __construct(PayBox $payBox)
    {
        $this->gateway = $payBox;
    }

    public function result(Request $request)
    {
        $data = $request->all();
        $data['pg_order_id'] = (int)$data['pg_order_id'];

        $validator = Validator::make($data, [
            'pg_order_id' => 'required|exists:orders,id',
            'pg_payment_id' => 'required',
            'pg_salt' => 'required',
            'pg_sig' => 'required',
        ]);

        if ($validator->fails() && $data['pg_can_reject'] == '1') {
            return response()->xml([
                'pg_status' => static::PAYMENT_STATUS_ERROR,
                'pg_salt' => $data['pg_salt'],
                'pg_sig' => $data['pg_sig'],
                'pg_description' => 'Order number validation error',
            ]);
        }

        if ($data['pg_result'] == '1') {
            $order = Order::with('items')->where('id', $data['pg_order_id'])->first();
            $order->paid = true;
            $order->order_id = $data['pg_payment_id'];
            $order->sign = $data['pg_sig'];

            if ($order->save()) {
                return response()->xml([
                    'pg_status' => static::PAYMENT_STATUS_OK,
                    'pg_salt' => $data['pg_salt'],
                    'pg_sig' => $data['pg_sig'],
                ]);
            }
        }

        return response()->xml([
            'pg_status' => static::PAYMENT_STATUS_REJECTED,
            'pg_salt' => $data['pg_salt'],
            'pg_sig' => $data['pg_sig'],
        ]);
    }

    public function success(Request $request)
    {
        $data = $request->all();
        $data['pg_order_id'] = (int)$data['pg_order_id'];

        $validator = Validator::make($data, [
            'pg_order_id' => 'required|exists:orders,id',
            'pg_payment_id' => 'required',
            'pg_salt' => 'required',
            'pg_sig' => 'required',
        ]);


        if (!$validator->fails()) {
            if ($this->gateway->checkPaymentStatus(
                $request->query('pg_order_id'),
                $request->query('pg_payment_id'),
                $request->query('pg_salt')
            )) {
                notify('Заказ успешно оплачен. Следите за изменениями хода процесса заказа.', 'success');

                return redirect()->route('cabinet.profile.orders.active', ['status' => 'in-process']);
            }
        }

        notify('Оплата не была произведена либо произошла ошибка в процессе оплаты.', 'error');

        return redirect()->route('cabinet.profile.orders.active', ['status' => 'in-process']);
    }
}
