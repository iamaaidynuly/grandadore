<?php

namespace App\Models;

class CompanyOneTimePayment extends AbstractModel
{
    protected $table = 'company_one_time_package';

    public static function action($model, $inputs)
    {

        if (empty($model)) {
            $model = new self;
        }

        $olds = self::where(['package_id' => $inputs['package_id'], 'company_id' => $inputs['company_id']])->update(['status' => 0]);

        $model->company_id = $inputs['company_id'];
        $model->package_id = $inputs['package_id'];
        $model->package_price = $inputs['package_price'];
        $model->status = 1;

        return $model->save();
    }


    public static function deleteItem($model)
    {

        return $model->delete();
    }

    public function package()
    {
        return $this->hasMany('App\Models\OneTimePayment', 'id', 'package_id');
    }

    public function company()
    {
        return $this->hasMany('App\Models\User', 'id', 'company_id');
    }
}
