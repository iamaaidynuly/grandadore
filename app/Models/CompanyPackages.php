<?php

namespace App\Models;

class CompanyPackages extends AbstractModel
{
    protected $table = 'company_package';

    public static function action($model, $inputs)
    {

        if (empty($model)) {
            $model = new self;
        }
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
        return $this->hasMany('App\Models\Packages', 'id', 'package_id');
    }

    public function company()
    {
        return $this->hasMany('App\Models\User', 'id', 'company_id');
    }
}
