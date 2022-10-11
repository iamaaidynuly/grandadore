<?php

namespace App\Http\Controllers\Site\Cabinet;

use App\Gateways\Kassa24;
use App\Http\Controllers\Site\BaseController;
use App\Models\Basket;
use App\Models\DeliveryRegion;
use App\Models\ItemOrder;
use App\Models\Order;
use App\Models\PickupPoint;
use App\Rules\FormattedPhone;
use App\Services\BasketService\BasketFactory;
use App\Services\BasketService\BasketService;
use App\Services\BasketService\Drivers\DatabaseDriver;
use App\Services\BasketService\Drivers\SessionDriver;
use Illuminate\Contracts\Container\Container;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Gd\Driver;
use MongoDB\Driver\Session;
use Zakhayko\Banners\Models\Banner;
use App\Models\DeliveryCity;

class OrdersController extends BaseController
{
    public function order($id)
    {
        $data = [];
        $data['order'] = Order::getOrderSite($id);
        $data['title'] = __('cabinet.order').' N'.$data['order']->id;
        $data['seo'] = $this->staticSEO($data['title']);
        $data['status'] = Order::getStatus($data['order']->status);

        return view('site.pages.cabinet.order', $data);
    }

    public function createOrder()
    {
        $basketService = BasketFactory::createDriver();

        if (!count(Basket::getUserItems())) {
            return redirect()->route('cabinet.profile.basket');
        }

        if (!auth()->user()->sms_verification) {
            return redirect()->back()->with('phone_verify', 'true');
        }
        $data = [];
        $data['active'] = 'basket';

        $data['seo'] = $this->staticSEO('Оформления заказа');
        $data['regions'] = DeliveryRegion::siteList();
        $data['user'] = authUser();
        $data['basketService'] = $basketService;
        $data['pickupPoints'] = PickupPoint::query()->where('active', 1)->get();


        $breadcrumbs = [
            [
                'title' => __('cabinet.profile settings'),
                'url'   => ''
            ]
        ];
        $data['deliverAll'] = DeliveryRegion::where('id', '>', 0)->get();

        $data['breadcrumbs'] = $breadcrumbs;
//dd($data);
        return view('site.pages.cabinet.order_form', $data);
    }

    public function submitOrder(Request $request)
    {

        $inputs = $request->all();
        $inputs['delivery'] = 1;

        $rules = [
            'name'  => 'required|string|max:255',
            'phone' => ['required', 'string', 'max:255', new FormattedPhone()],
        ];

        if (($inputs['delivery'] ?? 0) != 0) {
            $rules['city_id'] = 'required|integer|exists:delivery_cities,id';
            $rules['address'] = 'required|string|max:255';
        }

        Validator::make($inputs, $rules, [
            'required' => 'Поле обязательно для заполнения.',
            'string'   => 'Поле обязательно для заполнения.',
            'max'      => 'Макс. :max символов.',
            'exists'   => 'Поле обязательно для заполнения.',
            'phone'    => 'Недействительный номер телефона.',
            'integer'  => 'Поле обязательно для заполнения.',
        ])->validate();

        $basketService = BasketFactory::createDriver();

        if (!count($basketService->getItems())) {
            return redirect()->route('cabinet.profile.basket');
        }

        if (!($order = Order::makeOrder($inputs))) {
            return redirect()->route('cabinet.profile.orders.active', ['status' => 'pending']);
        }

        Basket::clear();

        if ($order->payment_method == 'bank') {
            notify('Заказ принят. После подтверждения вы сможете приступить к оплате.');
        } else {
            notify('Заказ принят. Следите за ходом выполнения.');
        }

        return redirect()->route('cabinet.profile.orders.active', ['status' => 'in-process']);
    }

    public function delFromBasket(Request $request, SessionDriver $driver, DatabaseDriver $driverData)
    {
        if (!Auth::check()) {

            $driver->delete($request->itemId);
            return response()->json($driver->getItems()->sum('price'), 201);
        } else {
            $driverData->delete($request->itemId);
            return response()->json($driverData->getItems()->sum('price'), 201);
        }

    }

    public function addOrder(Request $request)
    {
        $addressSelected = $request->input('address_selected');

        if ($addressSelected) {
            $addressSelected = Arr::last(explode('||', $addressSelected));
        }

        $order = Order::makeOrder([
            'delivery'        => $request->input('type_paynament') == 'dostavka-do-dveri',
            'name'            => $request->input('name'),
            'phone'           => $request->input('phone'),
            'city_id'         => $request->input('nasselioni_punkt'),
            'address'         => $request->input('address'),
            'payment_method'  => $request->input('payment_method'),
            'pickup_point_id' => $addressSelected
        ]);

        if ($order->payment_method == 'bank') {
            notify('Заказ принят. После подтверждения вы сможете приступить к оплате.');
        } else {
            notify('Заказ принят. Следите за ходом выполнения.');
        }


        return redirect()->route('cabinet.profile.orders.active')->with('order', 'Profile updated!');
    }

    public function naselionniPunk()
    {

        $request = \Illuminate\Container\Container::getInstance()->make(Request::class);

        $deliverCites = DeliveryCity::where('region_id', $request->id)->get();

        return view('site.components.filterOption', compact('deliverCites'));
    }

    public function changeOrderIncrement(Request $request)
    {
        $basketService = BasketFactory::createDriver();
        $RealSum = 0;
        $Sum = 0;
        foreach ($basketService->getItems() as $row => $item) {

            if ($item->getPrice()) {
                $RealSum += $item->getPrice() * $item->getCount();
                $Sum += $item->getSum();
            }
        }

        $data['realSum'] = $RealSum;
        $data['sum'] = $Sum;
        return $data;
    }
}
