<?php

namespace App\Http\Controllers\Site\Cabinet;

use App\Http\Controllers\Site\BaseController;
use App\Models\Reviews;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewsController extends BaseController
{

    public function add(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'message' => 'string|nullable|max:255',
            'rating' => 'int|max:6|min:1|required',
        ],
            [
                'rating.required' => 'Оценка обязательно',
                'rating.max' => ' Мах Оценка 6',
                'rating.int' => 'Оценка должно быть цифрой',
                'rating.min' => 'Введите оценку',
                'message.max' => ' Коментарий мах 255',
                'message.string' => ' Коментарий должно быть текстом'

            ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 200);
        }
        if ($a = Reviews::action(null, $request->all())) {
            return $a;
//            return redirect()->route('admin.news.main');
        } else {

            return redirect()->back()->withInput();
        }
    }

    private function validator($request, $ignore = false)
    {
        $inputs = $request->all();
        $result = [];
        $rules = [
            'message' => 'string|max:255',
            'rating' => 'int|max:6|required',
        ];

        $result['validator'] = Validator::make($inputs, $rules, [
            'rating.required' => 'Оценка обязательно',
            'rating.max' => ' Мах Оценка 6',
            'rating.int' => 'Оценка должно быть цифрой',
            'message.max' => ' Коментарий мах 255',
            'message.string' => ' Коментарий должно быть текстом',
        ]);
        $result['validator'] = $inputs;

        return $result;
    }
}

