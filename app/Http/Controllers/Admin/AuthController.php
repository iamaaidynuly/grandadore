<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Notify\Facades\Notify;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    use ThrottlesLogins;

    //region Not Authenticated
    public function login()
    {
        return view('admin.auth.login');
    }

    public function attemptLogin(Request $request)
    {
        $credentials = $request->only($this->username(), 'password');
//        Request
        $langData = __('admin/auth/validation');
        $validator = Validator::make($credentials, [
            $this->username() => 'required|email',
            'password' => 'required',
        ], $langData['custom'], $langData['attributes']);

        if ($validator->fails()) {
            return $this->sendErrors($validator);
        }
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }
        $user = User::checkAdmin($credentials['email'], $credentials['password']);
        if (!$user) {
            $this->incrementLoginAttempts($request);

            return $this->sendErrors(['root' => [__('admin/auth/messages.failed')]]);
        }
        $this->clearLoginAttempts($request);
        Auth::login($user);

        return $this->redirectIfAuthenticated();
    }

    private function sendErrors($errors)
    {
        return redirect()->route('admin.login')
            ->withErrors($errors)
            ->withInput();
    }

    protected function username()
    {
        return 'email';
    }

    protected function sendLockoutResponse(Request $request)
    {
        $seconds = $this->limiter()->availableIn(
            $this->throttleKey($request)
        );

        throw ValidationException::withMessages([
            'root' => [__('admin/auth/messages.throttle', ['seconds' => $seconds])],
        ])->status(429);
    }

    public function reset()
    {
        return view('admin.auth.reset');
    }


    public function attemptReset(Request $request)
    {
        $inputs = $request->all();
        $langData = __('admin/auth/validation');
        Validator::make($inputs, [
            'email' => 'required',
        ], $langData['custom'], $langData['attributes'])->validate();
        if (!User::getAdmin($request->input('email'))) $notFound = true;
        else $response = Password::broker()->sendResetLink($request->only('email'));
        if (isset($notFound) || ($response ?? null) != Password::RESET_LINK_SENT) return redirect()->back()->withInput()->withErrors([
            'email' => 'Указанная эл. почта не существует',
        ]);

        return redirect()->route('admin.password.reset')->with('reset_link_sent', true);
    }

    public function recover($email, $token)
    {
        if (!User::checkRecoverToken($email, $token) || !User::getAdmin($email)) abort(404);

        return view('admin.auth.recover', ['email' => $email, 'token' => $token]);
    }

    public function attemptRecover($email, $token, Request $request)
    {
        if (!User::checkRecoverToken($email, $token) || !User::getAdmin($email)) abort(404);
        $inputs = $request->all();
        Validator::make($inputs, [
            'new_password' => 'required|min:5|confirmed',
        ], [
            'required' => 'Введите новый пароль',
            'min' => 'Новый пароль должен содержать мин. :min символов',
            'confirmed' => 'Новый пароль и подверждение не совпадают',
        ])->validate();
        $user = User::recoverPassword($email, $inputs['new_password']);
        Auth::login($user);
        Notify::success('Пароль успешно восстановлен');

        return redirect()->route(config('admin.homepage'));
    }

    //endregion
    //region Authenticated
    public function logout(Request $request)
    {
        if ($request->input('action') == 'logout') {
            Auth::logout();
            $request->session()->flush();

            return redirect()->route('admin.login');
        }

        return $this->redirectIfAuthenticated();
    }

    public function redirectIfAuthenticated()
    {
        return redirect()->route(config('admin.homepage'));
    }
}
