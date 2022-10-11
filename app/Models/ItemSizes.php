<?php

namespace App\Models;


use App\Services\ExchangeRateDetector\ExchangeRateDetector;

class ItemSizes extends AbstractModel
{
    public $timestamps = false;

    protected $fillable = [
        'name', 'price'
    ];

    public function item()
    {
        return $this->belongsTo(Items::class, 'item_id');
    }

    public function exchangedPrice() : int
    {
        /** @var ExchangeRateDetector $detector */
        $detector = app()->get(ExchangeRateDetector::class);

        return $this->price * $detector->getRate();
    }

    public function getExchangedPriceAttribute() : int
    {
        return $this->exchangedPrice();
    }
}
