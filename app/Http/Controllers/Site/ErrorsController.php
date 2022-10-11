<?php

namespace App\Http\Controllers\Site;

class ErrorsController extends BaseController
{
    public function __construct()
    {
    }

    public function show($code, $setLocale = false)
    {
        if ($setLocale) set_locale();

        $this->view_share();

        $t = __('errors.' . $code);
        if (!is_array($t)) $t = [];
        $data = [
            'error' => [
                'code' => $code,
                'title' => $t['title'] ?? null,
                'message' => $t['message'] ?? null,
            ],
            'seo' => $this->staticSEO($t['title'] ?? null),
            'main_class' => ' error-page',
            'current_page' => 404
        ];

        return response()->view('site.pages.error', $data)->setStatusCode($code);
    }
}
