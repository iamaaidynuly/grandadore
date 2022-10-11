<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class OrderExport implements FromCollection, ShouldAutoSize
{

    /**
     * @return \Illuminate\Support\Collection
     */
    protected $id;
    protected $count = 0;

    public function __construct($id)
    {
        $this->id = $id;

    }

    public function collection()
    {
        $header = [
            'ID',
            "Пользователь",
            "ФИО",
            "Сумма",
            "Статус",
            "Метод доставки",
            "Метод оплаты",
            "Статус оплаты",
            'Артикул',
            'Название',
            'Количество',
            "Дата",


        ];
        $item = Order::where('id', $this->id)->with(['parts', 'user'])->first();
        $collection = [];
        foreach ($item->order_parts as $part) {
            array_push($collection, Order::where('id', $this->id)->with(['parts', 'user'])->get()->map(function ($item) use ($part) {
                return [
                    !$this->count ? $item->id : '-',
                    (!$this->count && $item->user) ? $item->user->email : '-',
                    !$this->count ? $item->name : '-',
                    !$this->count ? $item->total : '-',
                    !$this->count ? $item->status_order_export : '-',
                    !$this->count ? $item->delivery_method_name : '-',
                    !$this->count ? $item->payment_method_name : '-',
                    !$this->count ? $item->paid ? 'оплачен' : (($item->payment_method == 'bank' && $item->paid_request) ? 'ожидание подверждения' : 'не оплачен') : '-',
                    $part->code,
                    $part->name,
                    $part->count,
                    !$this->count ? $item->created_at->format('d.m.Y H:i') : '-',

                ];
            }));
            $this->count = 1;
        }

        return collect($collection)->prepend($header);
    }
}
