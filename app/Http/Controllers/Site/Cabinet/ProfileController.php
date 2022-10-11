<?php

namespace App\Http\Controllers\Site\Cabinet;

use App\Http\Controllers\Site\BaseController;
use App\Models\DeliveryRegion;
use App\Models\MinimumTotalCost;
use App\Models\Order;
use App\Models\Support;
use App\Models\User;
use App\Rules\FormattedPhone;
use App\Rules\FormattedUniquePhone;
use App\Services\Notify\Facades\Notify;
use App\Services\SmsSender\SmsSender;
use App\Services\Support\Str;
use Carbon\Carbon;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Snowfire\Beautymail\Beautymail;


class ProfileController extends BaseController
{
    public function main()
    {
        $data = [];
        $data['user'] = User::auth();
        $data['seo'] = $this->staticSEO(__('cabinet.profile settings'));
        $data['current_page'] = 111;

        return view('site.pages.cabinet.profile', $data);
    }

    public function setPhone(Request $request)
    {
        if ($request->user()->hasVerifiedPhone()) {
            return redirect()->route('cabinet.profile');
        }

        if ($request->isMethod('post')) {
            $request->validate([
                'phone' => ['required', new FormattedUniquePhone(), new FormattedPhone()]
            ]);

            $request->user()->phone = preg_replace('/[^0-9]/', '', $request->input('phone'));
            $request->user()->saveOrFail();

            $request->user()->sendToVerify();

            return redirect()->route('cabinet.phoneVerification.notice');
        }

        $pageData = [
            'title' => 'Ввод номера телефона'
        ];

        return view('site.pages.cabinet.verification.setPhone', $pageData);
    }

    public function showPhoneVerify(Request $request)
    {
        if (!$request->user()->phone) {
            return redirect()->route('cabinet.phoneVerification.setPhone');
        }

        $pageData = [
            'title' => 'Ввод кода подтверждения'
        ];

        return view('site.pages.cabinet.verification.index', $pageData);
    }

    public function verify(Request $request)
    {

        if ($request->user()->sms_verification_code !== (int) $request->input('code')) {
            throw ValidationException::withMessages([
                'code' => ['Код не правильный. Пожалуйста проверьте правильность.'],
            ]);
        }

        if ($request->user()->hasVerifiedPhone()) {
            return redirect()->route('cabinet.profile.basket');
        }

        $request->user()->markPhoneAsVerified();

        notify('Номер телефона успешно сохранен!');

        return redirect()->route('cabinet.profile.basket');
    }

    public function settings()
    {
        $data = [];
        $data['user'] = User::auth();
        $data['title'] = 'Настройки';
        $data['active'] = 'settings';
        $data['seo'] = $this->staticSEO(__('cabinet.profile settings'));
        $data['current_page'] = 111;
        $data['regions'] = DeliveryRegion::with('cities')->has('cities')->orderBy('title')->get();

        $breadcrumbs = [
            [
                'title' => __('cabinet.profile settings'),
                'url'   => ''
            ]
        ];

        $data['breadcrumbs'] = $breadcrumbs;

        return view('site.pages.cabinet.profileSettings', $data);
    }

    public function support(Request $request)
    {
        $data['user'] = User::auth();
        $data['questions'] = Support::query()->orderByDesc('id')->get();
        $data['title'] = 'Поддержка';

        return view('site.pages.cabinet.support', $data);
    }

    public function favorites(Request $request)
    {
        $data['title'] = 'Интернет магазин Grandadore.com';
        $data['active'] = 'favorite';
        $data['items'] = authUser()->favoriteItems()->paginate(24);

        return view('site.pages.cabinet.favorites', $data);
    }

    public function activeOrders(Request $request)
    {
        /*$data['title'] = 'Активные заказы';

        $status = $request->query('status', 'in-process');

        $inProcess = Order::with('items')->where([
            'status' => Order::STATUS_PENDING,
            'user_id' => authUser()->id
        ])->orderByDesc('created_at')->get();
        $pending = Order::with('items')->where([
            'status' => Order::STATUS_NEW,
            'user_id' => authUser()->id
        ])->orderByDesc('created_at')->get();

        switch ($status) {
            case 'in-process':
                $data['orders'] = $inProcess;
                break;
            default:
                $data['orders'] = $pending;
        }*/
        /*
        $data['status'] = $status;*/

        $data['orders'] = Order::query()
            ->where('user_id', authUser()->id)
            ->whereIn('status', [Order::STATUS_PENDING, Order::STATUS_NEW])
            ->with('items')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('site.pages.cabinet.orders.active', $data);
    }

