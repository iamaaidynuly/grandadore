<?php

namespace App\Http\Controllers\Admin;

use App\Gateways\Kassa24;
use App\Models\BistriZakaz;
use App\Models\BistriZakazUser;
use App\Models\ColorFilter;
use App\Models\ItemOrder;
use App\Models\Items;
use App\Models\ItemSizes;
use App\Models\Order;
use App\Services\BasketService\BasketFactory;
use App\Services\Notify\Facades\Notify;
use App\Models\ZakazatZvonok;
use Illuminate\Http\Request;
use Snowfire\Beautymail\Beautymail;

class OrdersController extends BaseController
{
    public function newOrders()
    {
        $data = [
            'title' => 'Новые заказы',
        ];
        $data['status'] = Order::STATUS_NEW;
        $data['items'] = Order::getOrdersWithStatus(Order::STATUS_NEW);

        return view('admin.pages.orders.main', $data);
    }

    public function doneOrders()
    {
        $data = [
            'title' => 'Выполненные заказы',
        ];
        $data['items'] = Order::getOrdersWithStatus(Order::STATUS_DONE);
        $data['status'] = Order::STATUS_DONE;

        return view('admin.pages.orders.main', $data);
    }

    public function changeProcess(Request $request, $id)
    {
        $order = Order::getItem($id);
        if ($order->status != Order::STATUS_PENDING) return redirect()->back();
        $process = (int) $request->input('process');
        $paid = (int) $request->has('paid');
        if ($process < 0 || $process > 3) return redirect()->back();
        $order->process = $process;
        $order->paid = $paid;
        if ($process == 3 && $paid) {
            $order->status = Order::STATUS_DONE;
        }
        $order->save();
        Notify::get('changes_saved');

        return redirect()->back();
    }


    public function pendingOrders()
    {
        $data = [
            'title' => 'Невыполненные заказы',
        ];
        $data['items'] = Order::getOrdersWithStatus(Order::STATUS_PENDING);
        $data['status'] = Order::STATUS_PENDING;

        return view('admin.pages.orders.main', $data);
    }

    public function declinedOrders()
    {
        $data = [
            'title' => 'Откланенные заказы',
        ];
        $data['status'] = Order::STATUS_DECLINED;
        $data['items'] = Order::getOrdersWithStatus(Order::STATUS_DECLINED);

        return view('admin.pages.orders.main', $data);
    }

    public function view($id)
    {
        $data = [];
        $data['item'] = Order::where('id', $id)->with('items')->first();

        $data['color'] = ColorFilter::all();
        $data['size'] = ItemSizes::all();
        if (!empty($data['item'])) {
            $data['title'] = 'Заказ N'.$data['item']->id;
        }
        $data['process'] = Order::PROCESS;

        return view('admin.pages.orders.view', $data);
    }

    public function respond(Request $request, $id)
    {
        $order = Order::where('id', $id)->with('items', 'user')->firstOrFail();

        if (!$request->status) {
            $message = 'Заказ Отклонен';
            $order->status = -1;
            $order->save();
            Notify::success($message);

            return redirect()->back();
        }

        if ($order->payment_method == 'bank') {
            $order->status = 1;
            $order->save();

            Notify::success('Заказ принят');
        } else {
            $order->status = 1;
            $order->save();
        }


        /*        foreach ($order->items as $order_part) {
                    $new_count = $order_part->count - $order_part->pivot->count;
                    if ($new_count < 0) $new_count = 0;
                    $order_part->count = $new_count;
                    $order_part->save();
                }*/

        /*   if (!empty($order->user) && !empty($order->user->email)) {
               $user = $order->user;

               try {
                   app()->make(Beautymail::class)->send('site.notifications.order_sent', [
                       'url' => route('cabinet.profile.orders.active'),
                       'order' => $order
                   ], function ($message) use ($user) {
                       $message->from(env('MAIL_FROM_ADDRESS'))
                           ->to($user->email, $user->name)
                           ->subject('Ваш заказ на сайте Dev.loc одобрен');
                   });
               } catch (\Exception $exception) {}
           }*/


        return redirect()->back();
    }


    public function delete(Request $request)
    {
        $result = ['success' => false];
        if (isset($request->unique)) {
            $bistriZakazAll = BistriZakaz::where('user_id', $request->user_id)->get();
            foreach ($bistriZakazAll as $bistriZakaz) {
                $bistriZakaz->delete();
            }
            BistriZakazUser::find($request->user_id)->delete();
            $result = ['success' => true];

        } else {


            $id = $request->input('item_id');
            if ($id && is_id($id)) {
                $item = Order::find($id);
                if ($item && $item->status != '0' && $item->delete()) $result['success'] = true;
            }

        }
        return response()->json($result);
    }

    public function clear(Request $request)
    {
        $status = $request->input('status');
        if (!in_array($status, [Order::DECLINED, Order::ACCEPTED])) abort(404);
        Order::clear($status);
        Notify::success('История очищена');

        return redirect()->back();
    }


    /** Bistri zakaz */

    public function bistriZakaz()
    {
        $items = BistriZakazUser::with(['items', 'itemsNew'])->get();
        //dd($items[0]->itemsNew()->sum('price'));


        $data = [
            'title' => '"Быстрый заказ" ',
        ];

        $data['items'] = $items;

        foreach ($items as $item) {
            foreach ($item->items as $pivot) {
                //dd($pivot->item_id);

                // $item = Items::query()->Where('id',$item->items)->orWhere('id',)
            }
        }

        return view('admin.pages.orders.main', $data);

    }

    /** for fast order */
    public function viewOrder($id)
    {

        $data = [];
        $data['item'] = BistriZakazUser::where('id', $id)->with(['itemsNew', 'items'])->first();

        // dd($data['item']);
        return view('admin.pages.orders.viewFastOrder', $data);

    }

    public function ajaxChangeStatus(Request $request)
    {
        $bistriZakaz = BistriZakazUser::where('id', $request->id)->first();

        if ($request->status == 0) {
            $bistriZakaz->status = 0;
        } else {
            $bistriZakaz->status = $request->status;
        }

        $bistriZakaz->update();

    }

    public function call()
    {
        $all = ZakazatZvonok::allZakazat();

        return view('admin.pages.orders.call', compact('all'));
    }

    public function dropZakazatZvonok(Request $request)
    {
        ZakazatZvonok::find($request->id)->delete();

    }

}
