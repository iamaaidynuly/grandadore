<?php

namespace App\Http\Controllers\Site\Cabinet\Company;

use App\Http\Controllers\Site\BaseController;
use App\Models\CompanyItems;
use App\Models\Items;
use App\Models\Part;
use App\Services\Zip\Zip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class ImportImagesController extends BaseController
{

    public function import(Request $request)
    {
        if ($request->post()) {
            $request->validate([
                'file' => 'required|file|mimes:zip|max:20480‬',
            ], [
                'file.max' => 'Размер файла не может быть более 20 Мегабайтов.',
                'file.required' => 'Выберите файл.',
                'file.mimes' => 'Выберите файл zip'
            ]);

            $uploadedFilePath = $request->file('file')->getRealPath();
            if (!\App\Services\Zip\Zip::check($uploadedFilePath)) return redirect()->back()->withErrors(['file' => 'Недействительный ZIP']);
            $zip = Zip::open($uploadedFilePath);
            $zipContent = $zip->listFiles();
            $files = [];
            $allFiles = [];

            foreach ($zipContent as $file) {
                if (Str::contains('/', $file) || !preg_match('/\.[a-z]+$/', $file)) continue;
                $array = explode('.', $file);
                $ext = end($array);

                $code = preg_replace('/(-[0-9]+)?(\.[a-z]+$)/', '', $file);
                if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png') {
                    $files[strtolower($code)][] = $file;
                    $allFiles[] = $file;
                }

            }

            $changed_count = 0;
            if (count($files)) {
                $path = storage_path('zip/');
                $thumbs_path = storage_path('zip/thumbs/');
                $small = storage_path('zip/small/');

                $codes = array_keys($files);
                $codes = explode(",", implode(",", $codes));
                $company_items = CompanyItems::where('user_id', auth()->user()->id)->pluck('item_id')->toArray();

                $findParts = Items::whereIn('code', $codes)->whereIn('id', $company_items)->get();
                $findParts_codes = Items::whereIn('code', $codes)->whereIn('id', $company_items)->pluck('code')->toArray();
                $no_match_codes = array_diff($codes, $findParts_codes);
                //   dd($findParts);

//                    dd($findParts,$codes);
                $zip->setMask(0755);
                $zip->extract($path, $allFiles);
                $zip->extract($thumbs_path, $allFiles);
                $zip->extract($small, $allFiles);
                foreach ($files as $key => $file) {
                    if (File::exists($thumbs_path . $file[0])) {
                        if (File::extension($thumbs_path . $file[0]) == 'jpg' || File::extension($thumbs_path . $file[0]) == 'jpeg' || File::extension($thumbs_path . $file[0]) == 'png') {
                            $image_real_sizes = getimagesize($thumbs_path . $file[0]);
                            $image_real_width = $image_real_sizes[0];
                            $image_real_height = $image_real_sizes[1];
                            $width = 400;
                            $height = 400;
                            if ($image_real_width <= $width) {
                                $width = $image_real_width;
                            }
                            if ($image_real_height <= $height) {
                                $height = $image_real_height;
                            }

                            if ($image_real_width >= $image_real_height) {
                                $height = null;
                            } else {
                                $width = null;
                            }

                            $image_for_resize = Image::make($thumbs_path . $file[0]);
                            $img_sizes = getimagesize($thumbs_path . $file[0]);
                            $watermark_sizes = getimagesize(public_path('logo.png'));
                            $bottom = ((($img_sizes[1] / 2 - $watermark_sizes[1]) / 2) - $watermark_sizes[1] / 5);
                            $right = ((($img_sizes[0] / 2 - $watermark_sizes[0]) / 2));
                            $image_for_resize->insert(public_path('logo.png'), 'bottom-right', (int)$right, (int)$bottom);
                            $image_for_resize->resize($width, $height, function ($constraint) {
                                $constraint->aspectRatio();
                            })->save($thumbs_path . $file[0]);
                        }

                    }
                    if (File::exists($path . $file[0])) {
                        if (File::extension($path . $file[0]) == 'jpg' || File::extension($path . $file[0]) == 'jpeg' || File::extension($path . $file[0]) == 'png') {

                            $image_real_sizes = getimagesize($path . $file[0]);
                            $image_real_width = $image_real_sizes[0];
                            $image_real_height = $image_real_sizes[1];
                            $width = 800;
                            $height = 800;
                            if ($image_real_width <= $width) {
                                $width = $image_real_width;
                            }
                            if ($image_real_height <= $height) {
                                $height = $image_real_height;
                            }

                            if ($image_real_width >= $image_real_height) {
                                $height = null;
                            } else {
                                $width = null;
                            }

                            $image_for_resize = Image::make($path . $file[0]);

                            $img_sizes = getimagesize($path . $file[0]);
                            $watermark_sizes = getimagesize(public_path('logo.png'));
                            $bottom = ((($img_sizes[1] / 2 - $watermark_sizes[1]) / 2) - $watermark_sizes[1] / 5);
                            $right = ((($img_sizes[0] / 2 - $watermark_sizes[0]) / 2));
                            $image_for_resize->insert(public_path('logo.png'), 'bottom-right', (int)$right, (int)$bottom);
                            $image_for_resize->resize($width, $height, function ($constraint) {
                                $constraint->aspectRatio();
                            })->save($path . $file[0]);
                        }

                    }
                    if (File::exists($small . $file[0])) {
                        if (File::extension($small . $file[0]) == 'jpg' || File::extension($path . $file[0]) == 'jpeg' || File::extension($small . $file[0]) == 'png') {
                            $image_real_sizes = getimagesize($small . $file[0]);
                            $image_real_width = $image_real_sizes[0];
                            $image_real_height = $image_real_sizes[1];
                            $width = 800;
                            $height = 800;
                            if ($image_real_width <= $width) {
                                $width = $image_real_width;
                            }
                            if ($image_real_height <= $height) {
                                $height = $image_real_height;
                            }

                            if ($image_real_width >= $image_real_height) {
                                $height = null;
                            } else {
                                $width = null;
                            }

                            $image_for_resize = Image::make($small . $file[0]);

                            $img_sizes = getimagesize($small . $file[0]);
                            $watermark_sizes = getimagesize(public_path('logo.png'));
                            $bottom = ((($img_sizes[1] / 2 - $watermark_sizes[1]) / 2) - $watermark_sizes[1] / 5);
                            $right = ((($img_sizes[0] / 2 - $watermark_sizes[0]) / 2));
                            $image_for_resize->insert(public_path('logo.png'), 'bottom-right', (int)$right, (int)$bottom);
                            $image_for_resize->resize($width, $height, function ($constraint) {
                                $constraint->aspectRatio();
                            })->save($small . $file[0]);
                        }

                    }
                }
                $changed_count = $this->importImages($findParts, $files, $path, $thumbs_path, $small, $no_match_codes);
                clear_dir($path);
                clear_dir($thumbs_path);
            }

            return redirect()->back()->withInput(['changed_count' => $changed_count, 'count' => count($zipContent)]);
        }

        return view('site.pages.cabinet.company.general.images');
    }


    private function importImages($parts, $files, $path, $thumbs_path, $small, $no_match_codes)
    {
        $imagesPath = public_path('u/items/');
        $imagesThumbsPath = public_path('u/items/thumbs/');
        $imagesSmallPath = public_path('u/items/small/');
        $updateParts = [];
        foreach ($parts as $part) {
            if (array_key_exists($part->code, $files)) {
                $thisImages = $files[$part->code];
                $first = true;
                if ($part->image) {
                    File::delete($imagesPath . $part->image);
                    File::delete($imagesThumbsPath . $part->image);
                    File::delete($imagesSmallPath . $part->image);
                }
                foreach ($thisImages as $image) {
                    $ext = preg_replace('/^.*\.([^.]+)$/', '$1', $image);

                    $filename = file_name(18, $ext);
//                    while (file_exists($imagesPath.$filename) && file_exists($imagesThumbsPath.$filename));
                    rename($thumbs_path . $image, $imagesThumbsPath . $filename);
                    rename($path . $image, $imagesPath . $filename);
                    rename($small . $image, $imagesSmallPath . $filename);
                    $updateParts[] = [
                        'code' => $part->code,
                        'image' => $filename,
                    ];

                }
            }

        }
        $data = [];
        $data['no_match_codes'] = $no_match_codes;
        $data['changed_count'] = 0;
        $company_items = CompanyItems::where('user_id', auth()->user()->id)->pluck('item_id')->toArray();

        foreach ($updateParts as $part) {
            $item = Items::whereIn('id', $company_items)->where('code', $part['code'])->first();
            if (!empty($item)) {
                $data['changed_count']++;
                $item->image = $part['image'];
                $item->save();
            }

        }

        return $data;
    }

    public static function getIncrement()
    {
        $model = new Items();
        $database = $model->getConnection()->getDatabaseName();
        $table = $model->getTable();

        return DB::select("SELECT `AUTO_INCREMENT` as `increment` FROM  INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '$database' AND TABLE_NAME = '$table'")[0]->increment;
    }
}
