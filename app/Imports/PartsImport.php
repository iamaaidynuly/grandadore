<?php

namespace App\Imports;

use App\Models\Brand;
use App\Models\BrandsItems;
use App\Models\Category;
use App\Models\ColorFilter;
use App\Models\ColorFilterRelation;
use App\Models\CompanyItems;
use App\Models\Criterion;
use App\Models\Engine;
use App\Models\ItemCategories;
use App\Models\ItemCriterions;
use App\Models\Items;
use App\Models\Modification;
use App\Models\Part;
use App\Models\PartCatalog;
use Illuminate\Support\Facades\DB;

class PartsImport extends AbstractImport
{
    protected $rules = [
        'code' => 'required|string|max:255',
        'brand' => 'nullable|integer',
        'category' => 'required|integer',
        'title' => 'required|string|max:500',
        'title_kz' => 'required|string|max:500',
        'description' => 'nullable|string|max:2500',
        'description_kz' => 'nullable|string|max:2500',
        'price' => 'required|numeric|between:1,10000000000',
//        'provider_price' => 'required|numeric|between:1,10000000000',
        'count' => 'nullable|integer|digits_between:1,10',
        'criteria' => 'nullable|string',
        'color' => 'nullable|string',

    ];

    protected $names = [
        'code' => 'артикул',
        'brand' => 'бренд',
        'category' => 'категория',
        'title' => 'название',
        'title_kz' => 'название kz',
        'description' => 'описание',
        'description_kz' => 'описание kz',
        'price' => 'цена на сайте',
//        'provider_price' => 'цена поставщика',
        'count' => 'остаток',
        'criteria' => 'фильтры',
        'color' => 'цвет',
    ];

//    private $all_brands = [];
    private $all_catalogue = [];
//    private $all_engines = [];
//    private $all_modifications = [];
    private $all_criteria = [];
    private $code = [];


    protected function filter($data)
    {

        $data['code'] = mb_strtolower($data['code']);
        if (in_array($data['code'], $this->code)) return $this->skip('duplicate_in_file');
        $this->code[] = $data['code'];
        $thisCatalogue = $data['category'];
        if (!in_array($thisCatalogue, $this->all_catalogue)) $this->all_catalogue[] = $thisCatalogue;
        if (!$data['count']) $data['available'] = 0;
        $this_criteria = [];
        if ($data['criteria']) {

            $criteria = explode(',', $data['criteria']);
            foreach ($criteria as $criterion) {
                $criterion = (int)trim($criterion);
                if (!in_array($criterion, $this_criteria)) $this_criteria[] = $criterion;
                if (!in_array($criterion, $this->all_criteria)) $this->all_criteria[] = $criterion;
            }
        }
        $data['criteria'] = $this_criteria;

        return $this->add($data);
    }

