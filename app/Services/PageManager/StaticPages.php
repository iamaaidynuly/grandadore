<?php

namespace App\Services\PageManager;

use App\Models\Page;
use App\Services\PageManager\Facades\PageManager;

trait StaticPages
{
    public function pageManager($url = null)
    {
        $defaultPage = PageManager::getHomePage();

        if ($url === null) {
            $default = true;

            $getPage = Page::where('static', $defaultPage)->first();
        } else {
            $getPage = Page::where(['url' => $url, 'active' => 1])->first();
        }
        if (!$getPage || (empty($default) && $getPage->static == $defaultPage)) abort(404);

        if (!is_null($url)) {
            $breadcrumbs = [
                [
                    'title' => $getPage->title,
                    'url' => url()->to($url)
                ]
            ];
            view()->share('breadcrumbs', $breadcrumbs);
        }


        if ($getPage->static) {
            $method_name = 'static_' . $getPage->static;
            if (!method_exists(get_class(), $method_name)) abort(404);

            return $this->{$method_name}($getPage);
        } else {
            return $this->dynamic_page($getPage);
        }
    }
}
