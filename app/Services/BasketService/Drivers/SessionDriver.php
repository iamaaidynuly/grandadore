<?php


namespace App\Services\BasketService\Drivers;


use App\Models\Items;
use App\ValueObjects\BasketItem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

class SessionDriver implements BasketDriver
{
    protected $sessionKey = 'basketItems';

    public function getItems(): Collection
    {
        $sessionItems = $this->getSessionItems();
        $basketItems = collect([]);

        if (count($sessionItems)) {
            $items = Items::query()->whereIn('id', $sessionItems->pluck('itemId')->toArray())->get();

            foreach ($sessionItems as $sessionItem) {
                $item = $items->firstWhere('id', $sessionItem['itemId']);

                $basketItems->push(new BasketItem(
                    $item,
                    $sessionItem['count'],
                    !empty($sessionItem['size']) ? $item->sizes->firstWhere('id', $sessionItem['size']) : null
                ));
            }
        }

        return $basketItems;
    }

    protected function getSessionItems()
    {
        return collect(Session::get($this->sessionKey, []));
    }

    public function add(int $itemId, array $data): bool
    {
        $items = $this->getSessionItems();
        $data['itemId'] = $itemId;

        $items->push($data);
        Session::put($this->sessionKey, $items->values()->toArray());

        return true;
    }

    public function update(int $itemId, $data): bool
    {
        $items = $this->getSessionItems();

        foreach ($items as $index => $item) {
            if ($item['itemId'] == $itemId) {
                $items[$index] = array_merge($item, $data);
            }
        }

        Session::put($this->sessionKey, $items->values()->toArray());

        return true;
    }

    public function delete(int $itemId): bool
    {
        $items = $this->getSessionItems();

        $items = $items->filter(function ($item) use ($itemId) {
            return $item['itemId'] != $itemId;
        });

        Session::put($this->sessionKey, $items->values()->toArray());

        return true;
    }
}
