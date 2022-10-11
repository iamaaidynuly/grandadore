<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\BistriZakazUser;
use App\Models\Brands;
use App\Models\Items;
use App\Models\Language;
use App\Models\Order;
use App\Models\Otziv;
use App\Models\Page;
use App\Models\PriceApplication;
use App\Models\User;
use App\Models\UserMessage;
use App\Models\ZakazatZvonok;

class BaseController extends Controller
{
    protected $languages;
    protected $lang;
    protected $isos;
    protected $urlLang;
    protected $shared;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!$this->shared) {
                $this->view_share();
            }

            return $next($request);
        });
    }

    protected function view_share()
    {
        $languages = Language::select('id', 'iso', 'title')->where('active', '>=', '0')->sort()->get();
        $languagesResult = [];
        $isos = [];
        $admin_language = settings('admin_language', 1);


        $url_language = settings('url_language', 4);
        foreach ($languages as $language) {
            if ($language->id == $admin_language) {
                $this->lang = $language->iso;
            }
            if ($language->id == $url_language) {
                $this->urlLang = $language->iso;
            }
            $languagesResult[] = [
                'iso' => $language->iso,
                'title' => $language->title,
            ];
            if(isset($language->iso)) $isos[] = $language->iso;

        }
        try {
            $default_language = $languages->where('id', settings('default_language', 1))->first()->iso;
        }catch (\Exception $e){
            $default_language = 'ru';
        }
        $bistriZakazUser =BistriZakazUser::orderStatus();


        if (!$this->lang) $this->lang = $default_language;
        if (!$this->urlLang) $this->urlLang = $default_language;

        if(isset($language->iso)){
        $this->languages = $languagesResult;

        $this->isos = $isos;
        }
        $this->shared = [
            'all_brands_count' => Brands::where('active', 1)->count(),
            //'all_magazines_count' => User::where('type', 1)->where('admin', 0)->count(),
            //'new_magazines_count' => User::where('type', 1)->where(['admin' => 0, 'active' => 0])->count(),
            'all_users_count' => User::where('type', 0)->where('admin', 0)->count(),
            'new_users_count' => User::where('type', 0)->where(['admin' => 0, 'active' => 0])->count(),
            'lang' => $this->lang,
            'languages' => $languagesResult,
            'isos' => $isos,
            'urlLang' => $this->urlLang,
            'count'=> BistriZakazUser::count(),
            'allZakazat' => ZakazatZvonok::allZakazat(),
            'bistri_status' => $bistriZakazUser,
            'new_orders_count' => Order::getCount(Order::STATUS_NEW),
            'pending_orders_count' => Order::getCount(Order::STATUS_PENDING),
            'declined_orders_count' => Order::getCount(Order::STATUS_DECLINED),
            'done_orders_count' => Order::getCount(Order::STATUS_DONE),
            'all_items_count_for_top' => Items::all()->count(),
            'new_items_count_for_top' => Items::where('moderated', 0)->get()->count(),
            'footer' => Page::where('in_footer',1)->get(),
            'countUserMess' => UserMessage::where('active',0)->get()->count(),
            'countnewMessage' => Otziv::where('activ',0)->get()->count(),

        ];
        view()->share($this->shared);
    }
}
