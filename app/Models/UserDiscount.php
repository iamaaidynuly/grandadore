<?php

namespace App\Models;

//use Illuminate\Support\Facades\File;
//use Intervention\Image\Facades\Image;

class UserDiscount extends AbstractModel
{

    public $timestamps = false;

    public static function action($user_id, $discount)
    {

        $user = self::where('user_id', $user_id)->first();

        if (empty($discount)) {
            return $user->delete();
        }
        $discount = $discount;
        if (json_decode($discount) && !empty(json_decode($discount)->discount_id)) {
            $discount_id = json_decode($discount)->discount_id;
            $discount = json_decode($discount)->discount;
        }


        if (!empty($discount_id)) {
            if (!empty($user)) {
                $user->discount_id = $discount_id;
                $user->individual_discount = null;
            } else {
                $user = new self();
                $user->user_id = $user_id;
                $user->discount_id = $discount_id;
            }

            return $user->save();
        } else if (!empty($discount)) {
            if (!empty($user)) {
                $user->individual_discount = $discount;
                $user->discount_id = null;
            } else {
                $user = new self();
                $user->user_id = $user_id;
                $user->individual_discount = $discount;
            }

            return $user->save();

        }

        return $user->delete();

    }

    public function discount()
    {
        return $this->belongsTo('App\Models\DiscountForUser', 'discount_id', 'id');
    }

}
