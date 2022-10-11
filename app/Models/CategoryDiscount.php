<?php

namespace App\Models;


class CategoryDiscount extends AbstractModel
{

    public $timestamps = false;

    public static function action($category_id, $discount)
    {
        $user = self::where('category_id', $category_id)->first();

        if (empty($discount) && !empty($user)) {
            return $user->delete();
        }
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
                $user->category_id = $category_id;
                $user->discount_id = $discount_id;
            }

            return $user->save();
        } else if (!empty($discount)) {
            if (!empty($user)) {
                $user->individual_discount = $discount;
                $user->discount_id = null;
            } else {
                $user = new self();
                $user->category_id = $category_id;
                $user->individual_discount = $discount;
            }

            return $user->save();

        }
        if (!empty($user)) {

            return $user->delete();
        }

        return true;

    }

    public function discount()
    {
        return $this->belongsTo('App\Models\DiscountForUser', 'discount_id', 'id');
    }
}
