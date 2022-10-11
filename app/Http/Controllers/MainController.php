<?php

namespace App\Http\Controllers;


class MainController extends Controller
{
    public function home()
    {
        return view('site.pages.home.index');
    }

    public function about()
    {
        return view('site.pages.about.index');
    }
}
