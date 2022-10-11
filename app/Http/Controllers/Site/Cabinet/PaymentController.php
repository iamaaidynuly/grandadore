<?php

namespace App\Http\Controllers\Site\Cabinet;

use App\Gateways\Kassa24;
use App\Http\Controllers\Site\BaseController;
use App\Models\CompanyOneTimePayment;
use App\Models\CompanyPackages;
use App\Models\OneTimePayment;
use App\Models\Order;
use App\Models\Packages;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Zakhayko\Banners\Models\Banner;

class PaymentController extends BaseController
{


    protected static $param = [
        'userName'  => 'impharm_test',
        'password'  => 'impharm2020***',
        'returnUrl' => '',
        'MERCHANT'  => 'ECOMM001',
        'TERMINAL'  => 'ECOMM001',
        'DESC'      => '',
        'P_SIGN'    => '',
        'NONCE'     => '',
    ];

    public function __construct()
    {
        self::$param['returnUrl'] = route('cabinet.paymentCheck', ['redirect' => true]);


    }

    function convert_text($text)
    {
        $t = $text;

        $specChars = array(
            '!'  => '%21', '"' => '%22',
            '#'  => '%23', '$' => '%24', '%' => '%25',
            '&'  => '%26', '\'' => '%27', '(' => '%28',
            ')'  => '%29', '*' => '%2A', '+' => '%2B',
            ','  => '%2C', '-' => '%2D',
            '/'  => '%2F', ':' => '%3A', ';' => '%3B',
            '<'  => '%3C', '=' => '%3D', '>' => '%3E',
            '?'  => '%3F', '@' => '%40', '[' => '%5B',
            '\\' => '%5C', ']' => '%5D', '^' => '%5E',
            '_'  => '%5F', '`' => '%60', '{' => '%7B',
            '|'  => '%7C', '}' => '%7D', '~' => '%7E',
            ','  => '%E2%80%9A', ' ' => '%20'
        );

        foreach ($specChars as $k => $v) {
            $t = str_replace($k, $v, $t);
        }

        return $t;
    }

    public function payment(Request $request)
    {
        if (!authUser()) {
            abort(404);
        }

        $encryptedId = $request->input('order_id');

        $orderId = decrypt($encryptedId);
        $order = Order::where('id', $orderId)->with(['items', 'user'])->firstOrFail();
        $order_desc = '';
        if (!empty($order->items) && count($order->items)) {
            foreach ($order->items as $item) {
                $order_desc = $order_desc.$item->title.' , ';
            }
        } else {
            abort(403);
        }

        if ($order->payment_method == 'bank' && !(bool) $order->paid) {
            $gateway = new Kassa24();

            $paymentResult = $gateway->processPayment($order);

            if (!empty($paymentResult['url']) && !empty($paymentResult['id'])) {
                $order->order_id = $paymentResult['id'];
                $order->save();

                return redirect()->to($paymentResult['url']);
            }

            return redirect()->route('cabinet.basket');
        }
    }

    public function paymentCheck(Request $request)
    {
        if (!Auth::check() || \auth()->user()->type == 1) {
            abort(404);
        }

        if ($request->res_code === '0') {
            $model = Order::where('random_order_id', (int) $request->order)->firstOrFail();
            $model->paid = 1;
            $model->order_id = $request->mpi_order ?? null;
            $model->sign = $request->sign ?? null;
            $model->random_order_id = (int) $request->order;
            $model->save();

            return redirect()->route('cabinet.profile.history')->with('success_payment', 'true');
        } else {
            return redirect()->route('cabinet.profile.history')->with('error_payment', 'true');
        }
    }

    //buy Package
    public function paymentPackage($package, $company_package)
    {
        if (!Auth::check() || \auth()->user()->type != 1) {
            abort(404);
        }
        self::$param['returnUrl'] = route('cabinet.paymentPackageCheck', ['package_id' => $package->id]).'?redirect=true';
        self::$param['DESC'] = 'Заказ';
        self::$param['NONCE'] = rand(100000, 9999999999);
        $bank_order_id = rand(1000000, 9999999999);
        if (!empty($company_package)) {
            $company_package->random_order_id = $bank_order_id;
            $company_package->save();
        } else {
            $model = new CompanyPackages();
            $model->status = 0;
            $model->random_order_id = $bank_order_id;
            $model->company_id = auth()->user()->id;
            $model->package_id = $package->id;
            $model->package_price = $package->package_price;
            $model->save();
        }
        $p_sign = hash("sha512", '123456781234567812345678'.$bank_order_id.";".(int) $package->package_price.";"."KZT;".self::$param["MERCHANT"].";".self::$param["TERMINAL"].";".self::$param['NONCE'].";;".preg_replace("/\n|\r/", "", self::$param['DESC']).";;".\auth()->user()->email.";".self::$param['returnUrl'].";;;");
        self::$param['P_SIGN'] = $p_sign;

        return redirect('https://46.101.210.91/ecom/api?ORDER='.$bank_order_id.'&AMOUNT='.(int) $package->package_price.'&CURRENCY=KZT&MERCHANT='.self::$param["MERCHANT"].'&TERMINAL='.self::$param["TERMINAL"].'&LANGUAGE=ru&CLIENT_ID=&DESC='.SELF::$param['DESC'].'&DESC_ORDER=&NAME='.auth()->user()->name.'&EMAIL='.$this->convert_text(\auth()->user()->email).'&BACKREF='.$this->convert_text(self::$param['returnUrl']).'&NONCE='.self::$param['NONCE'].'&Ucaf_Flag=&Ucaf_Authentication_Data=&P_SIGN='.self::$param['P_SIGN'.'']);
    }

