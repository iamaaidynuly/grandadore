<?php

namespace App\Services\FileManager;

use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class FileManager
{
    public function uploadImage($key, $path, $sizes, $delete = false, $item = false)
    {
        if (is_string($key) && request()->hasFile($key)) {
            $image = request()->file($key);
        } else if (is_object($key)) {
            $image = $key;
        }
        if (!empty($image)) {
            if (in_array($image->extension(), ['jpeg', 'jpg', 'png', 'gif']) && $image->isValid()) {
                $ext = $image->extension();
                if ($ext == 'jpeg') $ext = 'jpg';
                $path = public_path($path);
                do {
                    $name = file_name(18, $ext);
                } while (file_exists($path . $name));
                $image_real_sizes = getimagesize($image->getRealPath());
                $image_real_width = $image_real_sizes[0];
                $image_real_height = $image_real_sizes[1];
                $new_sizes = [];
                $i = 0;
                foreach ($sizes as $size) {
                    if (!empty($size['width'])) {
                        $new_sizes[$i]['width'] = $size['width'];
                    }
                    if (!empty($size['height'])) {
                        $new_sizes[$i]['height'] = $size['height'];
                    }
                    if (!empty($size['aspect'])) {
                        $new_sizes[$i]['aspect'] = $size['aspect'];
                    }
                    if (!empty($size['method'])) {
                        $new_sizes[$i]['method'] = $size['method'];
                    }
                    if (!empty($size['dir'])) {
                        $new_sizes[$i]['dir'] = $size['dir'];
                    }
                    if (!empty($size['width']) && $image_real_width <= $size['width']) {
                        $new_sizes[$i]['width'] = $image_real_width;
                    }
                    if (!empty($size['height']) && $image_real_height <= $size['height']) {
                        $new_sizes[$i]['height'] = $image_real_height;
                    }
                    if ($image_real_width >= $image_real_height) {
                        $new_sizes[$i]['height'] = null;
                    } else {
                        $new_sizes[$i]['width'] = null;
                    }
                    $i++;
                }
                foreach ($new_sizes as $i => $size) {
                    $img = Image::make($image->getRealPath());
                    if ($item) {
                        $img_sizes = getimagesize($image->getRealPath());
                        $watermark_sizes = getimagesize(public_path('logo.png'));
                        $bottom = ((($img_sizes[1] / 2 - $watermark_sizes[1]) / 2) - $watermark_sizes[1] / 5);
                        $right = ((($img_sizes[0] / 2 - $watermark_sizes[0]) / 2));
                        $img->insert(public_path('logo.png'), 'bottom-right', (int)$right, (int)$bottom);
                    }
                    $canvasSize = $sizes[$i];
                    $canvas = Image::canvas($canvasSize['width'], $canvasSize['height'], null);
                    $dir = $path . ($size['dir'] ?? null);
                    if (!file_exists($dir)) mkdir($dir, 0775, true);
                    if (!empty($size['method']) && $size['method'] == 'original') $img->save($dir . $name);
                    else {
                        $img = $img->{$size['method'] ?? 'fit'}($size['width'], $size['height'], function ($constraint) use ($size) {
                            if (!empty($size['upsize'])) $constraint->upsize();
                            if (!empty($size['aspect'])) {
                                $constraint->aspectRatio();
                            }
                        });
                        $canvas->insert($img, 'center')->save(($dir . $name), 80);
                    }
                    if (!empty($delete) && empty($size['skip_delete'])) {
                        File::delete($dir . $delete);
                    }
                }

                return $name;
            } else return false;
        } else return null;
    }

    public function uploadOriginalImage($key, $path, $delete = false)
    {
        if (is_string($key) && request()->hasFile($key)) {
            $image = request()->file($key);
        } else if (is_object($key)) {
            $image = $key;
        }
        if (!empty($image)) {
            if (in_array($image->extension(), ['jpeg', 'jpg', 'png', 'gif', 'svg']) && $image->isValid()) {
                $ext = $image->extension();
                $path = public_path($path);
                if ($ext == 'jpeg') $ext = 'jpg';
                do {
                    $name = file_name(18, $ext);
                } while (file_exists($path . $name));

                $image->move($path, $name);

                if (!empty($delete)
                ) {
                    File::delete($path . $delete);
                }

                return $name;
            } else return false;
        } else return null;
    }

    public function uploadFile($key, $path, $delete = false)
    {
        if (is_string($key) && request()->hasFile($key)) {
            $image = request()->file($key);
        } else if (is_object($key)) {
            $image = $key;
        }
        if (!empty($image)) {
            $ext = $image->getClientOriginalExtension();
            $path = public_path($path);
            do {
                $name = file_name(18, $ext);
            } while (file_exists($path . $name));

            $image->move($path, $name);

            if (!empty($delete)
            ) {
                File::delete($path . $delete);
            }

            return $name;
        } else return null;
    }
}
