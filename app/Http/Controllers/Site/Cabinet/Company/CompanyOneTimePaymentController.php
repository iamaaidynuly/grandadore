<?php

namespace App\Http\Controllers\Site\Cabinet\Company;

use App\Http\Controllers\Site\BaseController;
use App\Http\Controllers\Site\Cabinet\PaymentController;
use App\Models\CompanyOneTimePayment;
use App\Models\OneTimePayment;
use Carbon\Carbon;


class CompanyOneTimePaymentController extends BaseController
{

    public function view()
    {
        $data = [];
        $data['title'] = 'Все пакеты';
        $data['OneTimePayment'] = OneTimePayment::all();

        $data['company_package'] = CompanyOneTimePayment::where('created_at', '>', Carbon::now()->subWeek()->toDateTimeString())->where(['status' => 1, 'company_id' => auth()->user()->id])->pluck('package_id')->toArray();

        return view('site.pages.cabinet.company.OneTimePayment.main', $data);
    }

    public function buy($id)
    {

        $package = OneTimePayment::where('id', $id)->firstOrFail();


        $payment = new PaymentController();

        return $payment->paymentOneTimePackage($package);


        if ($this->bank($package)) {
            $inputs = [];
            $inputs['company_id'] = auth()->user()->id;
            $inputs['package_id'] = $package->id;
            $inputs['package_price'] = $package->price;
            if (CompanyOneTimePayment::action(null, $inputs)) {
                return redirect()->route('company.one-time-payment.view');
            }
        } else {
            dd('bank error');
        }

    }


    public function bank($package)
    {
        return true;
    }
}
