<?php

namespace App\Http\Controllers\Site;

use App\BistriZakazUser;
use App\Http\Requests\CommentRequest;
use App\Models\Banner;
use App\Models\BistriZakaz;
use App\Models\Brands;
use App\Models\BrandsItems;
use App\Models\Category;
use App\Models\ColorFilter;
use App\Models\CompanyItems;
use App\Models\CompanyOneTimePayment;
use App\Models\Favorite;
use App\Models\Filter;
use App\Models\Gallery;
use App\Models\HomeInfo;
use App\Models\ItemCategories;
use App\Models\ItemOptions;
use App\Models\Items;
use App\Models\MainSlide;
use App\Models\MinimumTotalCost;
use App\Models\News;
use App\Models\Order;
use App\Models\Otziv;
use App\Models\Page;
use App\Models\ReviewItem;
use App\Models\Search;
use App\Models\ShortLinks;
use App\Models\Support;
use App\Models\User;
use App\Models\UserMessage;
use App\Rules\FormattedPhone;
use App\Services\Basket\Facades\Basket;
use App\Services\BasketService\Drivers\DatabaseDriver;
use App\Services\PageManager\StaticPages;
use App\Models\ZakazatZvonok;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppController extends BaseController
{
    use StaticPages;

    protected function static_home($page)
    {

        $pageData['home'] = 'true';

        $parentCategories = Category::query()->with('nestedChildren')->whereNull('parent_id')->sort()->get();
        $pageData['categoriesChunks'] = $parentCategories;

        $pageData['home_info'] = HomeInfo::homeList(); // En bac toxaci tex@ karela dnel kam esi kam shorlinks@
        $pageData['slider'] = MainSlide::getHeaderSlides();
        $pageData['big_banner'] = Banner::get('home_big_image_banners');
        $pageData['home_banners'] = Banner::get('home');
        $pageData['short_links'] = ShortLinks::homeList();
        $pageData['brands'] = Brands::homeList();
        $pageData['brandPage'] = Page::select('title', 'url')->where(['static' => 'brands', 'active' => 1])->first();
        $pageData['seo'] = $this->renderSEO($page);
        $pageData['search'] = Search::homeList();

        return view('site.pages.home.index', $pageData);
    }

    protected function static_brands($page)
    {
        $data = [];
        $data['current_page'] = Page::where('static', 'brands')->firstOrFail();
        $data['brands'] = Brands::getBrands();
        $data['seo'] = $this->renderSEO($page);

        $breadcrumbs = [
            [
                'title' => $data['current_page']->title,
                'url'   => ''
            ]
        ];

        $data['breadcrumbs'] = $breadcrumbs;

        return view('site.pages.brands.index', $data);
    }

    protected function static_news($page)
    {
        $data = [];
        $data['current_page'] = Page::where('static', 'news')->first();
        $data['articles'] = News::query()->where('active', 1)->sort()->paginate(9);
        $data['seo'] = $this->renderSEO($page);
        $data['newsPage_banner'] = Banner::get('news');

        $breadcrumbs = [
            [
                'title' => $data['current_page']->title,
                'url'   => ''
            ]
        ];

        $data['breadcrumbs'] = $breadcrumbs;

        return view('site.pages.news.index', $data);
    }

    protected function static_oferta($page)
    {
        $data['current_page'] = $page;
        $data['item'] = Banner::get('oferta');

        $breadcrumbs = [
            [
                'title' => $data['current_page']->title,
                'url'   => ''
            ]
        ];

        $data['breadcrumbs'] = $breadcrumbs;

        return view('site.pages.oferta.index', $data);
    }

    protected function dynamic_page($page)
    {
        $data = [];
        $data['current_page'] = $page->id;
        $data['item'] = $page;
        $data['seo'] = $this->renderSEO($page);
        $data['gallery'] = Gallery::get('pages', $data['item']->id);

        $breadcrumbs = [
            [
                'title' => $data['item']->title,
                'url'   => ''
            ]
        ];

        $data['breadcrumbs'] = $breadcrumbs;

        return view('site.pages.dynamic.index', $data);
    }


    public function sendContactMessage(Request $request)
    {
        $this->validate($request, [
            'name'  => 'required|string|max:255',
            'phone' => ['required', 'string', new FormattedPhone()],
            'email' => 'required|string|email|max:255',
        ], [
            'required' => 'Заполните поле',
            'string'   => 'Поле должно быть текстом',
            'email'    => 'Эл. почта неправильного формата'
        ]);

        $zakazatZvonok = new ZakazatZvonok;
        $zakazatZvonok->name = $request->name;
        $zakazatZvonok->phone = $request->phone;
        $zakazatZvonok->email = $request->email;

        $zakazatZvonok->save();

        notify('Заявка на обратный звонок принята. Наш оператор свяжется с вами в течении рабочего дня.');

        return redirect()->back();
    }

    protected function static_contact($page)
    {
        $pageData['seo'] = $this->renderSEO($page);

        return view('site.pages.contact.index', $pageData);
    }

    protected function static_boutiques($page)
    {
        $companies = User::query()->where('admin', 0)->where('type', 1)->sort()->paginate(32);

        $pageData['seo'] = $this->renderSEO($page);
        $pageData['companies'] = $companies;

        return view('site.pages.company.index', $pageData);
    }

    protected function product_view($url)
    {
        $item = Items::where('url', $url)->where('active', 1)->where('moderated', 1)->firstOrFail();
        $company = CompanyItems::where('item_id', $item->id)->first();
        $pageData['company'] = $company ? User::where(['active' => 1, 'id' => $company->user_id])->first() : null;
        $pageData['options'] = ItemOptions::where('item_id', $item->id)->get();
        $pageData['item_gallery'] = Gallery::get('items_item', $item->id);
        $pageData['item'] = $item;
        $pageData['category'] = ItemCategories::where('item_id', $item->id)->with('categories')->first();
        $brands = BrandsItems::where('item_id', $item->id)->with('brands')->first();
        $pageData['brands'] = $brands;
        $pageData['filters'] = Filter::with(['criteria' => function (HasMany $criteria) use ($item) {
            $criteria->whereHas('item', function (Builder $query) use ($item) {
                $query->where('items.id', $item->id);
            });
        }])->whereHas('criteria', function (Builder $query) use ($item) {
            $query->whereHas('item', function (Builder $query) use ($item) {
                $query->where('items.id', $item->id);
            });
        })->get();

        $id = [$pageData['item']->id];
        $pageData['colorFilters'] = ColorFilter::query()->whereHas('items', function (Builder $query) use ($id) {
            $query->whereIn('color_filter_relations.item_id', $id);
        })->sort()->get();

        if (Auth::check() && auth()->user()->type == 0) {
            $pageData['item_favorite'] = Favorite::where('item_id', $item->id)->where('user_id', auth()->user()->id)->first();
        }
        if (Auth::check() && auth()->user()->type == 0) {
            $review = ReviewItem::where('item_id', $item->id)->where('user_id', \auth()->user()->id)->first();
        }
        $pageData['add_review'] = false;
        if (Auth::check() && auth()->user()->type == 0) {
            $pageData['basket_item'] = \App\Models\Basket::getItemView($item->id);
        }
        if (empty($review)) {
            $pageData['add_review'] = true;
        }

        $companies_where_one_time_package = CompanyOneTimePayment::where('created_at', '>', Carbon::now()->subWeek()->toDateTimeString())->where(['status' => 1, 'package_id' => 3])->pluck('company_id')->toArray();
        $similiar_items = CompanyItems::whereIn('user_id', $companies_where_one_time_package)->pluck('item_id')->toArray();
        $pageData['reviews'] = ReviewItem::where('item_id', $item->id)->with('reviews', 'user')->get();
        $similiar_items = Items::where('active', 1)->where('moderated', 1)->whereIn('id', $similiar_items)->inRandomOrder()->limit(8)->get();
        $pageData['similar_items'] = [];
        if (count($similiar_items) < 8) {
            $new_count = 8 - count($similiar_items);
            $new_items = Items::where(['active' => 1, 'moderated' => 1])->inRandomOrder()->limit($new_count)->get();
            foreach ($new_items as $item) {
                $pageData['similar_items'][] = $item;
            }
            foreach ($similiar_items as $item) {
                $pageData['similar_items'][] = $item;
            }
        }
        $pageData['seo'] = $this->renderSEO($pageData['item']);

        $breadcrumbs = [
            [
                'title' => $pageData['item']->title,
                'url'   => ''/*route('product.view', ['url' => $pageData['item']->url])*/
            ]
        ];


        $category = $pageData['item']->categories->first();

        $breadcrumbs[] = [
            'title' => $category->name,
            'url'   => !is_null($category->parent_id) ? route('products.category.list', ['url' => $category->url]) : null
        ];

        if ($category = $category->parent) {
            $breadcrumbs[] = [
                'title' => $category->name,
                'url'   => !is_null($category->parent_id) ? route('products.category.list', ['url' => $category->url]) : null
            ];
        }
        if ($category = $category->parent) {
            $breadcrumbs[] = [
                'title' => $category->name,
                'url'   => !is_null($category->parent_id) ? route('products.category.list', ['url' => $category->url]) : null
            ];
        }

        $itemOtziv = Items::where('url', $url)->with('otziv')->first();
        $countOtziv = count($itemOtziv->otziv->where('status', 1));
        $pageData['countOtziv'] = $countOtziv;
        $pageData['breadcrumbs'] = array_reverse($breadcrumbs);
        $pageData['url'] = $url;
        $otziv = Otziv::where('status', 1)->with('item')->get();

        $avgStar = Otziv::where('id', '>', 0)->avg('star');

        $pageData['avgStar'] = $avgStar;
        $pageData['otziv'] = $otziv;

        return view('site.pages.products.view', $pageData);
    }

    public function boutiquesView($alias)
    {
        $company = User::with('companyItems')->where('admin', 0)->where('type', 1)->where('url', $alias)->firstOrFail();

        $companyItems = $company->companyItems->pluck('id')->toArray();

        $companyCategories = Category::query()->whereNotNull('parent_id')->whereHas('items', function (Builder $query) use ($companyItems) {
            $query->whereIn('items.id', $companyItems);
        })->get();

        $pageData['seo'] = $this->renderSEO($company);
        $pageData['company'] = $company;
        $pageData['galleryImages'] = Gallery::query()->where([
            'gallery' => 'users',
            'key'     => $company->id
        ])->sort()->get();
        $pageData['companyCategories'] = $companyCategories;

        return view('site.pages.company.view', $pageData);
    }

    public function info()
    {
        return view('site.pages.info.index');
    }

    public function static_about($page)
    {

        $data = [];
        $data['current_page'] = Page::where('static', 'about')->first()->id;
        $data['seo'] = $this->renderSEO($page);
        $data['item'] = $page;
        $data['banner_about'] = Banner::get('about');

        return view('site.pages.about.index', $data);
    }

    public function support()
    {
        $data['support'] = true;
        $data['items'] = Support::where('active', 1)->get();
        $data['user'] = Auth::user() ?? [];

        return view('site.pages.support', $data);
    }

    public function productsCategoryList($url)
    {

        $category = Category::with(['children', 'parent' => function (HasOne $category) {
            return $category->with('children');
        }])->where('url', $url)->firstOrFail();

        $parentCategories = [];
        $parentCategories[] = $category->id;
        if ($parent = $category->parent) {
            $parentCategories[] = $category->parent->id;

            if ($parent->parent) {
                $parentCategories[] = $parent->parent->id;
            }
        }

        $parentCategories = array_merge($parentCategories, $category->children->pluck('id')->toArray());

        $pageData['filters'] = Filter::with(['criteria' => function (HasMany $criteria) use ($parentCategories) {
            $criteria->has('item')->whereHas('item', function (Builder $query) use ($parentCategories) {
                $query->whereHas('categories', function (Builder $query) use ($parentCategories) {
                    $query->whereIn('categories.id', $parentCategories);
                });
            });
        }])->whereHas('categories', function (Builder $query) use ($parentCategories) {
            return $query->whereIn('categories.id', $parentCategories);
        })->whereHas('criteria', function (Builder $query) use ($parentCategories) {
            $query->has('item')->whereHas('item', function (Builder $query) use ($parentCategories) {
                $query->whereHas('categories', function (Builder $query) use ($parentCategories) {
                    $query->whereIn('categories.id', $parentCategories);
                });
            });
        })->get();

        $pageData['colorFilters'] = ColorFilter::query()->has('items')->whereHas('items', function (Builder $query) use ($parentCategories) {
            $query->where(['active' => true, 'moderated' => true])->whereHas('categories', function (Builder $query) use ($parentCategories) {
                $query->whereIn('categories.id', $parentCategories);
            });
        })->sort()->get();

        $brandsIds = BrandsItems::query()->whereHas('items')->pluck('brand_id')->toArray();
        $pageData['brands'] = Brands::query()->whereIn('brands.id', $brandsIds)->sort()->get();

        $pageData['seo'] = $this->renderSEO($category);
        $pageData['category'] = $category;

        $breadcrumbs = [];
        $parent = $category->parent;
        $grandParent = $parent ? $parent->parent : null;

        if ($grandParent) {
            $breadcrumbs[] = [
                'title' => $grandParent->name
            ];
        }
        if ($parent) {
            $breadcrumbs[] = [
                'title' => $parent->name,
                'url'   => !is_null($parent->parent_id) ? route('products.category.list', ['url' => $parent->url]) : null
            ];
        }
        $breadcrumbs[] = [
            'title' => $category->name,
            'url'   => route('products.category.list', ['url' => $category->url])
        ];

        $pageData['breadcrumbs'] = $breadcrumbs;

        return view('site.pages.products.index', $pageData);
    }


    public function fastOrderView()
    {

        $data['user']  = Auth::user();


        return view('site.fastOrderView', $data);
    }

    public function fastOrder(Request $request)
    {

        if ($request->email == "" || $request->name == null || $request->nomer == null || $request->dostavka == null) {

            return back();
        }


        $databaseDriver = new DatabaseDriver;


        if (isset($request->count) && $request->count != null) {
            $user = new \App\Models\BistriZakazUser;

            $user->gorod = $request->gorod;
            $user->email = $request->email;
            $user->dostavka = $request->dostavka;
            $user->nomer = $request->nomer;
            $user->name = $request->name;

            $user->save();
            $user_id = $user->id;

            if (count($request->count) < 1) {

                $count = 0;
            } elseif ($request->count > 1) {
                $count = count($request->count);
            }

            for ($i = 0; $i < $count; $i++) {
                $zakaz = new BistriZakaz;

                $zakaz->user_id = $user_id;

                $zakaz->item_id = ($request->id)[$i];
                $zakaz->count = ($request->count)[$i];
                $zakaz->save();
            }
        }


        /*foreach($databaseDriver->getItems() as $item){

            $databaseDriver->delete($item->toArray()['itemId']);
        }*/

        return back()->with('remove', 'remove');
    }


    public function addComment(CommentRequest $request, Otziv $otziv)
    {
        $otziv->star = $request->rating;
        $otziv->name = $request->name;
        $otziv->email = $request->email;
        $otziv->otziv = $request->otziv;
        $otziv->status = 0;
        $otziv->item_id = $request->product;

        $otziv->save();

        return back();


    }


    public function newMessage(UserMessage $user, Request $request)
    {
        $this->validate($request, [
            'name'  => 'required|string|max:255',
            'phone' => 'required|string|phone|max:255',
            'email' => 'required|string|email|max:255',
        ], [
            'required' => 'Заполните поле',
            'string'   => 'Поле должно быть текстом',
            'email'    => 'Эл. почта неправильного формата'
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->message = $request->message;
        $user->phone = $request->phone;
        $user->active = 0;

        $user->new = 0;
        $user->save();

        return back()->with('success', 'отправлено');

    }


}
