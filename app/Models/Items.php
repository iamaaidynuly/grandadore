<?php

namespace App\Models;

use App\Http\Controllers\Site\ExchangeController;
use App\Http\Traits\HasTranslations;
use App\Http\Traits\InsertOrUpdate;
use App\Http\Traits\Sortable;
use App\Http\Traits\UrlUnique;
use App\Services\ExchangeRateDetector\ExchangeRateDetector;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class Items extends AbstractModel
{
    use HasTranslations, UrlUnique, Sortable, InsertOrUpdate;

    public $translatable = ['title', 'short', 'description', 'seo_title', 'seo_description', 'seo_keywords'];
    public $deliver_sticker;
    public $show_company;
    public $show_address;
    public $filtersLoaded;

    public function getDeliveryStickerAttribute()
    {

        $item_id = $this->id;
        $company_id = CompanyItems::where('item_id', $item_id)->exists() ? CompanyItems::where('item_id', $item_id)->first()->user_id : 0;
        $package = CompanyPackages::where('created_at', '>', Carbon::now()->subMonth()->toDateTimeString())->where(['status' => 1])->where('company_id', $company_id)->with('package')->first();
        $one_time_payment_package_companies = CompanyOneTimePayment::where('created_at', '>', Carbon::now()->subWeek()->toDateTimeString())->where(['status' => 1, 'package_id' => 4, 'company_id' => $company_id])->first();
        if (empty($package)) {
            $package = Packages::where('id', 1)->first();
        } else {
            $package = $package->package[0];
        }

        if ($package->title_company == 1 || !empty($one_time_payment_package_companies)) {
            return true;
        }

        return false;
    }

    public function getShowCompanyAttribute()
    {

        $item_id = $this->id;
        $company_id = CompanyItems::where('item_id', $item_id)->first()->user_id;
        $package = CompanyPackages::where('created_at', '>', Carbon::now()->subMonth(1)->toDateTimeString())->where(['status' => 1])->where('company_id', $company_id)->with('package')->first();

        if (empty($package)) {
            $package = Packages::where('id', 1)->first();
        } else {
            $package = $package->package[0];
        }
        if ($package->stickers == 1) {
            return true;
        }

        return false;
    }

    public function getShowAddressAttribute()
    {

        $item_id = $this->id;
        $company_id = CompanyItems::where('item_id', $item_id)->first()->user_id;
        $package = CompanyPackages::where('created_at', '>', Carbon::now()->subMonth(1)->toDateTimeString())->where(['status' => 1])->where('company_id', $company_id)->with('package')->first();
        if (empty($package)) {
            $package = Packages::where('id', 1)->first();
        } else {
            $package = $package->package[0];
        }
        if ($package->check_city == 1) {
            return true;
        }

        return false;
    }

    public static function adminList()
    {
        return self::select('*')->with(['reviews', 'company', 'brands'])->orderBy('created_at', 'DESC')->with('categories')->get();
    }

    public static function getIncrement()
    {
        $model = new self();
        $database = $model->getConnection()->getDatabaseName();
        $table = $model->getTable();

        return DB::select("SELECT `AUTO_INCREMENT` as `increment` FROM  INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '$database' AND TABLE_NAME = '$table'")[0]->increment;
    }

    public static function getActiveItem($id)
    {
        return self::query()->where(['id' => $id, 'moderated' => 1])->first();
    }

    public function getDiscountPriceAttribute()
    {
        $discount = 0;

        $category = ItemCategories::where('item_id', $this->id)->with('categories')->first();
        if (!empty($category->categories->onlyParents()) && count($category->categories->onlyParents())) {
            $category_id = $category->categories->onlyParents()[0]->id;
            $discount_category = CategoryDiscount::where('category_id', $category_id)->with('discount')->first();
        }

        if (!empty($discount_category)) {
            if (!empty($discount_category->individual_discount)) {
                $discount += (int)$discount_category->individual_discount;
            } elseif (!empty($discount_category->discount)) {
                $discount += (int)$discount_category->discount->discount;
            }
        }
        $discount_user = UserDiscount::where('user_id', auth()->user()->id)->with('discount')->first();
        if (!empty($discount_user)) {
            if (!empty($discount_user->individual_discount)) {
                $discount += (int)$discount_user->individual_discount;
            } elseif (!empty($discount_user->discount)) {
                $discount += (int)$discount_user->discount->discount;
            }
        }
        if (!empty($this->delivery_price)) {
            $discount += (int)$this->delivery_price;
        }
        if ($discount >= 100) {
            $discount = 99;
        }

        return $discount;
    }

    private static function cacheKey()
    {
        return 'items';
    }

    public static function clearCaches()
    {
        Cache::forget(self::cacheKey());
    }

    public function reviews()
    {
        return $this->hasMany(ReviewItem::class, 'item_id');
    }

    public static function action($model, $inputs)
    {

        self::clearCaches();
        if (empty($model)) {
            $model = new self;
            $action = 'add';
            $ignore = false;
        } else {
            $action = 'edit';
            $ignore = $model->id;
        }
        $model['moderated'] = true;
        $model['active'] = (int)!empty($inputs['active']);
        $model['new'] = (int)!empty($inputs['new']);
        $model['top'] = (int)!empty($inputs['top']);
        $model['sale'] = (int)!empty($inputs['sale']);
        $url = self::url_unique(to_url($inputs['title']['ru']), $ignore);
        $model['url'] = $url;
        merge_model($inputs, $model, ['title', 'description', 'seo_title', 'seo_description']);
        $resizes = [
            [
                'width' => 800,
                'height' => 800,
                'aspect' => true,
                'method' => 'resize'
            ],
            [
                'width' => 640,
                'height' => 640,
                'aspect' => true,
                'dir' => 'thumbs/',
                'method' => 'resize'

            ],
            [
                'width' => 360,
                'height' => 360,
                'aspect' => true,
                'dir' => 'small/',
                'method' => 'resize'

            ]
        ];


if(upload_image('image', 'u/items/', $resizes)){
        $image = upload_image('image', 'u/items/', $resizes);
            $model->image = $image;
}

        $model['price'] = $inputs['price'] ?? 0;
        $model['provider_price'] = $inputs['provider_price'] ?? $inputs['price'] ?? 0;
        $model['code'] = $inputs['code'];
        if (!$ignore) {
            $model['uniq_code'] = $inputs['code'] . '-' . self::getIncrement();
        } else {
            $model['uniq_code'] = $model->uniq_code;
        }

        $model['count'] = $inputs['count'];
        if ($inputs['delivery_price']) {
            $model['delivery_price'] = (int)$inputs['delivery_price'];
        } else {
            $model['delivery_price'] = null;
        }
        if ($model->save()) {
            if (!empty($inputs['sizes']) && count($inputs['sizes'])) {
                $existingIds = $model->sizes->pluck('id');

                foreach ($inputs['sizes'] as $size) {
                    foreach ($size as $infoSize) {
                        $data['name'] = $infoSize['name'];
                        $data['price'] = $infoSize['price'];

                        if (!empty($infoSize['id']) && $model->sizes->contains($infoSize['id'])) {
                            $existingIds = $existingIds->filter(function ($id) use ($infoSize) {
                                return $id != $infoSize['id'];
                            });
                            $model->sizes()->where('id', $infoSize['id'])->update($data);
                        } else {
                            $model->sizes()->create($data);
                        }
                    }
                }

                $model->sizes()->whereIn('id', $existingIds->toArray())->delete();
            }
        }
        if ($model->save() && !empty($inputs['categories'])) {
            ItemCategories::action($model->id, $inputs['categories']);
            if (!empty($inputs['item_criterions'])) {
                ItemCriterions::action($model->id, $inputs['item_criterions']);
            }
            ItemOptions::action($model->id, $inputs);
            if (!empty($inputs['brands'])) {
                BrandsItems::action($model->id, $inputs['brands']);
            }
        }

        return true;
    }

    public static function getItem($id)
    {
        return self::findOrFail($id);
    }

    public static function deleteItem($model)
    {
        self::clearCaches();
        $path = public_path('u/items/');
        if (!empty($model->image)) File::delete($path . $model->image);
        if (!empty($model->image)) File::delete($path . 'thumbs/' . $model->image);
        Gallery::clear('item', $model->id);
        VideoGallery::clear('item', $model->id);
        $itemOptions = ItemOptions::where(['item_id' => $model->id])->delete();
        $itemSizes = ItemSizes::where(['item_id' => $model->id])->delete();
        ItemCategories::where('item_id', $model->id)->delete();

        ItemCriterions::where('item_id', $model->id)->delete();
        CompanyItems::where('item_id', $model->id)->delete();
        BrandsItems::where('item_id', $model->id)->delete();
        Basket::where('item_id', $model->id)->delete();

        return $model->delete();
    }

    public function getPrice()
    {
        $exchanges = Banner::get('exchange')->exchange;
        $index = collect(Banner::get('exchange')->exchange)->where('title', config('exchange'));
        if ($index->isNotEmpty()) {
            $index = $index->first();
        } else $index = $exchanges[0];

        return number_format($this->price * $index->rate);
    }

    public function bistriZakaz(){
        $this->hasOne(BistriZakaz::class,'item_id','id');
    }


    public function categories()
    {
        return $this->belongsToMany('App\Models\Category', 'items_categories', 'item_id', 'category_id');
    }

    public function colorFilters()
    {
        return $this->belongsToMany(ColorFilter::class, 'color_filter_relations', 'item_id', 'filter_id');
    }

    public function company()
    {
        return $this->hasMany('App\Models\CompanyItems', 'item_id')->with('users');
    }

    public function brands()
    {
        return $this->hasMany('App\Models\BrandsItems', 'item_id')->with('brands');
    }

    public function sizes()
    {
        return $this->hasMany(ItemSizes::class, 'item_id');
    }

    public function rates()
    {
        return $this->hasMany(ItemRate::class, 'item_id');
    }

    public function getAvgRating() : int
    {
        return $this->rates->avg('rating') ?? 0;
    }

    public function criteria()
    {
        return $this->belongsToMany(Criteria::class, 'criteria_relations', 'item_id', 'criteria_id');
    }

    public function getDiscountedPrice($format = false, $exchanged = false)
    {
        if ($exchanged) {
            $priceAttribute = $this->exchangedPrice();
        } else {
            $priceAttribute = $this->price;
        }

        $price = $priceAttribute - (($priceAttribute * $this->delivery_price) / 100);

        if ($format) {
            $price = formatPrice($price);
        }

        return $price;
    }

    public function filters()
    {
        if (!$this->filtersLoaded) {
            $criteriaIds = $this->criteria->pluck('id')->toArray();

            $filters = Filter::query()->whereHas('criteria', function (Builder $query) use ($criteriaIds) {
                $query->whereIn('criteria.id', $criteriaIds);
            })->get();

            $this->setAttribute('filters', $filters);

            $this->filtersLoaded = true;
        }

        return $this->getAttribute('filters');
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope('inStock', function (Builder $query) {
            $priceRaw = DB::raw("(CASE WHEN items.price='0' THEN 0 ELSE 1 END) as in_stock, items.*");

            $query->select($priceRaw);
        });
    }


    public function otziv(){
        return $this->hasMany(Otziv::class ,'item_id','id');
    }

    public function itemOrder(){
        $this->hasMany(ItemOrder::class, 'id','items_id');
    }

    public function exchangedPrice() : int
    {
        /** @var ExchangeRateDetector $detector */
        $detector = app()->get(ExchangeRateDetector::class);

        return $this->price * $detector->getRate();
    }

    public function getExchangedPriceAttribute() : int
    {
        return $this->exchangedPrice();
    }
}
