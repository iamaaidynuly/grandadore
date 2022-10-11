<?php

namespace App\Http\Controllers\Admin;

use App\Models\CompanyItems;
use App\Models\CompanyOneTimePayment;
use App\Models\CompanyPackages;
use App\Models\DiscountForUser;
use App\Models\Items;
use App\Models\Order;
use App\Models\Packages;
use App\Models\User;
use App\Models\UserDiscount;
use App\Services\Notify\Facades\Notify;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UsersController extends BaseController
{
    public function main($role = null)
    {
        if (!empty($role)) {
            $data = ['title' => User::ROLEFRONT[$role]];

            $data['items'] = User::getByRoles($role);
            $data['role'] = $role;

            return view('admin.pages.users.main', $data);
        }

        $data = ['title' => 'Пользователи'];

        $data['items'] = User::getUsersByTypeWithOrders(0);
        $data['type'] = 0;

        return view('admin.pages.users.main', $data);
    }

    public function edit($id)
    {
        $user = User::query()->where('id', $id)->firstOrFail();

        return view('admin.pages.users.edit', ['item' => $user]);
    }

    public function update($id, Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'password' => 'nullable|confirmed',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png',
            'image' => 'nullable|image|mimes:jpg,jpeg,png'
        ]);

        $user = User::query()->where('id', $id)->firstOrFail();
        $user->name = $request->input('name');
        $user->active = $request->has('active');
        $user->email = $request->input('email');
        $user->work_hours = $request->input('work_hours');
        $user->website = $request->input('website');
        $user->description = $request->input('description');

        if ($user->type == 1) {
            $user->url = to_url($user->name);
        }

        $url = User::url_unique($request->input('name'), $user->id);

        $user->url = $url;
        if ($request->input('password') !== null && $request->input('passsword') == $request->input('password_confirmation')) {
            $user->password = bcrypt($request->input('password'));
        }

        $resizes = [
            [
                'width' => 200,
                'height' => 200,
                'aspect' => true,
                'dir' => 'thumbs/',
                'method' => 'resize'
            ],
        ];

        if ($image = upload_image('logo', 'u/users/', $resizes, (!empty($user->logo)) ? $user->logo : false, false)) {
            $user->logo = $image;
        }

        $resizes = [
            [
                'width' => 1200,
                'height' => 680,
                'aspect' => true,
                'dir' => 'thumbs/',
                'method' => 'resize'
            ],
            [
                'width' => 365,
                'height' => 215,
                'aspect' => true,
                'dir' => 'small/',
                'method' => 'resize'
            ],
        ];
        if ($image = upload_image('image', 'u/users/', $resizes, (!empty($user->image)) ? $user->image : false, false)) {
            $user->image = $image;
        }

        if ($user->save()) {
            Notify::success('Магазин успешно отредактирован');

            return redirect()->route('admin.users.view.magazine');
        }

        return abort(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function userDiscount($id)
    {
        $data['discounts'] = DiscountForUser::all();
        $data['user_id'] = $id;
        $data['user_discount'] = UserDiscount::where('user_id', $id)->first();

        return view('admin.pages.users.user_discount', $data);
    }

    public function acceptEmail($id)
    {
        $user = User::where('id', $id)->first();
        $user->verification = null;
        $user->save();

        return redirect()->back();
    }

    public function addDiscountToUser(Request $request, $id)
    {
        $result = UserDiscount::action($id, $request->discount);
        $data['discounts'] = DiscountForUser::all();
        $data['user_id'] = $id;
        $data['user_discount'] = UserDiscount::where('user_id', $id)->first();

        return view('admin.pages.users.user_discount', $data);
    }

    public function magazine()
    {
        $data = ['title' => 'Бутик'];
        $data['items'] = User::getUsersByTypeWithItems(1);
        $data['type'] = 1;

        return view('admin.pages.users.main', $data);
    }

    public function addUserByType(Request $request, $type)
    {
        if ($request->post()) {

            $inputs = $request->all();
            $user = User::where('email', $inputs['email'])->first();
            if (!empty($user)) {
                Notify::error('Эл.почта уже используется');

                return redirect()->back();
            }
            $this->validator($inputs)->validate();
            $user = new User();
            $user->name = $inputs['name'];
            $user->active = $inputs['active'] ?? 0;
            $user->email = $inputs['email'];
            $user->password = Hash::make($inputs['password']);
            $user->type = $type;

            if ($type == 1) {
                $user->url = to_url($user->name);
            }

            $user->verification = null;
            $user->admin = 0;
            if ($user->save()) {
                Notify::success(User::TYPE[$type] . ' добавлен успешно');

                return redirect()->route('admin.users.view.magazine');
            }
        }
        $data['type'] = $type;

        return view('admin.pages.users.form', $data);
    }

    public function packagesEdit($id)
    {
        $company = User::where(['admin' => 0, 'type' => 1, 'id' => $id])->firstOrFail();
        $data = [];
        $data['company_package'] = CompanyPackages::where('created_at', '>', Carbon::now()->subMonth(1)->toDateTimeString())->where(['status' => 1])->where('company_id', $company->id)->pluck('package_id')->toArray();
        $data['packages'] = Packages::all();
        $data['company'] = $company;
        if (empty($data['company_package'])) {
            $data['company_package'][] = 1;
        }

        return view('admin.pages.users.company_package', $data);
    }

    public function packagesEditSubmit(Request $request, $company_id)
    {
        $check_company = User::where(['admin' => 0, 'type' => 1, 'id' => $company_id, 'active' => 1])->first();
        if (empty($check_company)) {
            Notify::error('Нельзя добавить пакет к блокированному магазину');

            return redirect()->back();
        }


        $id = $request->package_id;
        $package = Packages::where('id', $id)->firstOrFail();

        $company_package = CompanyPackages::where(['package_id' => $package->id, 'status' => 1, 'company_id' => $company_id])->where('created_at', '>', Carbon::now()->subMonth(1)->toDateTimeString())->first();
        if (!empty($company_package)) {
            Notify::error('Пакет уже есть у магазина');

            return redirect()->back();
        } else {
            $company_package = CompanyPackages::where(['status' => 1, 'company_id' => $company_id])->where('created_at', '>', Carbon::now()->subMonth(1)->toDateTimeString())->first();

            if (!empty($company_package)) {
                if ($company_package->package_id > (int)$id) {
                    Notify::error('Вы не можете снизить  пакет магазина');

                    return redirect()->back();
                }
            } elseif ($id == 1) {
                Notify::error('Пакет уже есть у магазина');

                return redirect()->back();
            }
        }

        $inputs = [];
        $inputs['company_id'] = $company_id;
        $inputs['package_id'] = $package->id;
        $inputs['package_price'] = $package->package_price;
        if (!empty($company_package)) {
            $company_package->status = 0;
            $company_package->save();
        }
        if (CompanyPackages::action(null, $inputs)) {
            Notify::success('Пакет успешно отредактирован');

            return redirect()->back();
        }

    }

    public function addAdminsByType(Request $request, $role)
    {
        if ($request->post()) {
            $inputs = $request->all();
            $user = User::where('email', $inputs['email'])->first();
            if (!empty($user)) {
                Notify::error('Эл.почта уже используется');

                return redirect()->back();
            }
            $this->validator($inputs)->validate();
            $user = new User();
            $user->name = $inputs['name'];
            $user->active = $inputs['active'] ?? 0;
            $user->email = $inputs['email'];
            $user->password = Hash::make($inputs['password']);
            $user->role = $role;
            $user->admin = 1;
            if ($user->save()) {
                Notify::success(User::ROLE[$role] . ' добавлен успешно');

                return redirect()->route('admin.users.main', ['role' => $role]);
            }
        }
        $data['role'] = $role;

        return view('admin.pages.users.form', $data);
    }

    public function delete(Request $request)
    {
        $result = ['success' => false];
        $id = $request->input('item_id');
        if ($id && is_id($id)) {
            $user = User::where('id', $id)->first();
            if ($user && User::deleteUser($user)) {
                CompanyPackages::where('company_id', $id)->delete();
                CompanyOneTimePayment::where('company_id', $id)->delete();
                $items_ids = CompanyItems::where('user_id', $id)->pluck('item_id')->toArray();
                Items::whereIn('id', $items_ids)->delete();
                CompanyItems::where('user_id', $id)->delete();
                $result['success'] = true;
            }
        }

        return response()->json($result);

    }

    public function view($id)
    {
        $data = [];
        $data['item'] = User::where('id', $id)->with('orders')->firstOrFail();
        if ((int)$data['item']->admin == 0 && (int)$data['item']->type == 1) {
            $items_ids = CompanyItems::where('user_id', $id)->pluck('item_id')->toArray();
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

            $data['pending_orders_items_provider_sum'] = 0;
            $data['declined_orders_items_provider_sum'] = 0;
            $data['new_orders_items_provider_sum'] = 0;
            $data['done_orders_items_provider_sum'] = 0;
            $data['all_orders_items_provider_sum'] = 0;

            foreach ($data['all_orders'] as $order) {
                $data['all_orders_items'] += count($order->items);
                $data['all_orders_items_sum'] += $order->total;
                $data['all_orders_items_provider_sum'] += $order->provider_total;
                if ((int)$order->status == Order::STATUS_DONE) {
                    $data['done_orders_items'] += count($order->items);
                    $data['done_orders_items_sum'] += $order->total;
                    $data['done_orders_items_provider_sum'] += $order->provider_total;
                }
                if ((int)$order->status == Order::STATUS_NEW) {
                    $data['new_orders_items'] += count($order->items);
                    $data['new_orders_items_sum'] += $order->total;
                    $data['new_orders_items_provider_sum'] += $order->provider_total;
                }
                if ((int)$order->status == Order::STATUS_DECLINED) {

                    $data['declined_orders_items'] += count($order->items);
                    $data['declined_orders_items_sum'] += $order->total;
                    $data['declined_orders_items_provider_sum'] += $order->provider_total;
                }
                if ((int)$order->status == Order::STATUS_PENDING) {
                    $data['pending_orders_items'] += count($order->items);
                    $data['pending_orders_items_sum'] += $order->provider_total;
                    $data['pending_orders_items_provider_sum'] += $order->provider_total;

                }
            }

        }
        $data['title'] = 'Пользователь "' . $data['item']->email . '"';
        $data['orders'] = $orders = $data['item']->orders;
        $data['orders_count'] = [
            'pending' => $orders->where('status', Order::STATUS_PENDING)->count(),
            'pending' => $orders->where('status', Order::STATUS_NEW)->count(),
            'declined' => $orders->where('status', Order::STATUS_DECLINED)->count(),
            'accepted' => $orders->where('status', Order::STATUS_DONE)->count(),
        ];
        $data['company_packages'] = CompanyPackages::where('company_id', $id)->with('package')->get();
        $data['company_one_time_packages'] = CompanyOneTimePayment::where('company_id', $id)->with('package')->get();

//        $data['back_url'] = route('admin.users.main');
        return view('admin.pages.users.view', $data);
    }

    public function statistics()
    {
        $users = User::getUsersByTypeWithOrders(1);

        $items = [];

        if (!empty($users) && count($users)) {
            foreach ($users as $user) {
                $data = [];
                $data['item'] = $user;
                if ((int)$data['item']->admin == 0 && (int)$data['item']->type == 1) {
                    $items_ids = CompanyItems::where('user_id', $user->id)->pluck('item_id')->toArray();
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

                    $data['pending_orders_items_provider_sum'] = 0;
                    $data['declined_orders_items_provider_sum'] = 0;
                    $data['new_orders_items_provider_sum'] = 0;
                    $data['done_orders_items_provider_sum'] = 0;
                    $data['all_orders_items_provider_sum'] = 0;

                    foreach ($data['all_orders'] as $order) {
                        $data['all_orders_items'] += count($order->items);
                        $data['all_orders_items_sum'] += $order->total;
                        $data['all_orders_items_provider_sum'] += $order->provider_total;
                        if ((int)$order->status == Order::STATUS_DONE) {
                            $data['done_orders_items'] += count($order->items);
                            $data['done_orders_items_sum'] += $order->total;
                            $data['done_orders_items_provider_sum'] += $order->provider_total;
                        }
                        if ((int)$order->status == Order::STATUS_NEW) {
                            $data['new_orders_items'] += count($order->items);
                            $data['new_orders_items_sum'] += $order->total;
                            $data['new_orders_items_provider_sum'] += $order->provider_total;
                        }
                        if ((int)$order->status == Order::STATUS_DECLINED) {

                            $data['declined_orders_items'] += count($order->items);
                            $data['declined_orders_items_sum'] += $order->total;
                            $data['declined_orders_items_provider_sum'] += $order->provider_total;
                        }
                        if ((int)$order->status == Order::STATUS_PENDING) {
                            $data['pending_orders_items'] += count($order->items);
                            $data['pending_orders_items_sum'] += $order->provider_total;
                            $data['pending_orders_items_provider_sum'] += $order->provider_total;

                        }
                    }

                }
                $data['title'] = 'Пользователь "' . $data['item']->email . '"';
                $data['orders'] = $orders = $data['item']->orders;
                $data['orders_count'] = [
                    'pending' => $orders->where('status', Order::STATUS_PENDING)->count(),
                    'new' => $orders->where('status', Order::STATUS_NEW)->count(),
                    'declined' => $orders->where('status', Order::STATUS_DECLINED)->count(),
                    'accepted' => $orders->where('status', Order::STATUS_DONE)->count(),
                ];
                $data['company_packages'] = CompanyPackages::where('company_id', $user->id)->with('package')->get();
                $data['company_one_time_packages'] = CompanyOneTimePayment::where('company_id', $user->id)->with('package')->get();

                $items['items'][] = $data;
            }
        }

//        dd($items);
        return view('admin.pages.users.statistics', $items);
    }

    public function toggleActive(Request $request)
    {
        $id = $request->input('id');
        $active = $request->input('active');
        if (!is_id($id)) abort(404);
        $item = User::findOrFail($id);
        $item->active = $active ? 1 : 0;
        $item->save();
        Notify::success('Изменении сохранены');
        if ($request->has('from_list')) {
            return redirect()->route('admin.users.view.magazine');
        }

        return redirect()->route('admin.users.view', ['id' => $item->id]);
    }

    private function validator($inputs, $edit = false)
    {
        $result = [];
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required_with:confirm_password|string|max:20|same:confirm_password|min:8',
        ];

        return Validator::make($inputs, $rules, [
            'name.required' => ' Введите Имя.',
            'email.required' => ' Введите Эл.почту.',
            'password.required' => ' Введите Пароль.',
            'password.required_with' => ' Введите повторите пароль.',
            'confirm_password.required' => ' Повторите Пароль.',
            'password.same' => 'Пароли не совпадают',
            'password.min' => 'Пароли должны быть не менее 8 символов',
            'password.max' => 'Пароли должны быть не более 20 символов',
            'password.string' => 'Пароли должны (A-Z)(0-9)',
            'email.email' => 'Введите правильную Эл.почту',
            'email.max' => 'Эл.почту должен быть не более 255 символ',
            'name.max' => 'Имя должен быть не более 255 символ',
        ]);
    }
}
