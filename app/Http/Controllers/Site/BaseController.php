<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Basket;
use App\Models\Brands;
use App\Models\Category;
use App\Models\Language;
use App\Models\Order;
use App\Models\Page;
use App\Models\User;
use App\Services\BasketService\BasketFactory;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class BaseController extends Controller
{

    protected $shared = [];

    protected function view_share()
    {
        if (count($this->shared)) return false;
        $this->shared['infos'] = Banner::get('info');

        $this->shared['locale'] = app()->getLocale();
        $this->shared['languages'] = Language::getLanguages();

        $other_languages = [];
        foreach ($this->shared['languages'] as $language) {
            if ($language->iso == $this->shared['locale']) $this->shared['current_language'] = $language;
            else $other_languages[] = $language;
        }
        $this->shared['other_languages'] = $other_languages;

        $this->shared['homepage'] = Page::where('static', 'home')->first();
        $this->shared['in_footer'] = Page::where('in_footer', 1)->get();
        $this->shared['menu_pages'] = Page::getMenu();
        $this->shared['brandsCount'] = Brands::query()->count();

        $this->shared['categories'] = Category::where('deep', 0)->with(['childrens' => function (HasMany $children) {
            return $children->has('items')->with(['childrens' => function (HasMany $children) {
                return $children->has('items');
            }]);
        }])->sort()->get();



        $this->shared['categories_home'] = Category::where('deep', 0)->with('childrens')->where('in_home',1)->sort()->get();
//        dd($shared);


        //$this->shared['categories_top_section'] = Category::where('to_top_section', 1)->with('items')->sort()->get();
        $this->shared['current_url'] = url()->current();
        $this->shared['suffix'] = $this->shared['infos']->seo->title_suffix;
        $this->shared['urlLang'] = 'en';
        $this->shared['contacts'] = $this->shared['infos']->contacts->flip();
        $this->shared['footer_categories'] = Category::where('footer', 1)->inRandomOrder()->limit(10)->get();
        //$this->shared['home_banners'] = Banner::get('home');
        $this->shared['basketService'] = BasketFactory::createDriver();


        if (authUser()) {
            $user = User::where('id', authUser()->id)->first();

            if (!empty($user)) {
                $user->last_sign = Carbon::now()->toDateTimeString();
                $user->save();
            }
            $this->shared['basket_fro_basket'] = Basket::where('user_id', authUser()->id)->pluck('item_id')->toArray();
            $this->shared['basket_parts'] = Basket::getUserItems();

            $this->shared['activeOrdersCount'] = Order::with('items')->whereIn('status', [
                Order::STATUS_PENDING,
                Order::STATUS_NEW
            ])->where('user_id', authUser()->id)->count();
            $this->shared['archiveOrdersCount'] = Order::with('items')->whereNotIn('status', [
                Order::STATUS_PENDING,
                Order::STATUS_NEW
            ])->where('user_id', authUser()->id)->count();
        }

        $languages = Language::select('id', 'iso', 'title')->where('active', '>=', '0')->sort()->get();
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
            $isos[] = $language->iso;
        }
        $this->shared['isos'] = $isos;
        view()->share($this->shared);

        return true;
    }

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->view_share();

            return $next($request);
        });
    }

    protected function renderSEO($item)
    {
        $seo = [
            'title' => $item->seo_title,
            'keywords' => $item->seo_keywords,
            'description' => $item->seo_description,
        ];
        if (!$seo['title']) {
            if ($item->static == 'home') {
                $title = '';
            } else {
//                $title = $item->title;
                $title = '';
            }
            if ($this->shared['suffix']) {

                if ($title && $item->static != 'home') $title .= ' - ';
                $title .= $this->shared['suffix'];
            }
            $seo['title'] = $title;
        }

        return $seo;
    }

    protected function staticSEO($title)
    {

        $seo = ['title' => ''];
        if ($this->shared['suffix']) {
            if ($seo['title']) $seo['title'] .= ' - ';
            $seo['title'] .= $this->shared['suffix'];
        }

        return $seo;
    }
}