    public function ordersHistory(Request $request)
    {
        $data['title'] = 'Архив заказов';

        $status = $request->query('status', 'done');

        $orders = Order::with('items')->where([
            'user_id' => authUser()->id
        ])->orderByDesc('created_at');

        if ($request->query('date_from') && $request->query('date_to')) {
            $orders->whereBetween('created_at', [Carbon::createFromFormat('d/m/Y', $request->query('date_from'))->toDateTimeString(), Carbon::createFromFormat('d/m/Y', $request->query('date_to'))]);
        }

        switch ($status) {
            case 'declined':
                $data['orders'] = $orders->where(['status' => Order::STATUS_DECLINED])->paginate(15);
                break;
            default:
                $data['orders'] = $orders->where(['status' => Order::STATUS_DONE])->paginate(15);
        }

        $data['status'] = $status;

        return view('site.pages.cabinet.orders.history', $data);
    }

    public function basket()
    {
        $data['title'] = 'Моя корзина ';
        $data['active'] = 'basket';
        $data['seo'] = $this->staticSEO(__('cabinet.profile settings'));

        $breadcrumbs = [
            [
                'title' => __('cabinet.profile settings'),
                'url'   => ''
            ]
        ];

        $data['breadcrumbs'] = $breadcrumbs;
        $data['price'] = MinimumTotalCost::first();

        return view('site.pages.cabinet.basket', $data);
    }

    public function updateUserInfo(Request $request)
    {

        $user = authUser();

        $user->name = $request->input('name');
        $user->address = $request->input('address');
        $user->delivery_city_id = $request->input('delivery_city_id');
        $user->address = $request->city;

        if (isset($request->old_pass) && isset($request->password1) && isset($request->password2)) {

            if ($request->password1 != $request->password2) {
                return back();
//                dd( Notify::success('Данные успешно обновлены'));

            }
            $hashCheck = Hash::check($request->old_pass, $user->password);
            if (!$hashCheck) {

                return back();
            } else {

                $user->password = Hash::make($request->password1);

            }
        }


//        $this->validate($request, [
//      'name' => 'required|string',
//       'address' => 'required|string',
//        'delivery_city_id' => 'required|exists:delivery_cities,id',
//          'phone' => ['required', new FormattedPhone(), new FormattedUniquePhone(authUser()->id)],
//            'email' => 'required|email|unique:users,email,' . authUser()->id
//        ]);


        /*$cleanPhone = preg_replace('/[^0-9]/', '', $request->input('phone', $user->phone));
        if ($user->phone != $cleanPhone) {
            $user->phone = $cleanPhone;
            $user->sms_verification = false;
            $user->sms_verification_limit = 0;

            $user->sendToVerify();
        }

        if ($user->email != $request->input('email')) {
            $user->email = $request->input('email');

            $verification_token = Str::random(32);
            $user->verification = Hash::make($verification_token);
            $user->sendRegisteredNotification($verification_token);
            notify('Ссылка подтверждения эл. почты отправлена по указанному адресу.');
        }*/

        if (!$user->save()) {

            return abort(403);
        }

//          notify('Данные успешно обновлены');
//        Notify::success(('Данные успешно обновлены'));
//dd(Notify::success('Данные успешно обновлены'));
//        Session::flush('message', 'This is a message!');

        return redirect()->route('cabinet.profile')->with('message', 'Данные успешно обновлены');
    }

    public function security(Request $request)
    {
        $user = User::auth();
        Validator::make($request->all(), [
            'email'                 => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'password'              => 'required|string|min:8',
            'password_confirmation' => 'required_with:password|same:password',
            'current_password'      => ['required', 'string', function ($attribute, $value, $fail) use ($user) {
                if (!Hash::check($value, $user->password)) {
                    $fail(__('auth.invalid password'));
                }
            }],
        ], [
            'required'      => __('auth.required'),
            'string'        => __('auth.required'),
            'max'           => __('auth.max'),
            'email'         => __('auth.invalid email'),
            'unique'        => __('auth.unique'),
            'min'           => __('auth.min'),
            'required_with' => __('auth.required'),
            'same'          => __('auth.confirmed'),
        ])->validate();
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->save();
        Auth::login($user);

        return redirect()->route('cabinet.profile')->with(['security.success' => true]);
    }

