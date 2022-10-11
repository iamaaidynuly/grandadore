<?php

namespace App\Models;

use App\Http\Traits\HasTranslations;
use App\Services\BasketService\BasketFactory;
use App\Traits\InteractsWithCalendarDays;
use App\ValueObjects\BasketItem;
use Illuminate\Contracts\Container\BindingResolutionException;
use Snowfire\Beautymail\Beautymail;

class Order extends AbstractModel
{
    use HasTranslations, InteractsWithCalendarDays;

    public const STATUS_DECLINED = -1;
    public const STATUS_NEW = 0;
    public const STATUS_PENDING = 1;
    public const STATUS_DONE = 2;

    public const STATUSES = [
        self::STATUS_DECLINED => 'declined',
        self::STATUS_NEW      => 'new',
        self::STATUS_PENDING  => 'pending',
        self::STATUS_DONE     => 'done',
    ];

    public const PROCESS = [
        0 => 'Заказ принят',
        1 => 'Собирается на складе',
        2 => 'Доставляется',
        3 => 'Доставлен',
    ];

    protected $casts = [
        'options' => 'array',
    ];


    public function getPaymentMethodNameAttribute()
    {
        if ($this->payment_method == 'bank') return 'Безналичная оплата';
        else return 'Наличными на месте';
    }

    public function getDeliveryMethodNameAttribute()
    {
        if ($this->delivery) return 'Доставка до двери';
        else return 'Самовывоз';
    }

    public function getStatusTypeAttribute()
    {
        return self::STATUSES[$this->status] ?? null;
    }

    public function getProcessTypeAttribute()
    {
        return self::PROCESS[$this->process] ?? null;
    }

    public static function getOrdersWithStatus($status, $user_id = null)
    {
        $result = self::where('status', $status);
        if ($user_id !== null) $result->where('user_id', $user_id);

        return $result->with(['items', 'user'])->sort()->get();
    }

    public function getStatusHtmlAttribute()
    {
        switch ($this->status) {
            case self::STATUS_DECLINED:
                $result = '<span class="text-warning">Отклоненный</span>';
                break;
            case self::STATUS_PENDING:
                $result = '<span class="text-danger">В процессе</span>';
                break;
            case self::STATUS_DONE:
                $result = '<span class="text-success">Выполненный</span>';
                break;
            default:
                $result = '<span class="text-info">Новый</span>';
        }

        return $result;
    }

    public static function makeOrder($inputs)
    {
        $basketService = BasketFactory::createDriver();
        $delivery = (int) $inputs['delivery'] ?? false;
        $order = new self;

        $order->user_id = authUser()->id;
        $order->name = $inputs['name'];
        $order->phone = $inputs['phone'];
        $order->delivery = $delivery;
        $order->sum = $basketService->getBasketTotal(true);
        $order->real_sum = $basketService->getBasketTotal(false);
        $order->provider_total = $basketService->getBasketTotal(false);

        if ($delivery) {
            $city = DeliveryCity::getItem($inputs['city_id']);
            $order->region_id = $city->region->id;
            $order->region_name = $city->region->title;
            $order->city_id = $city->id;
            $order->city_name = $city->title;
            $order->address = $inputs['address'];
            if ($order->sum >= (int) $city->min_price) {
                $order->delivery_price = 0;
            } else {
                $order->delivery_price = $city->price;
            }
        } else {
            $pickup_point = PickupPoint::getItem($inputs['pickup_point_id']);
            $order->pickup_point_id = $pickup_point->id;
            $order->pickup_point_address = $pickup_point->address;
        }
        $order->total = $basketService->getBasketTotal(true) + ($order->delivery_price ?? 0);
        $order->status = self::STATUS_NEW;
        $order->payment_method = ($inputs['payment_method'] ?? null) == 'bank' ? 'bank' : 'cash';
        $order->save();

        $toAttachData = $basketService->getItems()->map(function (BasketItem $basketItem) {
            return [
                'items_id'   => $basketItem->getItemId(),
                'color_id'   => $basketItem->getColor()['id']??null,
                'size_id'    => $basketItem->getSize()['id']??null,
                'price'      => $basketItem->getDiscountedPrice(),
                'real_price' => $basketItem->getPrice(),
                'count'      => $basketItem->getCount(),
                'sum'        => $basketItem->getSum(),
                'name'       => $basketItem->getItemTitle(),
                'code'       => $basketItem->getItemCode(),
            ];
        });
        $order->orderOriginalItems()->attach($toAttachData);

        $user_emails = [
            Banner::get('info')->data->contact_email
        ];
        if (!empty($user_emails) && count($user_emails)) {
            foreach ($user_emails as $user_email) {
                if (is_email($user_email)) {
                    $url = route('admin.orders.view', ['id' => $order->id]);
                    try {
                        app()->make(Beautymail::class)->send('site.notifications.new_order', [
                            'url' => $url
                        ], function ($message) use ($user_email) {
                            $message->from(env('MAIL_FROM_ADDRESS'))
                                ->to($user_email, env('APP_NAME'))
                                ->subject('Новый заказ на сайте');
                        });
                    } catch (BindingResolutionException $e) {
                    }
                }
            }
        }

        Basket::clear();

        return $order;
    }

    public static function getItem($id)
    {
        return self::with('items')->findOrFail($id);
    }

    public function items()
    {
        return $this->hasMany(ItemOrder::class, 'order_id', 'id')/*->withPivot('count', 'price', 'real_price', 'name', 'code', 'sum')*/ ;
    }

    public function orderOriginalItems()
    {
        return $this->belongsToMany(Items::class)->with('company')
            ->withPivot('count', 'price', 'real_price', 'name', 'code', 'sum', 'items_company_id', 'items_company_price', 'items_company_percent');
    }

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function products()
    {
        return $this->hasMany('App\Models\OrderProduct', 'order_id', 'id');
    }

    public function scopeSort($q)
    {
        return $q->orderBy('id', 'desc');
    }

    public static function clear($status)
    {
        return self::where('status', $status)->delete();
    }

    public static function getCount($status)
    {
        return self::where('status', $status)->count();
    }

    public static function getOrders($status = null, $only_count = false)
    {
        $query = new self();
        if ($status !== null) $query = $query->where('status', $status);
        if ($only_count) return $query->count();

        return $query->with('user')->sort()->get();
    }

    public static function getOrdersSite($status = null, $only_count = false)
    {
        $query = self::where('user_id', User::auth()->id);
        if ($status !== null) $query = $query->where('status', $status);
        if ($only_count) return $query->count();

        return $query->with('user')->sort()->get();
    }

    public static function getOrderSite($id)
    {
        return self::where(['id' => $id, 'user_id' => User::auth()->id])->with('products')->firstOrFail();
    }

    public static function getStatus($status)
    {
        switch ($status) {
            case -1:
                return 'declined';
            case 1:
                return 'accepted';
            default:
                return 'pending';
        }
    }

    public function getFormattedId()
    {
        return str_pad($this->id, 8, '0', STR_PAD_LEFT);
    }

    public function order()
    {
        $this->hasMany(ItemOrder::class, 'items_id', 'id');
    }

}
