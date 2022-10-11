<?php

namespace App\Models;

//use Illuminate\Support\Facades\File;
//use Intervention\Image\Facades\Image;

class ItemOptions extends AbstractModel
{


    public static function action($id = null, $data)
    {
        $itemOptions = self::where(['item_id' => $id])->delete();
        if (!empty($data['criterion']['new'])) {
            foreach ($data['criterion']['new'] as $option) {

                $insertData[] = [
                    'name' => $option['name'],
                    'value' => $option['value'],
                    'item_id' => $id,
                ];
            }
            self::insert($insertData);
        }
    }

}
