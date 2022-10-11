<?php

namespace App\Http\Controllers\Admin;

use App\Models\Page;
use App\Models\User;
use App\Services\Notify\Facades\Notify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PagesController extends BaseController
{
    private function content_pages()
    {
        return [
            'home' => route('admin.banners', ['page' => 'home']),
            'about' => route('admin.banners', ['page' => 'about']),
            'news' => route('admin.banners', ['page' => 'news']),
            'oferta' => route('admin.banners', ['page' => 'oferta']),
        ];
    }

    private function gallery_pages()
    {
        return [
            //'home'=>route('admin.gallery', ['gallery'=>'home']),
        ];
    }

    private function video_gallery_pages()
    {
        return [
//            'about'=>route('admin.video_gallery', ['gallery'=>'about']),
//            'news'=>route('admin.video_gallery', ['gallery'=>'news']),
//            'restaurant'=>route('admin.video_gallery', ['gallery'=>'restaurant']),

        ];
    }

    public function main()
    {
        $data = [];
        $data['content_pages'] = $this->content_pages();
        $data['gallery_pages'] = $this->gallery_pages();
        $data['video_gallery_pages'] = $this->video_gallery_pages();
        $data['title'] = 'Страницы';
        $data['pages'] = Page::adminList();

        return view('admin.pages.pages.main', $data);
    }

    public function addPage()
    {
        $data = [];
        $data['title'] = 'Добавление страницы';
        $data['back_url'] = route('admin.pages.main');
        $data['edit'] = false;
        $data['homepage'] = \PageManager::getHomePage();

        return view('admin.pages.pages.form', $data);
    }

    public function addPage_put(Request $request)
    {
        $validator = $this->validator($request);
        $validator['validator']->validate();
        if (Page::actionPage(null, $validator['inputs'])) {
            Notify::success('Страница добавлена.');

            return redirect(route('admin.pages.main'));
        } else {
            Notify::get('error_occurred');

            return redirect()->back()->withInput();
        }
    }

    public function sort()
    {
        return Page::sortable();
    }

    public function editPage($id)
    {
        $data = [];
        $data['page'] = Page::getPage($id);
        $data['back_url'] = route('admin.pages.main');
        $data['title'] = 'Редактирование страницы "' . $data['page']->a('title') . '"';
        $data['edit'] = true;
        $data['homepage'] = \PageManager::getHomePage();

        return view('admin.pages.pages.form', $data);
    }

    public function editPage_patch($id, Request $request)
    {
        $page = Page::getPage($id);
        $validator = $this->validator($request, $id, $page);
        $validator['validator']->validate();
        if (Page::actionPage($page, $validator['inputs'])) {
            Notify::success('Страница редактирована.');

            return redirect(route('admin.pages.main'));
        } else {
            Notify::get('error_occurred');

            return redirect()->back()->withInput();
        }
    }

    public function deletePage_delete(Request $request)
    { //Ajax

        $result = ['success' => false];
        $id = $request->input('page_id');
        if ($id && is_id($id)) {
            $page = Page::where(['id' => $id, 'static' => null])->first();
            if ($page && Page::deletePage($page)) $result['success'] = true;
        }

        return response()->json($result);
    }

    private function validator($request, $ignore = false, $page = null)
    {
        $inputs = $request->all();
        if ($page && $page->static == \PageManager::getHomePage()) {
            $inputs['url'] = $page->url;
        }
        if (!empty($inputs['url'])) $inputs['url'] = lower_case($inputs['url']);
        $inputs['generated_url'] = !empty($inputs['title'][$this->urlLang]) ? to_url($inputs['title'][$this->urlLang]) : null;
        $request->merge(['url' => $inputs['url']]);
        $unique = $ignore === false ? null : ',' . $ignore;
        $result = [];
        $rules = [
            'generated_url' => 'required_with:generate_url|string|nullable',
        ];
        if (empty($inputs['generate_url'])) {
            $rules['url'] = 'required|is_url|string|unique:pages,url' . $unique . '|min:3|nullable';
            if (!$page || $page->url !== $inputs['url']) {
                $rules['url'] .= '|not_in_routes';
            }
        }
        $result['validator'] = Validator::make($inputs, $rules, [
            'generated_url.required_with' => 'Введите название (' . $this->urlLang . ') чтобы сгенерировать URL.',
            'url.required' => 'Введите URL или подставьте галочку "сгенерировать автоматический".',
            'url.is_url' => 'Неправильный URL.',
            'url.unique' => 'URL уже используется.',
            'url.not_in_routes' => 'URL уже используется.',
            'url.min' => 'URL должен содержать мин. 3 символов.',
        ]);
        $result['inputs'] = $inputs;

        return $result;
    }
}
