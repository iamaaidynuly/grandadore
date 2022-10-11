<?php

namespace App\Http\Controllers\Admin;

use App\Models\UserMessage;
use Illuminate\Http\Request;

class UserMessagesController extends BaseController
{

    public function main($id = null)
    {
        if(isset(request()->messages)){
          $active0=UserMessage::where('active',0)->get();

          UserMessage::active($active0);
        }
        $data = ['title' => 'Письмы от пользователей'];
        $data['items'] = UserMessage::adminList()->sortBy('created_at')->reverse();

        return view('admin.pages.user_messages.main', $data);
    }

    public function view($id)
    {

        $data = [];
        $data['item'] = UserMessage::getItem($id);

        $data['title'] = 'Просмотр Письма';
        $data['back_url'] = route('admin.user_messages.main');
        $data['edit'] = true;

        return view('admin.pages.user_messages.form', $data);
    }

    public function delete(Request $request)
    {
        $result = ['success' => false];
        $id = $request->input('item_id');
        $item = UserMessage::where('id', $id)->first();
        if ($item && UserMessage::deleteItem($item)) {
            $result['success'] = true;
        }

        return response()->json($result);
    }


}