    protected function callback()
    {

        $result_parts = Items::select('id', 'code')->whereIn('code', $this->code)->get()->mapWithKeys(function ($item) {
            return [lower_case($item->code) => $item];
        });

        $increment = Items::getIncrement();
        $inserts = [];
        $updates = [];
        $updates_category = [];
        $inserts_category = [];
        $inserts_colors = [];
        $inserts_criterions = [];
        $updates_criterions = [];
        $inserts_companies = [];
        $updates_companies = [];
        $updates_colors = [];
$arr = [];

        foreach ($this->rows as $index => $row) {

            $category = Category::where('id', $row['category'])->first();
            if (empty($category)) {
                $this->errors->push([
                    'row' => ++$index,
                    'reason' => 'нет такой категории',
                ]);
            }

            $part = $result_parts[$row['code']] ?? null;

            if ($part) {
                $this_id = $part->id;
                $edit = true;
            } else {
                $this_id = $increment;
                $edit = false;
            }
            $continue = false;
            if ($continue) continue;
            if ($edit) {

                $updates[] = [
                    'id' => $this_id,
                    'code' => $row['code'],
                    'description' => json_encode(['ru' => $row['description'],'kz'=>$row['description_kz']]),
                    'title' => json_encode(['ru' => $row['title'],'kz'=>$row['title_kz']]),
                    'price' => $row['price'],
                    'uniq_code' => $row['code'] . '-' . $this_id,
                    'count' => (!empty($row['count']) || $row['count'] === "0") ? $row['count'] : 99999,
                    'delivery_price' => (!empty($row['delivery_price'])) ? $row['delivery_price'] : null,
                ];

                $updates_category[] = [
                    'count' => (!empty($row['count']) || $row['count'] === "0") ? $row['count'] : 99999,
                    'id' => $this_id,
                    'code' => $row['code'],
                    'title' => json_encode(['ru' => $row['title'],'kz'=>$row['title_kz']]),
                    'price' => $row['price'],
                    'uniq_code' => $row['code'] . '-' . $this_id,
                    'category_id' => $row['category'],
                    'delivery_price' => (!empty($row['delivery_price'])) ? $row['delivery_price'] : null,
                ];
                $updates_criterions[] = [
                    'id' => $this_id,
                    'criterions' => $row['criteria'],
                ];

                $updates_colors[] = [
                    'item_id' => $this_id,
                    'filter_id' => $row['color'],
                ];
                $updates_companies[] = [
                    'item_id' => $this_id,
                    'brand_id' => $row['brand']
                ];

            } else {
                $increment++;
                $inserts[] = [
                    'moderated' => true,
                    'id' => $this_id,
                    'code' => $row['code'],
                    'description' => json_encode(['ru' => $row['description'],'kz'=>$row['description_kz']]),
                    'title' => json_encode(['ru' => $row['title'],'kz'=>$row['title_kz']]),
                    'price' => $row['price'],
                    'uniq_code' => $row['code'] . '-' . $this_id,
                    'count' => (!empty($row['count']) || $row['count'] === "0") ? $row['count'] : 99999,
                    'delivery_price' => (!empty($row['delivery_price'])) ? $row['delivery_price'] : null,
                    'url' => to_url_suf($row['title']),
                ];
                $inserts_category[] = [
                    'id' => $this_id,
                    'code' => $row['code'],
                    'title' => json_encode(['ru' => $row['title'],'kz'=>$row['title_kz']]),
                    'price' => $row['price'],
                    'uniq_code' => $row['code'] . '-' . $this_id,
                    'category_id' => $row['category'],
                    'count' => (!empty($row['count']) || $row['count'] === "0") ? $row['count'] : 99999,
                    'delivery_price' => (!empty($row['delivery_price'])) ? $row['delivery_price'] : null,
                    'url' => to_url_suf($row['title']),
                ];
                $inserts_criterions[] = [
                    'id' => $this_id,
                    'criterions' => $row['criteria'],
                ];
                $inserts_colors[] = [
                    'item_id' => $this_id,
                    'filter_id' => $row['color'],
                ];
                $inserts_companies[] = [
                    'item_id' => $this_id,
                    'brand_id' => $row['brand']
                ];
            }
//            array_push($arr,$updates_colors);
        }

//        dd($arr);

        DB::transaction(function () use ($inserts, $updates, $inserts_category, $updates_category, $inserts_criterions,  $inserts_colors,  $updates_criterions, $inserts_companies,$updates_colors, $updates_companies) {
            ItemCriterions::actionImport($updates_criterions);

            if (count($inserts) && Items::insert($inserts)) {
                ItemCategories::actionImport($inserts_category);
                ItemCriterions::actionImport($inserts_criterions);
                brandsItems::actionImport($inserts_companies);
                ColorFilterRelation::actionImport($inserts_colors);
            }

            if (count($updates) && Items::insertOrUpdate($updates, ['title', 'uniq_code', 'price', 'code', 'count'])) {
                ItemCategories::actionImport($updates_category);
                ItemCriterions::actionImport($updates_criterions);
                brandsItems::actionImport($updates_companies);
                ColorFilterRelation::actionImport($updates_colors);
            }
        });
    }
}
