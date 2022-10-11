<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AddressRequest;
use App\Models\Banner;
use App\Models\Catalogue;
use App\Models\Otziv;
use App\Models\PickupPoint;
use App\Models\Product;
use App\Models\ProductOption;
use App\Services\Notify\Facades\Notify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductsController extends BaseController
{
    public function main($id)
    {
        $data = ['catalogue' => Catalogue::getItem($id)];
        $data['back_url'] = route('admin.catalogue.main');
        $data['title'] = 'Товары раздела "' . $data['catalogue']->a('title') . '"';
        $data['items'] = $data['catalogue']->products;
        $data['default_image'] = Banner::get('info')->data->product_image();

        return view('admin.pages.products.main', $data);
    }

    public function add($id)
    {
        $data = [];
        $data['catalogue'] = Catalogue::getItem($id);
        $data['title'] = 'Добавление продукта';
        $data['back_url'] = route('admin.products.main', ['id' => $id]);
        $data['current_catalogue'] = $data['catalogue']->id;
        $data['catalogues'] = Catalogue::adminList();
        $data['options'] = ProductOption::adminList();
        $data['edit'] = false;

        return view('admin.pages.products.form', $data);
    }

    public function add_put(Request $request)
    {
        $catalogue = Catalogue::getItem($request->input('catalogue_id') ?? 0);
        $validator = $this->validator($request);
        $validator['validator']->validate();
        if (Product::action(null, $validator['inputs'])) {
            Notify::success('Раздел успешно добавлен.');

            return redirect()->route('admin.products.main', ['id' => $catalogue['id']]);
        } else {
            Notify::get('error_occurred');

            return redirect()->back()->withInput();
        }
    }

    public function edit($id)
    {
        $data = [];
        $data['item'] = Product::getItem($id);
        $data['title'] = 'Редактирование продукта';
        $data['back_url'] = route('admin.products.main', ['id' => $data['item']->catalogue_id]);
        $data['options'] = ProductOption::adminList();
        $data['catalogues'] = Catalogue::adminList();
        $data['current_catalogue'] = $data['item']->catalogue_id;
        $data['product_options'] = $data['item']->options->pluck('id')->toArray();
        $data['edit'] = true;

        return view('admin.pages.products.form', $data);
    }

    public function edit_patch($id, Request $request)
    {
        $item = Product::getItem($id);
        $validator = $this->validator($request, $id);
        $validator['validator']->validate();
        if (Product::action($item, $validator['inputs'])) {
            Notify::success('Товар успешно редактирован.');

            return redirect()->route('admin.products.edit', ['id' => $item->id]);
        } else {
            Notify::get('error_occurred');

            return redirect()->back()->withInput();
        }
    }

    public function sort()
    {
        return Product::sortable();
    }

    public function delete(Request $request)
    {
        $result = ['success' => false];
        $id = $request->input('item_id');
        if ($id && is_id($id)) {
            $item = Product::find($id);
            if ($item && Product::deleteItem($item)) $result['success'] = true;
        }

        return response()->json($result);
    }

    private function validator($request, $ignore = false)
    {
        $inputs = $request->all();
        if (!empty($inputs['url'])) $inputs['url'] = lower_case($inputs['url']);
        $inputs['generated_url'] = !empty($inputs['title'][$this->urlLang]) ? to_url($inputs['title'][$this->urlLang]) : null;
        $request->merge(['url' => $inputs['url']]);
        $unique = $ignore === false ? null : ',' . $ignore;
        $result = [];
        $rules = [
            'generated_url' => 'required_with:generate_url|string|nullable',
            'image' => 'nullable|image',
            'price' => 'required|numeric|between:1,1000000',
            'sale' => 'nullable|numeric|between:1,1000000'
        ];
        if (empty($inputs['generate_url'])) {
            $rules['url'] = 'required|is_url|string|unique:products,url' . $unique . '|nullable';
        }
        $result['validator'] = Validator::make($inputs, $rules, [
            'generated_url.required_with' => 'Введите название (' . $this->urlLang . ') чтобы сгенерировать URL.',
            'url.required' => 'Введите URL или подставьте галочку "сгенерировать автоматический".',
            'url.is_url' => 'Неправильный URL.',
            'url.unique' => 'URL уже используется.',
//            'image.required' => 'Выберите Изображение',
            'image.image' => 'Неверное Изображение',
            'price.required' => 'Введите цену',
            'price.numeric' => 'Цена должна иметь только цифры',
            'sale.numeric' => 'Скидка должна иметь только цифры',
            'price.between' => 'Цена должна быть между :min и :max',
            'sale.between' => 'Скидка должна быть между :min и :max',
        ]);
        $result['inputs'] = $inputs;

        return $result;
    }


    public function comment(){

        $otzivAllActive = Otziv::where('activ','=','0')->get();
        foreach ($otzivAllActive as $value){
            $value->activ=1;
            $value->update();
        }

        $otzivAll = Otziv::where('id','>','0')->with('item')->get();


        return view('admin.comment.comment',compact('otzivAll'));

    }

    public function commentStatus(Request $request){
        $otziv=Otziv::find($request->id);
        if($otziv->status == 0){
            $otziv->status =1 ;
        }else{
            $otziv->status =0 ;
        }

        $otziv->update();

    }

    public function commentRemove(Request $request){
        Otziv::find($request->id)->delete();
    }

    public function address(){

        $pickupPoint = PickupPoint::where('id','>',0)->get();



        return view('admin.pages.address',compact('pickupPoint'));
    }

    public function changeStatus(Request $request ){
        $pickup=PickupPoint::find($request->id);

        $pickup->active = !$pickup->active;
        $pickup->update();

    }


    public function deleteAddress(Request $request){
        PickupPoint::find($request->id)->delete();
        return back() ;
    }

    public function addAddress(){
        return view('admin.pages.addAddress');
    }

    public function createAddress(Request $request , PickupPoint $pickup){
        $pickup->address = $request->address ;
        $pickup->title = $request->title ;
        $pickup->phone = $request->phone ;
        $pickup->lat = $request->lat ;
        $pickup->lng = $request->lng ;
        $pickup->active = 1 ;
        $pickup->save();

        return back() ;
    }

    public function editAddress($id){
        $pickupPoint=PickupPoint::find($id);
        return view('admin.pages.editAddress',compact('pickupPoint'));
    }

    public function editThisAddress($id , Request $request){
        $pickup = PickupPoint::find($id);
        $pickup->address = $request->address ;
        $pickup->title = $request->title ;
        $pickup->phone = $request->phone ;
        $pickup->lat = $request->lat ;
        $pickup->lng = $request->lng ;
        $pickup->update();
        return back() ;
    }

}