    public function showEmailVerify()
    {
        $pageData['title'] = 'Требуется подтверждение адреса эл. почты';

        return view('site.pages.auth.emailVerify', $pageData);
    }

    public function resendVerificationEmail(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('cabinet.profile');
        }

        $verification_token = Str::random(32);
        $request->user()->verification = Hash::make($verification_token);
        $request->user()->save();
        $request->user()->sendRegisteredNotification($verification_token);

        return back()->with('resent', true);
    }

    public function sendPhoneChangingCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => ['required', new FormattedUniquePhone($request->user()->id), new FormattedPhone()]
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->get('phone')
            ])->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $code = $this->generateChangingCode();
        $user = $request->user();
        $sender = new SmsSender();

        $user->forceFill([
            'sms_verification_code' => $code
        ])->save();

        $message = 'Ваш код подтверждения на сайте '.$code;

        $data = $validator->validated();

        $phone = preg_replace('/[^0-9]/', '', $data['phone']);

        session()->put('phoneToChange', $phone);

        $sender->send(preg_replace('/[^0-9]/', '', $phone), $message);

        return response()->json([
            'message' => 'Код подтверждения была отправлена на указанный номер телефона'
        ]);
    }

    public function phoneChange(Request $request)
    {
        $errors = [];

        $phone = preg_replace('/[^0-9]/', '', $request->input('phone'));

        if ($phone != session('phoneToChange')) {
            $errors[] = 'Номер телефона не соответствует выбору';
        }

        if ($request->input('code') != authUser()->sms_verification_code) {
            $errors[] = 'Код подтверждения не правильный';
        }

        if (count($errors)) {
            return response()->json([
                'errors' => $errors
            ])->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        authUser()->phone = $phone;

        if (!authUser()->save()) {
            return response()->json([
                'errors' => ['Произошла ошибка в процессе смены номера телефона. Перезагрузите страницу и попробуйте снова']
            ])->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        notify('Номер телефона была успешно сменена');
        session()->forget('phoneToChange');

        return response()->json([
            'message' => 'Номер телефона была успешно сменена'
        ]);
    }

    public function sendEmailChangingCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'unique:users,email', 'not_in:']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->get('email')
            ])->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $code = $this->generateChangingCode();
        $user = $request->user();

        $user->forceFill([
            'sms_verification_code' => $code
        ])->save();

        try {
            app()->make(Beautymail::class)->send('site.notifications.changeEmail', ['code' => $code], function ($message) use ($user) {
                $message->from(env('MAIL_FROM_ADDRESS'))
                    ->to($user->email, $user->name)
                    ->subject('Подтверждение адреса эл. почты');
            });
        } catch (BindingResolutionException $e) {
        }

        $data = $validator->validated();

        session()->put('emailToChange', $data['email']);

        return response()->json([
            'message' => 'Код подтверждения была отправлена на указанную эл. почту'
        ]);
    }

    public function emailChange(Request $request)
    {
        $errors = [];

        if ($request->input('email') != session('emailToChange')) {
            $errors[] = 'Эл. почта не соответствует выбранному';
        }

        if ($request->input('code') != authUser()->sms_verification_code) {
            $errors[] = 'Код подтверждения не правильный';
        }

        if (count($errors)) {
            return response()->json([
                'errors' => $errors
            ])->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        authUser()->email = $request->input('email');

        if (!authUser()->save()) {
            return response()->json([
                'errors' => ['Произошла ошибка в процессе смены эл. почты. Перезагрузите страницу и попробуйте снова']
            ])->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        notify('Эл. почта была успешно сменена!');
        session()->forget('emailToChange');

        return response()->json([
            'message' => 'Эл. почта была успешно сменена!'
        ]);
    }

    protected function generateChangingCode()
    {
        return rand(10000, 99999);
    }


    public function changeEmail(Request $request)
    {
        $user = User::find($request->user);
        $user->email = $request->email;
        $user->update();
    }
}
