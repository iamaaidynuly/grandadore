<?php

namespace App\Http\Controllers\Admin;

use App\Models\Banner;
use App\Models\Basket;
use App\Models\Brands;
use App\Models\Career;
use App\Models\Category;
use App\Models\CompanyItems;
use App\Models\CompanyPackages;
use App\Models\Gallery;
use App\Models\Items;
use App\Models\Language;
use App\Models\News;
use App\Models\Packages;
use App\Models\Page;
use App\Models\Project;
use App\Models\Rooms;
use App\Models\User;
use App\Services\Notify\Facades\Notify;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class GalleriesController extends BaseController
{
    //region Private
    private $data = [
        'title' => 'Галерея',
    ];
    private $settings = [
        'thumb_method' => 'resize',
        'thumb_width' => 400,
        'thumb_height' => 400,
        'thumb_upsize' => true,
        'thumb_aspect' => true,

        'method' => 'resize',
        'width' => 800,
        'height' => 800,
        'upsize' => true,
        'aspect' => true,
    ];
    private $brandsItemSettings = [
        'thumb_method' => 'resize',
        'thumb_width' => 277,
        'thumb_height' => 157,
        'thumb_upsize' => true,
        'thumb_aspect' => true,

        'method' => 'resize',
        'width' => 831,
        'height' => 471,
        'upsize' => true,
        'aspect' => true,
    ];
    private $pagesItemSettings = [
        'thumb_method' => 'resize',
        'thumb_width' => 444,
        'thumb_height' => 261,
        'thumb_upsize' => true,
        'thumb_aspect' => true,

        'method' => 'resize',
        'width' => 888,
        'height' => 522,
        'upsize' => true,
        'aspect' => true,
    ];
    private $gallery;
    private $key;

    private function verify($gallery, $key)
    {
        $this->gallery = $this->data['gallery'] = $gallery;
        $this->key = $this->data['key'] = $key;
        $method_name = 'gallery_' . $gallery;
        if (!method_exists($this, $method_name)) abort(404);
        $use_keys = $key === null ? false : true;
        $require_keys = (new \ReflectionMethod($this, $method_name))->getNumberOfRequiredParameters() == 0 ? false : true;
        if ($use_keys !== $require_keys) abort(404);
        if ($key) $this->{$method_name}($key);
        else $this->{$method_name}();
    }

    private function verifyFromRequest($request)
    {
        $gallery = $request->input('gallery');
        $key = $request->input('key');
        if (!$gallery || ($key !== null && !is_id($key))) abort(404);
        $this->verify($gallery, $key);
    }

    private function set(array $new_settings)
    {
        $this->settings = array_merge($this->settings, $new_settings);
    }

    public function show($gallery, $key = null)
    {
        $this->verify($gallery, $key);
        $this->data['images'] = Gallery::adminList($gallery, $key);
        if (explode('/', url()->previous())[3] == 'company') {
            $this->shared['locale'] = app()->getLocale();
            $this->shared['languages'] = Language::getLanguages();
            $other_languages = [];
            foreach ($this->shared['languages'] as $language) {
                if ($language->iso == $this->shared['locale']) $this->shared['current_language'] = $language;
                else $other_languages[] = $language;
            }
            $this->shared['other_languages'] = $other_languages;
            $this->shared['homepage'] = Page::where('static', 'home')->first();

            $this->shared['menu_pages'] = Page::getMenu();
            $this->shared['categories'] = Category::where('deep', 0)->with('childrens')->sort()->get();
            $this->shared['current_url'] = url()->current();
            $this->shared['infos'] = Banner::get('info');
            $this->shared['suffix'] = $this->shared['infos']->seo->title_suffix;
            $this->shared['urlLang'] = 'en';
            $this->shared['contacts'] = $this->shared['infos']->contacts->flip();
            if (Auth::check()) {
                $this->shared['basket_fro_basket'] = Basket::where('user_id', auth()->user()->id)->pluck('item_id')->toArray();
                $this->shared['basket_parts'] = Basket::getUserItems();
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

            return view('site.pages.cabinet.company.gallery', $this->data);
        }

        return view('admin.pages.gallery.main', $this->data);
    }

    public function sort()
    {
        $ids = Gallery::sortable(true);
        if (!$ids) return response()->json(false);

        return response()->json(true);
    }

    public function delete(Request $request)
    {
        $result = ['success' => false];
        $id = $request->input('item_id');


        if ($id && is_id($id)) {
            $item = Gallery::where('id', $id)->first();
            if ($item && Gallery::deleteItem($item)) $result['success'] = true;
        }
        if (explode('/', url()->previous())[3] == 'company') {
            return redirect()->route('company.gallery', ['key' => 'items_item', 'id' => $request->key]);
        }

        return response()->json($result);
    }

    public function add(Request $request)
    {
        $this->verifyFromRequest($request);

        $images = $request->images;
        Validator::make(['images' => $images], [
            'images' => 'required|array',
            'images.*' => 'image|mimes:png,jpeg,gif'
        ], [
            'required' => 'Выберите изоброжение(я)',
            'array' => 'Выберите изоброжение(я)',
            'image' => 'Формат не поддерживается',
            'mimes' => 'Формат не поддерживается',
        ])->validate();

        if ($request->input('gallery') == 'brands_item'){
            $this->settings = $this->brandsItemSettings;
        }
        if ($request->input('gallery') == 'pages'){
            $this->settings = $this->pagesItemSettings;
        }

        if ($request->input('gallery') == 'users') {
            if (Gallery::addImages($this->gallery, $this->key, $images, $this->settings)) {
                Notify::success('Изоброжения успешно добавлены');
            } else {
                Notify::error('Некаторые изоброжения не добавлены');
            }

            return redirect()->route('admin.gallery', ['gallery' => $this->gallery, 'key' => $this->key]);
        }

        if (Gallery::addImages($this->gallery, $this->key, $images, $this->settings)) {
            Notify::success('Изоброжения успешно добавлены');
        } else {
            Notify::error('Некаторые изоброжения не добавлены');
        }
        $args = ['gallery' => $this->gallery];
        if ($this->key) $args['key'] = $this->key;

        return redirect()->route('admin.gallery', $args);
    }

    public function edit(Request $request)
    {
        $id = $request->input('item_id');
        $response = ['success' => false];
        if (is_id($id) && $item = Gallery::where('id', $id)->first()) {
            $values = $request->only('alt', 'title');
            Gallery::updateSeo($item, $values);
            $response['success'] = true;
        }

        return response()->json($response);

    }

    //endregion

    private function gallery_about()
    {
        $this->data['title'] = 'Галерея страницы о компании';
        $this->data['back_url'] = route('admin.pages.main');
    }

    private function gallery_pages($key)
    {
        $item = Page::getPage($key);
        $this->data['title'] = 'Галерея страницы "' . $item->a('title') . '"';
        $this->data['back_url'] = route('admin.pages.main');
    }

    private function gallery_news()
    {
        $this->data['title'] = 'Галерея страницы новостей';
        $this->data['back_url'] = route('admin.pages.main');
    }

    private function gallery_restaurant()
    {
        $this->data['title'] = 'Галерея страницы ресторана';
        $this->data['back_url'] = route('admin.pages.main');
    }

    private function gallery_home()
    {
        $this->data['title'] = 'Галерея главной страницы';
        $this->data['back_url'] = route('admin.pages.main');
    }

    private function gallery_news_item($key)
    {
        $item = News::getItem($key);
        $this->data['title'] = 'Галерея новости "' . $item->a('title') . '"';
        $this->data['back_url'] = route('admin.news.main');
    }

    private function gallery_brands_item($key)
    {
        $item = Brands::getItem($key);
        $this->data['title'] = 'Галерея бренда "' . $item->a('title') . '"';
        $this->data['back_url'] = route('admin.brands.main');
    }

    private function gallery_items_item($key)
    {
        $item = Items::getItem($key);
        $this->data['title'] = 'Галерея товара "' . $item->a('title') . '"';
        $this->data['back_url'] = route('admin.items.index');
    }

    private function gallery_users($key)
    {
        $item = User::query()->where('type', 1)->where('id', $key)->firstOrFail();

        $this->data['title'] = 'Галерея компании "' . $item->name . '"';
        $this->data['back_url'] = route('admin.users.view.magazine');
    }
}
