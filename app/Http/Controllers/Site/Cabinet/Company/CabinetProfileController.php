<?php

namespace App\Http\Controllers\Site\Cabinet\Company;

use App\Http\Controllers\Site\BaseController;
use App\Models\CompanyItems;
use App\Models\CompanyOneTimePayment;
use App\Models\CompanyPackages;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class   CabinetProfileController extends BaseController
{

    public function main()
    {

        $data = [];
        $data['user'] = User::auth();
        $data['seo'] = $this->staticSEO(__('cabinet.profile settings'));
        $data['current_page'] = 111;

        return view('site.pages.cabinet.company.profile', $data);
    }

    public function personal(Request $request)
    {
        Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|phone|max:255',
            'address' => 'required|string|max:255',
        ], [
            'required' => __('auth.required'),
            'string' => __('auth.required'),
            'max' => __('auth.max'),
            'phone' => __('auth.invalid phone'),
        ])->validate();
        $user = User::auth();
        $user->name = $request->input('name');
        $user->phone = $request->input('phone');
        $user->address = $request->input('address');
        $user->save();

        return redirect()->route('cabinet.profile')->with(['personal.success' => true]);
    }

    public function ordersHistory()
    {
        $data['title'] = 'Купленные товары ';
        $data['empty'] = 'Нет Купленных товаров';

        $items_ids = CompanyItems::where('user_id', auth()->user()->id)->pluck('item_id')->toArray();

        $order_ids = DB::table('items_order')->select('order_id')->whereIn('items_id', $items_ids)->groupBy('order_id')->pluck('order_id')->toArray();
        $data['orders'] = Order::whereIn('id', $order_ids ?? [])->with('items')->where('status', Order::STATUS_DONE)->get();
        return view('site.pages.cabinet.company.ordersHistory', $data);
    }

    public function ordersPending()
    {
        $data['title'] = 'Полученные заказы ';
        $data['empty'] = 'Нет Полученных заказов ';
        $items_ids = CompanyItems::where('user_id', auth()->user()->id)->pluck('item_id')->toArray();

        $order_ids = DB::table('items_order')->select('order_id')->whereIn('items_id', $items_ids)->groupBy('order_id')->pluck('order_id')->toArray();
        $data['orders'] = Order::whereIn('id', $order_ids ?? [])->with('items')->whereIN('status', [Order::STATUS_PENDING, Order::STATUS_NEW])->get();

        return view('site.pages.cabinet.company.ordersHistory', $data);
    }

    public function ordersDeclined()
    {
        $data['title'] = 'Oтклоненные заказы ';
        $data['empty'] = 'Нет Oтклоненных заказов ';

        $items_ids = CompanyItems::where('user_id', auth()->user()->id)->pluck('item_id')->toArray();

        $order_ids = DB::table('items_order')->select('order_id')->whereIn('items_id', $items_ids)->groupBy('order_id')->pluck('order_id')->toArray();
        $data['orders'] = Order::whereIn('id', $order_ids ?? [])->with('items')->where('status', Order::STATUS_DECLINED)->get();

        return view('site.pages.cabinet.company.ordersHistory', $data);
    }

    public function security(Request $request)
    {
        $user = User::auth();
        Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'required|string|min:8',
            'password_confirmation' => 'required_with:password|same:password',
            'current_password' => ['required', 'string', function ($attribute, $value, $fail) use ($user) {
                if (!Hash::check($value, $user->password)) {
                    $fail(__('auth.invalid password'));
                }
            }],
        ], [
            'required' => __('auth.required'),
            'string' => __('auth.required'),
            'max' => __('auth.max'),
            'email' => __('auth.invalid email'),
            'unique' => __('auth.unique'),
            'min' => __('auth.min'),
            'required_with' => __('auth.required'),
            'same' => __('auth.confirmed'),
        ])->validate();
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->save();
        Auth::login($user);

        return redirect()->route('cabinet.profile')->with(['security.success' => true]);
    }

    public function settings()
    {

        $data = [];
        $data['user'] = User::auth();
        $data['title'] = 'Настройки';
        $data['seo'] = $this->staticSEO(__('cabinet.profile settings'));
        $data['current_page'] = 111;

        return view('site.pages.cabinet.company.profileSettings', $data);
    }

    public function support(Request $request)
    {
        $data['user'] = User::auth();
        $data['title'] = 'Поддержка';
        if ($request->post()) {

        }

        return view('site.pages.cabinet.company.support', $data);
    }

    public function statisticAndRevolutions()
    {
        $data = [];
        $data['user'] = User::auth();
        $data['title'] = 'Обороты и Статистики';
        $items_ids = CompanyItems::where('user_id', auth()->user()->id)->pluck('item_id')->toArray();

        $order_ids = DB::table('items_order')->select('order_id')->whereIn('items_id', $items_ids)->groupBy('order_id')->pluck('order_id')->toArray();
        $data['done_orders'] = Order::whereIn('id', $order_ids ?? [])->with('items')->where('status', Order::STATUS_DONE)->count();
        $data['declined_orders'] = Order::whereIn('id', $order_ids ?? [])->with('items')->where('status', Order::STATUS_DECLINED)->count();
        $data['pending_orders'] = Order::whereIn('id', $order_ids ?? [])->with('items')->where('status', Order::STATUS_PENDING)->count();
        $data['new_orders'] = Order::whereIn('id', $order_ids ?? [])->with('items')->where('status', Order::STATUS_NEW)->count();
        $data['all_orders'] = Order::whereIn('id', $order_ids ?? [])->with('items')->get();

        $data['pending_orders_items'] = 0;
        $data['declined_orders_items'] = 0;
        $data['new_orders_items'] = 0;
        $data['done_orders_items'] = 0;
        $data['all_orders_items'] = 0;

        $data['pending_orders_items_sum'] = 0;
        $data['declined_orders_items_sum'] = 0;
        $data['new_orders_items_sum'] = 0;
        $data['done_orders_items_sum'] = 0;
        $data['all_orders_items_sum'] = 0;

        foreach ($data['all_orders'] as $order) {
            $data['all_orders_items'] += count($order->items);
            $data['all_orders_items_sum'] += $order->total;
            if ((int)$order->status == Order::STATUS_DONE) {
                $data['done_orders_items'] += count($order->items);
                $data['done_orders_items_sum'] += $order->total;
            }
            if ((int)$order->status == Order::STATUS_NEW) {
                $data['new_orders_items'] += count($order->items);
                $data['new_orders_items_sum'] += $order->total;
            }
            if ((int)$order->status == Order::STATUS_DECLINED) {

                $data['declined_orders_items'] += count($order->items);
                $data['declined_orders_items_sum'] += $order->total;
            }
            if ((int)$order->status == Order::STATUS_PENDING) {
                $data['pending_orders_items'] += count($order->items);
                $data['pending_orders_items_sum'] += $order->total;
            }
        }
        $data['company_packages'] = CompanyPackages::where('company_id', \auth()->user()->id)->with('package')->whereNotNull('random_order_id')->get();
        $data['company_one_time_packages'] = CompanyOneTimePayment::where('company_id', \auth()->user()->id)->whereNotNull('random_order_id')->with('package')->get();

        return view('site.pages.cabinet.company.statisticAndRevolutions', $data);
    }

}
