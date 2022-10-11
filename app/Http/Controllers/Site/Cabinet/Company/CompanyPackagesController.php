<?php

namespace App\Http\Controllers\Site\Cabinet\Company;

use App\Http\Controllers\Site\BaseController;
use App\Http\Controllers\Site\Cabinet\PaymentController;
use App\Models\CompanyPackages;
use App\Models\Packages;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;


class CompanyPackagesController extends BaseController
{

    public function view()
    {

        $data = [];
        $data['title'] = 'Все пакеты';
        $data['packages'] = Packages::all();
        $data['company_package'] = CompanyPackages::where('created_at', '>', Carbon::now()->subMonth(1)->toDateTimeString())->where(['status' => 1, 'company_id' => auth()->user()->id])->pluck('package_id')->toArray();
        if (empty($data['company_package'])) {
            $data['company_package'] = Packages::where('id', 1)->pluck('id')->toArray();
        }

        return view('site.pages.cabinet.company.packages.main', $data);
    }

    public function buy($id)
    {


        $package = Packages::where('id', $id)->firstOrFail();
        $company_package = CompanyPackages::where(['package_id' => $package->id, 'status' => 1, 'company_id' => auth()->user()->id])->where('created_at', '>', Carbon::now()->subMonth(1)->toDateTimeString())->first();
        if (!empty($company_package)) {
            return Redirect::back()->withErrors(['error' => 'Пакет уже есть у вас']);
        } else {
            $company_package = CompanyPackages::where(['status' => 1, 'company_id' => auth()->user()->id])->where('created_at', '>', Carbon::now()->subMonth(1)->toDateTimeString())->first();
            if (!empty($company_package)) {
                if ($company_package->package_id > (int)$id) {
                    return Redirect::back()->withErrors(['error' => 'Вы не можете снизить ваш пакет']);
                }
            } elseif ($id == 1) {
                return Redirect::back()->withErrors(['error' => 'Пакет уже есть у вас']);

            }
        }

        $payment = new PaymentController();

        return $payment->paymentPackage($package, $company_package);


//
//          if($this->bank($package,$company_package)){
//
//
//              $inputs=[];
//              $inputs['company_id']=auth()->user()->id;
//              $inputs['package_id']=$package->id;
//              $inputs['package_price']=$package->package_price;
//              if(!empty($company_package)){
//                  $company_package->status=0;
//                  $company_package->save();
//              }
//              if(CompanyPackages::action(null,$inputs)){
//                  return redirect()->route('company.packages.view');
//              }
//
////              $inputs=[];
////              $inputs['company_id']=auth()->user()->id;
////              $inputs['package_id']=$package->id;
////              $inputs['package_price']=$package->package_price;
////              if(!empty($company_package)){
////                  $company_package->status=0;
////                  $company_package->save();
////              }
//              if(CompanyPackages::action(null,$inputs)){
//                  return redirect()->route('company.packages.view');
//              }
//          }else{
//              dd('bank error');
//          }

    }


    public function bank($package, $company_package)
    {
        return true;
    }
}