    public function paymentPackageCheck($package_id, Request $request)
    {
        if (!Auth::check() || \auth()->user()->type != 1) {
            abort(404);
        }
        if ($request->res_code === '0') {
            $oldmodel = CompanyPackages::where('random_order_id', (int) $request->order)->firstOrFail();
            $oldmodel->status = 0;
            $oldmodel->random_order_id = null;
            $oldmodel->save();
            $package = Packages::where('id', $package_id)->firstOrFail();
            $model = new CompanyPackages();
            $model->status = 1;
            $model->order_id = $request->mpi_order ?? null;
            $model->sign = $request->sign ?? null;
            $model->random_order_id = (int) $request->order;
            $model->company_id = \auth()->user()->id;
            $model->package_id = $package_id;
            $model->package_price = $package->package_price;
            $model->save();

            return redirect()->route('company.packages.view')->with('success_payment', 'true');
        } else {
            return redirect()->route('company.packages.view')->with('error_payment', 'true');
        }
    }
    //end buy package

    //buy oneTime package
    public function paymentOneTimePackage($package)
    {
        if (!Auth::check() || \auth()->user()->type != 1) {
            abort(404);
        }
        self::$param['returnUrl'] = route('cabinet.paymentOneTimePackageCheck', ['package_id' => $package->id]).'?redirect=true';
        self::$param['DESC'] = 'Заказ';
        self::$param['NONCE'] = rand(100000, 9999999999);
        $bank_order_id = rand(1000000, 9999999999);
        $old_package = CompanyOneTimePayment::where('created_at', '>', Carbon::now()->subWeek()->toDateTimeString())->where(['status' => 1, 'company_id' => auth()->user()->id, 'package_id' => $package->id])->first();
        if (!empty($old_package)) {
            $old_package->random_order_id = $bank_order_id;
            $old_package->save();
        } else {
            $model = new CompanyOneTimePayment();
            $model->status = 0;
            $model->random_order_id = $bank_order_id;
            $model->company_id = auth()->user()->id;
            $model->package_id = $package->id;
            $model->package_price = $package->price;
            $model->save();
        }
        $p_sign = hash("sha512", '123456781234567812345678'.$bank_order_id.";".(int) $package->price.";"."KZT;".self::$param["MERCHANT"].";".self::$param["TERMINAL"].";".self::$param['NONCE'].";;".preg_replace("/\n|\r/", "", self::$param['DESC']).";;".\auth()->user()->email.";".self::$param['returnUrl'].";;;");
        self::$param['P_SIGN'] = $p_sign;

        return redirect('https://46.101.210.91/ecom/api?ORDER='.$bank_order_id.'&AMOUNT='.(int) $package->price.'&CURRENCY=KZT&MERCHANT='.self::$param["MERCHANT"].'&TERMINAL='.self::$param["TERMINAL"].'&LANGUAGE=ru&CLIENT_ID=&DESC='.SELF::$param['DESC'].'&DESC_ORDER=&NAME='.auth()->user()->name.'&EMAIL='.$this->convert_text(\auth()->user()->email).'&BACKREF='.$this->convert_text(self::$param['returnUrl']).'&NONCE='.self::$param['NONCE'].'&Ucaf_Flag=&Ucaf_Authentication_Data=&P_SIGN='.self::$param['P_SIGN'.'']);
    }

    public function paymentOneTimePackageCheck($package_id, Request $request)
    {
        if (!Auth::check() || \auth()->user()->type != 1) {
            abort(404);
        }
        if ($request->res_code === '0') {
            $package = OneTimePayment::where('id', $package_id)->firstOrFail();
            $oldmodel = CompanyOneTimePayment::where('random_order_id', (int) $request->order)->firstOrFail();
            $oldmodel->status = 0;
            $oldmodel->random_order_id = null;
            $oldmodel->save();
            $model = new CompanyOneTimePayment();
            $model->status = 1;
            $model->order_id = $request->mpi_order ?? null;
            $model->sign = $request->sign ?? null;
            $model->random_order_id = (int) $request->order;
            $model->company_id = \auth()->user()->id;
            $model->package_id = $package_id;
            $model->package_price = $package->price;
            $model->save();

            return redirect()->route('company.one-time-payment.view')->with('success_payment', 'true');
        } else {
            return redirect()->route('company.one-time-payment.view')->with('error_payment', 'true');
        }
    }
    //endOneTime package
}
