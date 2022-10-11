<?php

namespace App\Models;

use App\Http\Traits\HasTranslations;
use App\Http\Traits\UrlUnique;
use Illuminate\Support\Facades\Cache;

class UserMessage extends AbstractModel
{
    use HasTranslations, UrlUnique;

    protected $table = 'user_messages';

    private static function cacheKey()
    {
        return 'user_messages';
    }


    public static function clearCaches()
    {
        Cache::forget(self::cacheKey());
    }

    public static function getMessages()
    {
        return Cache::rememberForever(self::cacheKey(), function () {
            return self::select('message', 'email', 'phone', 'name', 'created_at')->get();
        });
    }

    public static function adminList()
    {
        return self::select('message', 'id', 'email', 'phone', 'name', 'created_at')->get();

    }


    public static function action($model, $inputs)
    {
        self::clearCaches();
        $model = new self;
        $model->email = $inputs['email'];
        $model->phone = $inputs['phone'];
        $model->message = $inputs['message'];
        $model->name = $inputs['name'];

        $model->save();

        return true;
    }

    public static function getItem($id)
    {
        return self::where('id', $id)->firstOrFail();
    }


    public static function deleteItem($model)
    {
        self::clearCaches();

        return $model->delete();
    }

    public static function active($userAll){
        foreach ($userAll as $user){
            $user->active = 1 ;
            $user->update() ;
        }
    }
}
