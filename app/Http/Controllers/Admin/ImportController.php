<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Site\BaseController;
use App\Imports\AnalogsImport;
use App\Imports\AttachedPartsImport;
use App\Imports\CatalogsImport;
use App\Imports\CriteriaImport;
use App\Imports\EnginesImport;
use App\Imports\GenerationsImport;
use App\Imports\MarksImport;
use App\Imports\ModelsImport;
use App\Imports\ModificationsImport;
use App\Imports\PartsImport;
use App\Imports\RecommendedPartsImport;
use App\Models\Brands;
use App\Models\Category;
use App\Models\ColorFilter;
use App\Models\Filter;
use App\Models\FilterCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends \App\Http\Controllers\Admin\BaseController
{
    //region Private
    public function render(Request $request, $page = null)
    {
        $categories = Category::where('deep', 0)->with('childrens', 'filters')->get();
        $brands = Brands::adminList();
        $colors = ColorFilter::query()->sort()->get();
        if (empty($page)) {
            $page = 'parts';
        }
        if (!array_key_exists($page, self::IMPORTS)) abort(404);
        $import = self::IMPORTS[$page];
        if ($request->getMethod() == 'POST') {
            if (Validator::make($request->only('file'), [
                'file' => 'required|file|mimes:xlsx,xls,csv'
            ])->fails()) {
                $response = 'unvalidated';
            } else {
                $file = $request->file('file');
                $response = $import['importer']::import($file);
            }

            return redirect()->back()->with(['import_response' => $response]);
        } else {

            $data = [
                'title' => $import['title'] ?? null,
                'response' => session('import_response'),
                'columns' => $import['importer']::getColumns(),
                'categories' => $categories,
                'brands' => $brands,
                'colors' => $colors
            ];

            return view('admin.pages.items.import', $data);
        }
    }

    //endregion

    private const IMPORTS = [
        'parts' => [
            'importer' => PartsImport::class,
            'title' => 'Импортирование товаров',
        ],
    ];

    public function view($id)
    {
        $data['category'] = Category::where('id', $id)->firstOrFail();

        $filters_id = FilterCategory::where('category_id', $id)->get()->pluck('filter_id')->toArray();
        $data['filters'] = Filter::whereIn('id', $filters_id)->where('status', 1)->with('criteria')->get();

        return view('admin.pages.items.main', $data);
    }

    public function downloadExample()
    {
        return response()->download(storage_path('app/import-example.xlsx'), 'Образец импорта товаров.xlsx');
    }
}
