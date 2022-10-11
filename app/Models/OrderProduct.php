<?php

namespace App\Models;

use App\Http\Traits\HasTranslations;

class OrderProduct extends AbstractModel
{
    use HasTranslations;

    public $translatable = ['product_title'];
    public $timestamps = false;
    protected $casts = [
        'options' => 'array',
    ];

    public static function attach($order_id, $products)
    {
        $order_products = array_map(function ($a) use ($order_id) {
            $a['product_title'] = json($a['product_title']);
            $a['options'] = json($a['options']);
            $a['order_id'] = $order_id;

            return $a;
        }, $products);
        self::insert($order_products);
    }
}
