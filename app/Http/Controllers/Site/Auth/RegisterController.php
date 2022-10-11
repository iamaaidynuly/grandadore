<?php

namespace App\Http\Controllers\Site\Auth;

use App\Http\Controllers\Site\BaseController;
use App\Models\Banner;
use App\Models\User;
use App\Rules\FormattedUniquePhone;
use App\Services\Support\Str;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use MongoDB\Driver\Session;

class RegisterController extends BaseController
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->redirectTo = route('cabinet.profile');
        $this->middleware('guest')->except('verifyEmail');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $rules = [
            'password'              => ['required', 'string', 'min:8'],
            'password_confirmation' => ['required_with:password', 'same:password'],
            'agree'                 => ['required', 'int', 'max:2'],
        ];

//        if (isset($data['email_or_phone'])) {
            if (is_email($data['email_or_phone'])) {
                $rules['email_or_phone'] = ['required', 'string', 'mail', 'max:255', 'unique:users,email'];
            } else {
                $rules['email_or_phone'] = ['required', new FormattedUniquePhone()];
            }
//        }

        return Validator::make($data, $rules, [
            'required'      => __('auth.required'),
            'required_with' => __('auth.required'),
            'string'        => __('auth.required'),
            'unique'        => __('auth.unique'),
            'min'           => __('auth.min'),
            'max'           => __('auth.max'),
            'mail'          => __('auth.invalid email'),
            'phone'         => __('auth.invalid phone'),
            'same'          => __('auth.confirmed'),
        ]);
    }

    /**
     * @param array $data
     * @param       $verification
     *
     * @return mixed
     */
    protected function create(array $data, $verification)
    {
        $dataToCreate = [
            'password'     => Hash::make($data['password']),
            'type'         => 0,
            'active'       => true
        ];

        if (is_email($data['email_or_phone'])) {
            $dataToCreate['email'] = $data['email_or_phone'];
            $dataToCreate['verification'] = Hash::make($verification);
        } else {
            $dataToCreate['phone'] = preg_replace('/[^0-9]/', '', $data['email_or_phone']);
        }

        return User::create($dataToCreate);
    }

    public function showRegistrationForm()
    {
        $data['banner'] = Banner::get('auth');
        $data['current_page'] = '';
        $data['seo'] = $this->staticSEO(__('app.registration'));

        $breadcrumbs = [
            [
                'title' => __('app.registration'),
                'url'   => ''
            ]
        ];

        $data['breadcrumbs'] = $breadcrumbs;
//          $message = \Session::get('message');
//dd()
//        if (isset($message)) {
//            $data['message'] = 'asd';
//
//        }else{
//            $data['message']='';
//        }
        return view('site.pages.auth.register', $data);
    }

    public function showRegistrationForm1()
    {

        $msg = 'Для оформления заказа нужно зарегистрироваться на сайте';
        return redirect('/register')->withSuccess($msg);

    }

    public function register(Request $request)
    {

        $us = User::all();

        if($request['email_or_phone'] != null) {
            foreach ($us as $val) {
                if ($val['email'] === $request['email_or_phone'] || $val['phone'] === $request['email_or_phone']) {
                    return redirect()->back()->with('message', 'Пользователь с такими данными уже зарегистрирован');
                }
            }
        }


        $this->validator($request->all())->validate();

        $verification_token = Str::random(32);
        /** @var User $user */
        $user = $this->create($request->all(), $verification_token);

        $user->sms_verification = 0;
        $user->update();

        if ($user->email) {
            $user->sendRegisteredNotification($verification_token);
            notify('Ссылка с подтверждением адреса эл. почты отправлена на указанную почту');
        } elseif($user->phone) {
            $user->sendToVerify();
            notify('Ссылка с подтверждением номера телефона отправлена на указанный номер телефона');
        }


        $this->guard()->login($user);

        $user->initBasketWithSession();

//        return redirect($this->redirectPath());
        return redirect('cabinet/email/notice')->with('message','Добро пожаловать ' . $user->email);
    }

    public function verifyEmail($email, $token)
    {
        $user = User::where('email', $email)->firstOrFail();

        if (!$user->verification || !Hash::check($token, $user->verification)) abort(404);
        $user->verification = null;
        $user->save();

        notify('Адрес эл. почты успешно подтверждён!');

        return redirect()->route('cabinet.profile');
    }
}
