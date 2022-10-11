<?php

namespace App\Http\Controllers\Site\Cabinet\Company;

use App\Http\Controllers\Site\BaseController;
use App\Imports\PartsImport;
use App\Models\Category;
use App\Models\Filter;
use App\Models\FilterCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ImportController extends BaseController
{
    //region Private
    public function render(Request $request, $page = null)
    {
        $data['categories'] = Category::where('deep', 0)->with('childrens', 'filters')->get();
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
            ];

            return view('site.pages.cabinet.company.general.import', $data);
        }
    }

    //endregion

    private const IMPORTS = [

        'parts' => [
            'importer' => PartsImport::class,
            'title' => 'Импортирование запчастей',
        ],
    ];

    public function view($id)
    {
        $data['category'] = Category::where('id', $id)->firstOrFail();

        $filters_id = FilterCategory::where('category_id', $id)->get()->pluck('filter_id')->toArray();
        $data['filters'] = Filter::whereIn('id', $filters_id)->where('status', 1)->with('criteria')->get();

        return view('site.pages.cabinet.company.general.main', $data);
    }
}
