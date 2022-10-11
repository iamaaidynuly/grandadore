<?php

namespace App\Http\Controllers\Admin;

use App\Models\Items;
use App\Models\Part;
use App\Services\Zip\Zip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class ImportImagesController extends \App\Http\Controllers\Admin\BaseController
{

    public function import(Request $request)
    {
        if ($request->post()) {
            $request->validate([
                'file' => 'required|file|mimes:zip|max:20480',
            ], [
                'file.max' => 'Размер файла не может быть более 20 Мегабайтов.',
                'file.required' => 'Выберите файл.',
                'file.mimes' => 'Выберите файл zip'
            ]);

            $uploadedFilePath = $request->file('file')->getRealPath();
            if (!Zip::check($uploadedFilePath)) return redirect()->back()->withErrors(['file' => 'Недействительный ZIP']);
            $zip = Zip::open($uploadedFilePath);
            $zipContent = $zip->listFiles();
            $files = [];
            $allFiles = [];
            $codes = [];
            foreach ($zipContent as $file) {
                if (Str::contains('/', $file) || !preg_match('/\.[a-z]+$/', $file)) continue;
                $array = explode('.', $file);
                $ext = end($array);
                $codes[] = $array[0];
                $code = preg_replace('/(-[0-9]+)?(\.[a-z]+$)/', '', $file);
                if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png') {
                    $files[$code][] = $file;
                    $allFiles[] = $file;
                }

            }
            $changed_count = 0;
            if (count($files)) {
                $path = storage_path('zip/');
                $thumbs_path = storage_path('zip/thumbs/');
                $small_path = storage_path('zip/small/');
//                    $codes = array_keys($files);
                $findParts = Items::whereIn('code', $codes)->get();
                $zip->setMask(0755);
                $zip->extract($path, $allFiles);
                $zip->extract($thumbs_path, $allFiles);
                $zip->extract($small_path, $allFiles);

                foreach ($files as $key => $file) {
                    if (File::exists($thumbs_path . $file[0])) {
                        if (File::extension($thumbs_path . $file[0]) == 'jpg' || File::extension($thumbs_path . $file[0]) == 'jpeg' || File::extension($thumbs_path . $file[0]) == 'png') {
                            $image_for_resize = Image::make($thumbs_path . $file[0]);
                            $image_for_resize->fit(200, 200, function ($constraint) {
                                $constraint->upsize();
                            })->save($thumbs_path . $file[0]);
                        }
                    }
                    if (File::exists($path . $file[0])) {
                        if (File::extension($thumbs_path . $file[0]) == 'jpg' || File::extension($thumbs_path . $file[0]) == 'jpeg' || File::extension($thumbs_path . $file[0]) == 'png') {
                            $image_for_resize = Image::make($path . $file[0]);
                            $image_for_resize->fit(800, 800, function ($constraint) {
                                $constraint->upsize();
                            })->save($path . $file[0]);
                        }

                    }
                    if (File::exists($small_path . $file[0])) {
                        if (File::extension($small_path . $file[0]) == 'jpg' || File::extension($small_path . $file[0]) == 'jpeg' || File::extension($small_path . $file[0]) == 'png') {
                            $image_for_resize = Image::make($small_path . $file[0]);
                            $image_for_resize->fit(50, 50, function ($constraint) {
                                $constraint->upsize();
                            })->save($small_path . $file[0]);
                        }

                    }
                }
                $changed_count = $this->importImages($findParts, $files, $path, $thumbs_path, $small_path);
                clear_dir($path);
                clear_dir($thumbs_path);
                clear_dir($small_path);
            }

            return redirect()->back()->withInput(['changed_count' => $changed_count, 'count' => count($zipContent)]);
        }

        return view('admin.pages.items.images');
    }


    private function importImages($parts, $files, $path, $thumbs_path, $small_path)
    {
        $imagesPath = public_path('u/items/');
        $imagesThumbsPath = public_path('u/items/thumbs/');
        $imagesSmallPath = public_path('u/items/small/');
        $updateParts = [];
        foreach ($parts as $part) {
            $thisImages = $files[$part->code];
            $first = true;
            if ($part->image) {
                File::delete($imagesPath . $part->image);
                File::delete($imagesThumbsPath . $part->image);
                File::delete($imagesThumbsPath . $part->image);
            }
            foreach ($thisImages as $image) {
                $ext = preg_replace('/^.*\.([^.]+)$/', '$1', $image);

                $filename = file_name(18, $ext);
//                    while (file_exists($imagesPath.$filename) && file_exists($imagesThumbsPath.$filename));
                rename($path . $image, $imagesPath . $filename);
                rename($thumbs_path . $image, $imagesThumbsPath . $filename);
                rename($small_path . $image, $imagesSmallPath . $filename);
                $updateParts[] = [
                    'code' => $part->code,
                    'image' => $filename,
                ];

            }
        }
        $data['changed_count'] = 0;
        foreach ($updateParts as $part) {
            $item = Items::where('code', $part['code'])->first();
            if (!empty($item)) {
                $data['changed_count']++;
                $item->image = $part['image'];
                $item->save();
            }
        }

        return $data['changed_count'];
    }

    public static function getIncrement()
    {
        $model = new Items();
        $database = $model->getConnection()->getDatabaseName();
        $table = $model->getTable();

        return DB::select("SELECT `AUTO_INCREMENT` as `increment` FROM  INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '$database' AND TABLE_NAME = '$table'")[0]->increment;
    }
}
