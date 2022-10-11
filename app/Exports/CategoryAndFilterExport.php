<?php

namespace App\Exports;

use App\Models\FilterCategory;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CategoryAndFilterExport implements FromCollection, ShouldAutoSize
{

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $header = [
            'ID',
            'Email',
            'ФИО',
            'Телефон',
            'Регион',
            'Город',
            'Тип',
            'Компания',
            'Бин',
            'Менеджер',
            'Скидка',
            'Статус',
            'Дата регистрации',
        ];
        $collection = FilterCategory::where('id', '<>', 0)->with('categories')->get()->groupBy('category_id');

        return $collection->prepend($header);
    }
}
