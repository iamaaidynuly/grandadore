<?php

namespace App\Http\Controllers\Site\Auth;

use App\Http\Controllers\Site\BaseController;
use App\Models\Banner;
use App\Models\User;
use App\Services\Notify\Facades\Notify;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class LoginController extends BaseController
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers {
        sendLoginResponse as baseSendLoginResponse;
    }

    protected $username = 'email';

    /**
     * Where to redirect users after login.
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

        $this->redirectTo = url()->previous();
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('site.pages.auth.login', [
            'banner'       => Banner::get('auth'),
            'current_page' => 111,
            'seo'          => $this->staticSEO(__('app.authentication')),
            'page_classes' => ' fit-to-max',
        ]);
    }

    protected function validateLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            $this->username() => 'required',
            'password'        => 'required|string',
        ], [
            'required' => __('auth.required'),
            'string'   => __('auth.required'),
        ]);
        if ($validator->fails()) {
//            dd($validator->errors());
            throw ValidationException::withMessages($validator->errors()->messages())->redirectTo($this->redirectURL());
        }
    }

    protected function sendLockoutResponse(Request $request)
    {
        $seconds = $this->limiter()->availableIn(
            $this->throttleKey($request)
        );

        throw ValidationException::withMessages([
            'global' => [Lang::get('auth.throttle', ['seconds' => $seconds])],
        ])->redirectTo($this->redirectURL())->status(429);
    }

    protected function sendFailedLoginResponse(Request $request)
    {

        throw ValidationException::withMessages([
            'global' => [trans('auth.failed')],
        ])->redirectTo('login');
    }

    private function redirectURL()
    {
        return url('?response=301');
    }

    public function login(Request $request)
    {
        $email = $request->input('email');

        $isEmail = is_email($email);

        if (!$isEmail) {
            $email = preg_replace('/[^0-9]/', '', $email);
        }

        $this->validateLogin($request);
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            return $this->sendLockoutResponse($request);

        }
        /** @var Authenticatable|User $user */
        $user = User::query()->where($isEmail ? 'email' : 'phone', $email)->first();

        if (!$user || !Hash::check($request->input('password'), $user->password)) {
            $this->incrementLoginAttempts($request);

            return $this->sendFailedLoginResponse($request);
        }/* elseif (!$user->isVerified()) {
            return redirect()->route('login')->withErrors(['global' => __('auth.not verified')])->withInput();
        }*/ elseif ($user->active == 0) {
            return redirect()->route('login')->withErrors(['global' => __('auth.blocked')])->withInput();
        }


        Auth::login($user, true);
        $user = $request->user();
//dd($user->name);
        $user->initBasketWithSession();
        Notify::success('message' );
        //return $this->sendLoginResponse($request);
        return redirect('/')->with('message', 'Добро пожаловать ' . $user->email);
    }

    protected function sendLoginResponse(Request $request)
    {
        $user = $request->user();

        $user->initBasketWithSession();

        notify("Добро пожаловать, $user->name." );

        return $this->baseSendLoginResponse($request);
    }

    public function username()
    {
        return $this->username ?? 'email';
    }
}
