<?php

namespace App\Http\Controllers\Admin;

use App\Models\Banner;
use App\Services\Notify\Facades\Notify;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;

class BannersController extends BaseController
{
    //region Private
    private $data = [];
    private $notify = false;
    private $page;

    private function render()
    {
        $this->data['banners'] = Banner::getBanners($this->page);
        //dd($this->data['banners']);
        if (Request::getMethod() == 'POST') {
            return $this->post();
        }

        return view('admin.pages.banners.' . $this->page, $this->data);
    }

    private function post()
    {
        $request = Request::all();
        foreach ($this->data['params'] as $key => $params) {
            if (array_key_exists($key, $request)) {
                $inputs = $request[$key];
                $count = $params['count'] ?? 1;
                for ($i = 0; $i < $count; $i++) {
                    $this->updateData($params['params'], $inputs[$i] ?? null, $key, $i);
                }
            }
        }
        if ($this->notify) {
            Cache::forget(Banner::cacheKey($this->page));
            Notify::get('changes_saved');
        }

        return redirect()->back();

    }

    private function updateData($params, $inputs, $key, $i)
    {
        if (arraySize($inputs)) {
            $data = [];
            foreach ($params as $index => $param) {
                if (is_array($param)) {
                    $type = $param['type'];
                    unset($param['type']);
                    $settings = $param;
                } else {
                    $type = $param;
                    $settings = [];
                }
                $banner = $this->data['banners'][$key][$i]['data'][$index] ?? null;
                $data[$index] = $this->updateParam($type, $settings, $inputs[$index] ?? null, $banner);
            }
            if (arraySize($data)) {
                $id = $this->data['banners'][$key][$i]['id'] ?? false;
                if (Banner::updateBanner($this->page, $key, $data, $id)) $this->notify = true;
            }
        }

        return true;
    }

    private function updateParam($type, $settings, $input, $banner)
    {
        switch ($type) {
            case 'image':
                return $this->typeImage($settings, $input, $banner);
                break;
            case 'file':
                return $this->typeFile($settings, $input, $banner);
                break;
            case 'labelauty':
                return $this->typeCheckbox($input);
                break;
            default:
                return $input;
        }
    }

    private function typeImage($settings, $input, $banner)
    {
        if (empty($settings['original_file'])) {
            $resize = [];
            if (array_key_exists('resize', $settings)) {
                $resize[] = [
                    'method' => $settings['resize'][0],
                    'width' => $settings['resize'][1],
                    'height' => $settings['resize'][2],
                    'upsize' => empty($settings['resize'][3]) ? false : true,
                ];
            } else $resize[] = ['method' => 'original'];
            if ($input && $input->isFile() &&
                $image = upload_image($input, 'u/banners/', $resize, !empty($banner) ? $banner : false)

            ) return $image;
        } else {
            if ($input && $input->isFile() && $image = upload_original_image($input, 'u/banners/', !empty($banner) ? $banner : false)) return $image;
        }

        return $banner;
    }

    private function typeFile($settings, $input, $banner)
    {
        if ($input && $input->isFile() &&
            $file = upload_file($input, 'u/banners/', !empty($banner) ? $banner : false)

        ) return $file;

        return $banner;
    }

    private function typeCheckbox($input)
    {
        return $input ? true : false;
    }

    public function fixBanners()
    {
        Banner::fixBanners($this->settings);
    }

    public function renderPage($page)
    {

        if (!array_key_exists($page, $this->settings)) abort(404);
        $this->page = $page;
        $this->data['params'] = $this->settings[$page];

        return $this->render();
    }

    public function getSettings()
    {
        return $this->settings;
    }

    //endregion

