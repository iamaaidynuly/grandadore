<?php

namespace App\Http\Controllers\Admin;

use App\Models\News;
use App\Models\Page;
use App\Models\Rooms;
use App\Models\VideoGallery;
use App\Services\Notify\Facades\Notify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VideoGalleriesController extends BaseController
{
    //region Private
    private $data = [
        'title' => 'Галерея',
    ];
    private $gallery;
    private $key;

    private function verify($gallery, $key)
    {
        $this->gallery = $this->data['gallery'] = $gallery;
        $this->key = $this->data['key'] = $key;
        $method_name = 'gallery_' . $gallery;
        if (!method_exists($this, $method_name)) abort(404);
        $use_keys = $key === null ? false : true;
        $require_keys = (new \ReflectionMethod($this, $method_name))->getNumberOfRequiredParameters() == 0 ? false : true;
        if ($use_keys !== $require_keys) abort(404);
        if ($key) $this->{$method_name}($key);
        else $this->{$method_name}();
    }

    public function show($gallery, $key = null)
    {
        $this->verify($gallery, $key);
        $this->data['items'] = VideoGallery::adminList($gallery, $key);

        return view('admin.pages.video_gallery.main', $this->data);
    }

    public function add($gallery, $key = null)
    {
        $this->verify($gallery, $key);
        $this->data['title'] .= ' | добавление видео';
        $this->data['edit'] = false;
        $this->data['back_url'] = route('admin.video_gallery', ['gallery' => $gallery, 'id' => $key]);

        return view('admin.pages.video_gallery.form', $this->data);
    }

    public function add_put(Request $request, $gallery, $key = null)
    {
        $this->verify($gallery, $key);
        $inputs = $request->all();
        $this->validator($inputs)->validate();
        $inputs['gallery'] = $gallery;
        $inputs['key'] = $key;
        if (VideoGallery::action(null, $inputs)) {
            Notify::success('Видео успешно добавлено.');

            return redirect()->route('admin.video_gallery', ['gallery' => $gallery, 'id' => $key]);
        } else {
            Notify::get('error_occurred');

            return redirect()->back()->withInput();
        }
    }

    public function edit($id)
    {
        $item = $this->data['item'] = VideoGallery::getItem($id);
        $this->verify($item->gallery, $item->key);
        $this->data['title'] .= ' | редактирование видео';
        $this->data['edit'] = true;
        $this->data['back_url'] = route('admin.video_gallery', ['gallery' => $item->gallery, 'id' => $item->key]);

        return view('admin.pages.video_gallery.form', $this->data);
    }

    public function edit_patch(Request $request, $id)
    {
        $item = VideoGallery::getItem($id);
        $inputs = $request->all();
        $this->validator($inputs)->validate();
        if (VideoGallery::action($item, $inputs)) {
            Notify::success('Видео успешно редактировано.');

            return redirect()->route('admin.video_gallery.edit', ['id' => $item->id]);
        } else {
            Notify::get('error_occurred');

            return redirect()->back()->withInput();
        }
    }

    public function sort()
    {
        return VideoGallery::sortable();
    }

    public function delete(Request $request)
    {
        $result = ['success' => false];
        $id = $request->input('item_id');
        if ($id && is_id($id)) {
            $page = VideoGallery::where('id', $id)->first();
            if ($page && VideoGallery::deleteItem($page)) $result['success'] = true;
        }

        return response()->json($result);
    }

    private function validator($inputs)
    {
        return Validator::make($inputs, [
            'video' => 'required|string',
        ], [
            'video.required' => 'Введите ссылку видео.',
            'video.string' => 'Введите ссылку видео.'
        ]);
    }

    //endregion

    private function gallery_about($key)
    {
        dd(123);
        $item = Page::getPage($key);
        $this->data['title'] = 'Видеогалерея страницы "' . $item->a('title') . '"';
        $this->data['back_url'] = route('admin.pages.main');
    }

    private function gallery_pages($key)
    {
        $item = Page::getPage($key);
        $this->data['title'] = 'Видеогалерея страницы "' . $item->a('title') . '"';
        $this->data['back_url'] = route('admin.pages.main');
    }


    private function gallery_news_item($key)
    {
        $item = News::getItem($key);
        $this->data['title'] = 'Видеогалерея новости "' . $item->a('title') . '"';
        $this->data['back_url'] = route('admin.news.main');
    }

}
