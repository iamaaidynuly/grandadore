<?php

namespace App\Http\Controllers\Admin;


use App\Models\Category;
use App\Models\CategoryDiscount;
use App\Models\DiscountForUser;
use App\Models\FilterCategory;
use App\Models\ItemCategories;
use App\Services\Notify\Facades\Notify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class CategoriesController extends BaseController
{

    public function create(Request $request, $parent_id = null)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'name.*' => 'string|required|max:255',
                'parent_id' => 'integer|nullable|max:255',
                'footer' => 'integer|nullable|max:1',
                'image' => 'nullable|image',
            ]);
            $categoryModel = new Category;
            $response = $categoryModel->createCategory($request);
            if ($response) {
                Notify::success('Категория создана', $title = null, $options = []);
            } else {
                Notify::error('Произошла ошибка', $title = null, $options = []);
            }
        }
        $pageData = [
            'categories' => Category::where('deep', '<=', 2)->get(),
            'parent' => Category::where(['id' => $parent_id])->first()
        ];
        $pageData['onlyParents'] = $pageData['parent'] ? $pageData['parent']->onlyParents() : [];

        return view('admin.pages.category.add', $pageData);
    }


    public function categoryDiscount($id)
    {

        $data['discounts'] = DiscountForUser::all();
        $data['category_id'] = $id;
        $data['category_discount'] = CategoryDiscount::where('category_id', $id)->first();

        return view('admin.pages.category.category_discount', $data);
    }

    public function addDiscountToCategory(Request $request, $id)
    {
        $result = CategoryDiscount::action($id, $request->discount);
        $data['discounts'] = DiscountForUser::all();
        $data['category_id'] = $id;
        $data['category_discount'] = CategoryDiscount::where('category_id', $id)->first();

        return view('admin.pages.category.category_discount', $data);
    }

    public function update($id, Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'name.*' => 'string|required|max:255',
                'parent_id' => 'integer|nullable|max:255',
                'footer' => 'integer|nullable|max:1',
                'image' => 'image',
            ]);

            $response = Category::updateCategory($id, $request);
            if ($response['status']) {
                Notify::success('Категория отредактирована', $title = null, $options = []);

                return redirect()->route('admin.category.list', ['parent_id' => $response['parent_id']]);
            } else {
                Notify::error('Произошла ошибка', $title = null, $options = []);
            }
        }
        $pageData = [
            'categories' => Category::where('deep', '<=', 2)->get(),
            'categoryData' => Category::where(['id' => $id])->first()
        ];
        $pageData['parent'] = Category::where(['id' => $pageData['categoryData']->parent_id])->with(['children'])->first();
        $pageData['onlyParents'] = $pageData['parent'] ? $pageData['parent']->onlyParents() : $pageData['parent'];

        return view('admin.pages.category.edit', $pageData);
    }

    public function delete($id)
    {
        $categoryModel = Category::where(['id' => $id])->first();
        $childes = Category::where('parent_id', $categoryModel->id)->pluck('id')->toArray();
        if (!empty($childes) && count($childes)) {
            Category::whereIn('id', $childes)->delete();
            ItemCategories::whereIn('category_id', $childes)->delete();
            FilterCategory::whereIn('category_id', $childes)->delete();
        }
        File::delete(public_path('u/categories/' . $categoryModel->image));
        if ($categoryModel->delete()) {
            ItemCategories::where('category_id', $id)->delete();
            FilterCategory::where('category_id', $id)->delete();
        }

        echo true;
    }

    public function sort()
    {
        return Category::sortable();
    }

    public function categoriesList($parent_id = null)
    {

        if (!empty($parent_id)) {
            $hasItem = ItemCategories::where('category_id', $parent_id)->first();
            if (!empty($hasItem)) {
                Notify::error('Невозможно добавить подраздел так как к этом разделу прикреплен товар ', $title = null, $options = []);

                //return redirect()->back();
            }
        }
        $pageData = [
            'categories' => Category::where(['parent_id' => $parent_id])->with(['children'])->orderBy('sort', 'asc')->get(),
            'parent' => Category::where(['id' => $parent_id])->with(['children'])->first(),
        ];
        $pageData['onlyParents'] = $pageData['parent'] ? $pageData['parent']->onlyParents() : [];


        return view('admin.pages.category.list', $pageData);
    }

    public function changeParent($id, $parent_id)
    {
        $categoryModel = Category::where(['id' => $id])->first();
        $categoryModel->parent_id = $parent_id != 0 ? $parent_id : null;
        echo $categoryModel->save();
    }

    public function changeOrder(Request $request)
    {
        if ($request->has('orderedItems')) {
            $insertData = $request->input('orderedItems');
            foreach ($insertData as $datum) {
                $categoryModel = Category::where(['id' => $datum['id']])->first();
                $categoryModel->sortable = $datum['sortable'];
                $categoryModel->save();
            }
            echo true;
        }
    }

    public function getChildren($id)
    {
        $categoryModel = Category::where(['parent_id' => $id])->get();
        if (count($categoryModel) > 0) {
            echo json_encode($categoryModel->toArray());
        } else {
            echo json_encode([]);
        }
    }

    public function getMultiple(Request $request)
    {
        if ($request->has('ids')) {
            $responseData = Category::whereIn('parent_id', $request->input('ids'))->get();
            echo json_encode($responseData);
        }
    }

    public function deleteImage($id)
    {
        $categoryModel = Category::where(['id' => $id])->first();
        File::delete(public_path('u/categories/' . $categoryModel->image));
        $categoryModel->image = null;
        echo $categoryModel->save();
    }


    public function in_home(Request $request){


      $category= Category::find($request->id);

      if($category->in_home == 1){
          $category->in_home= 0;
      }
      else{
          $category->in_home = 1;
      }
        $category->save();
    }
}