    private $settings = [
        'home' => [
            'main_banner' => [
                'params' => [
                    'title' => 'title',
                    'content' => 'text',
                ]
            ],

        ],
        'oferta' => [
            'content' => [
                'params' => [
                    'title' => 'title',
                    'content' => 'text',
                    'image' => [
                        'type' => 'image',
                        'resize' => ['resize', 1920, null, true],
                        'hint' => false,
                    ],
                ]
            ]
        ],
        'about' => [
            'content' => [
                'params' => [
                    'title' => 'title',
                    'content' => 'text',
                    'image' => [
                        'type' => 'image',
                        'resize' => ['resize', 1440, 350, true],
                        'hint' => false,
                    ],
                ]
            ],
        ],
        'home_big_image_banners' => [
            'content' => [
                'params' => [
                    'top' => [
                        'type' => 'image',
                        'resize' => ['resize', 1410, 450, true],
                        'hint' => false,
                    ],
                    'top-link' => 'input',

                    'bottom' => [
                        'type' => 'image',
                        'resize' => ['resize', 1410, 450, true],
                        'hint' => false,
                    ],
                    'bottom-link' => 'input',
                    'left' => [
                        'type' => 'image',
                        'resize' => ['resize', 700, 300, true],
                        'hint' => false,
                    ],
                    'left_link' => 'input',
                    'right' => [
                        'type' => 'image',
                        'resize' => ['resize', 700, 300, true],
                        'hint' => false,
                    ],
                    'right_link' => 'input',
                ]
            ],
            'home' => [
                'params' => [
                    'title' => 'title',
                    'desc' => 'textarea',
                ]
            ],
        ],
        'home_small_image_banners' => [
            'content' => [
                'params' => [
                    'left' => [
                        'type' => 'image',
                        'resize' => ['resize', 580, 260, true],
                        'hint' => false,
                    ],
                    'right' => [
                        'type' => 'image',
                        'resize' => ['resize', 830, 260, true],
                        'hint' => false,
                    ],


                ]
            ],
            'home' => [
                'params' => [
                    'title' => 'title',
                    'desc' => 'textarea',
                ]
            ],
        ],
        'contact' => [
            'content' => [
                'params' => [
                    'title' => 'title',
                    'text' => 'text',
                    'first' => 'title',
                    'second' => 'title',
                    'third' => 'title',
                    'button' => 'title',
                    'contact_title' => 'title'
                ]
            ]
        ],
        'news' => [
            'content' => [
                'params' => [
                    'title' => 'title',
                    'title1' => 'title',
                    'image' => [
                        'type' => 'image',
                        'resize' => ['resize', 1920, 235, true],
                        'hint' => false,
                    ],
                ]
            ]
        ],
        'info' => [
            'seo' => [
                'params' => [
                    'title_suffix' => 'title',
                ]
            ],
            'contacts' => [
                'count' => 4,
                'params' => [
                    'phone' => 'input',
                    'email' => 'input',
                ]
            ],
            'address' => [
                'params' => [
                    'text' => 'title',
                ]
            ],
            'payments' => [
                'count' => 5,
                'params' => [
                    'image' => [
                        'type' => 'image',
                        'original_file' => 'true'
                    ],
                    'title' => 'input',
                    'active' => 'labelauty'
                ]
            ],
            'data' => [
                'params' => [
                    'header_logo' => [
                        'type' => 'image',
                        'original_file ' => true,
                    ],
                    'menu_logo' => [
                        'type' => 'image',
                        'original_file ' => true,
                    ],
                    'iframe' => 'input',
                    'contact_email' => 'input',
                    'min_sum' => [
                        'type' => 'number',
                        'min' => '0',
                        'max' => '99999',
                    ],
                    'product_image' => [
                        'type' => 'image',
                        'resize' => ['fit', 512, 288, true]
                    ],
                    'options_selected' => 'labelauty',
                ]
            ],
            'socials' => [
                'count' => 10,
                'params' => [
                    'icon' => [
                        'type' => 'image',
                        'original_file' => 'true'
                    ],
                    'title' => 'input',
                    'url' => 'input',
                ]
            ],
            'rates' => [
                'params' => [
                    'ruble' => [
                        'type' => 'input'
                    ],
                ]
            ]
        ],
    ];
}
