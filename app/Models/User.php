<?php

namespace App\Models;

use App\Http\Traits\UrlUnique;
use App\Notifications\RegisteredNotification;
use App\Notifications\ResetAdminPasswordNotification;
use App\Services\BasketService\BasketService;
use App\Services\BasketService\Drivers\SessionDriver;
use App\Services\Support\Str;
use App\Traits\MustVerifyPhone;
use App\ValueObjects\BasketItem;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Snowfire\Beautymail\Beautymail;

class  User extends Authenticatable
{
    use UrlUnique, MustVerifyPhone;

    const ROLE = [
        1 => 'admin',
        2 => 'moderator',
        3 => 'manager'
    ];
    const ROLEFRONT = [
        1 => 'Администратор',
        2 => 'Оператор-Модератор',
        3 => 'Контент-менеджер'
    ];
    const TYPE = [
        0 => 'user',
        1 => 'company',
    ];

    public function getRoledAttribute()
    {
        return isset(self::ROLE[$this->role]) ? self::ROLE[$this->role] : null;
    }

    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'type', 'phone', 'verification', 'active'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'active' => 'boolean'
    ];

    /**
     * @var
     */
    protected $appends = [
        'formattedPhone'
    ];

    private static $auth = null;

    public static function checkAdmin($email, $password)
    {
        $user = self::getAdmin($email);
        if (!$user || !Hash::check($password, $user->password)) return false;

        return $user;
    }

    public static function getAdmin($email)
    {
        $user = self::where('email', $email)->where('admin', '>', 0)->first();
        if ($user === null) return false;

        return $user;
    }

    public static function getUsers()
    {
        return self::where('admin', 0)->sort()->get();
    }

    public static function getUsersByType($type)
    {
        return self::where('admin', 0)->where('type', $type)->sort()->get();
    }

    public static function getUsersByTypeWithOrders($type)
    {
        return self::where('admin', 0)->where('type', $type)->with('orders')->sort()->get();
    }

    public static function getUsersByTypeWithItems($type)
    {
        return self::where('admin', 0)->where('type', $type)->with('items')->sort()->get();
    }

    public static function getByRoles($role)
    {
        return self::where('admin', 1)->where('role', $role)->where('role', '<>', 1)->sort()->get();
    }

    public static function getUser($email)
    {
        return User::where(['email' => $email, 'admin' => 0])->first();
    }

    public static function checkRecoverToken($email, $token)
    {
        $result = DB::table('password_resets')->select('token')->where('email', $email)->first();
        if (!$result) return false;

        return Hash::check($token, $result->token);
    }

    public static function action($user, $inputs)
    {
        $user['name'] = $inputs['name'];
        $user['email'] = $inputs['email'];
        if (!empty($inputs['change_password'])) {
            $user['password'] = Hash::make($inputs['new_password']);
        }
        $result = $user->save();
        Auth::login($user);

        return $result;
    }

    public static function recoverPassword($email, $password)
    {
        $user = self::where('email', $email)->first();

        return self::recoverUserPassword($user, $password);
    }

    public static function recoverUserPassword($user, $password)
    {
        DB::table('password_resets')->where('email', $user->email)->delete();
        $user->password = Hash::make($password);
        $user->setRememberToken(Str::random(60));
        if (!empty($user->verification)) $user->verification = null;
        $user->save();

//        event(new PasswordReset($user));
        return $user;
    }

    public static function deleteUser($model)
    {
        return $model->delete();
    }

    public function isAdmin()
    {
        return $this->admin > 0;
    }

    public function sendPasswordResetNotification($token)
    {
        if ($this->isAdmin()) {
            try {
                $this->notify(new ResetAdminPasswordNotification($token, $this->email));
            } catch (\Exception $e) {
            }
        } else {
            try {
                $url = route('password.reset', ['token' => $token, 'email' => $this->email]);

                app()->make(Beautymail::class)->send('site.notifications.password_reset', ['url' => $url], function ($message) {
                    $message->from(env('MAIL_FROM_ADDRESS'))
                        ->to($this->email, $this->name)
                        ->subject('Заявка на восстановление пароля');
                });
            } catch (BindingResolutionException $e) {
            }
        }
    }

    public function sendRegisteredNotification($token)
    {
        $url = route('verify_email', ['email' => $this->email, 'token' => $token]);

        try {
            return app()->make(Beautymail::class)
                ->send('site.notifications.registered', ['url' => $url], function ($message) {
                    $message->from(env('MAIL_FROM_ADDRESS'))
                        ->to($this->email, $this->name)
                        ->subject('Ссылка подтверждения эл. почты на сайте '.env('APP_NAME'));
                });
        } catch (Exception $e) {
            return true;
        }
    }

    public function hasVerifiedEmail()
    {
        return (empty($this->verification) || $this->admin > 0);
    }

    public static function auth()
    {
        if (self::$auth === null) {
            $user = Auth::user();
            if (!$user) {
                self::$auth = false;
            } /*else if (!$user->isVerified()) {
                Auth::logout();
                self::$auth = false;
            } */ else if ($user->active == 0) {
                Auth::logout();
                self::$auth = false;
            } else self::$auth = $user;
        }

        return self::$auth;
    }

    public function scopeSort($q)
    {
        return $q->orderBy('id', 'desc');
    }

    public function orders()
    {
        return $this->hasMany('App\Models\Order', 'user_id', 'id')->sort();
    }

    public function items()
    {
        return $this->hasMany('App\Models\CompanyItems', 'user_id');
    }

    public function rates()
    {
        return $this->hasMany(ItemRate::class, 'user_id');
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class, 'user_id');
    }

    public function companyItems()
    {
        return $this->belongsToMany(Items::class, 'item_magazine', 'user_id', 'item_id')->orderByDesc('in_stock')->sort();
    }

    public function basketItems()
    {
        return $this->belongsToMany(Items::class, 'basket', 'user_id', 'item_id')->withPivot('count');
    }

    public function favoriteItems()
    {
        return $this->belongsToMany(Items::class, 'user_favorite', 'user_id', 'item_id');
    }

    /**
     * @param string $phone
     */
    public function setFormattedPhone(string $phone)
    {
        $this->phone = preg_replace('/[^0-9]/', '', $phone);
    }

    /**
     * @return string|null
     */
    public function getFormattedPhoneAttribute()
    {
        return $this->phone ? '+' . $this->phone : '';
    }

    /**
     * @return string|null
     */
    public function getPhoneWithoutCountryCodeAttribute()
    {
        return substr($this->phone, 2);
    }

    public function initBasketWithSession()
    {
        $basketService = new BasketService(
            new SessionDriver()
        );

        $basketItems = $basketService->getItems();

        if (!count($basketItems)) {
            return false;
        }

        Basket::query()->where('user_id', $this->id)->forceDelete();

        /** @var BasketItem $basketItem */
        foreach($basketItems as $basketItem) {
            $this->basketItems()->attach($basketItem->getItemId(), [
                'count' => $basketItem->getCount(),
                'size_id' => $basketItem->getSize() ? $basketItem->getSize()->id : null
            ]);
        }

        $this->save();

        Session::forget('basketItems');

        notify('Товары с корзины перенесены в корзину вашего профиля.', 'info');

        return true;
    }

    /**
     * Mark the given user's email as verified.
     *
     * @return bool
     */
    public function markEmailAsVerified()
    {
        return $this->forceFill([
            'verification' => null,
            'active' => true,
        ])->save();
    }
}
