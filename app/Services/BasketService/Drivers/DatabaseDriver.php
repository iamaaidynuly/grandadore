<?php


namespace App\Services\BasketService\Drivers;


use App\Models\Basket;
use App\ValueObjects\BasketItem;
use Exception;
use Illuminate\Support\Collection;

/**
 * Class DatabaseDriver
 * @package App\Services\BasketService\Drivers
 */
class DatabaseDriver implements BasketDriver
{
    /**
     * @return Collection
     */
    public function getItems(): Collection
    {
        $basketItems = Basket::with(['item', 'size', 'color'])->where('user_id', authUser()->id)->get();

        $responseData = [];

        foreach ($basketItems as $basketItem) {
            $responseData[] = new BasketItem($basketItem->items, $basketItem->count, $basketItem->size, $basketItem->color);
        }

        return collect($responseData);
    }

    /**
     * @param int $itemId
     * @param int $data
     * @return bool
     */
    public function add(int $itemId, array $data): bool
    {
        try {
            $pivotData = [
                'count' => $data['count'],
                'size_id' => $data['size'] ?? null,
                'color_id' => $data['color'] ?? null
            ];
            authUser()->basketItems()->attach($itemId, $pivotData);
        } catch (Exception $exception) {
            return false;
        }

        return true;
    }

    /**
     * @param int $itemId
     * @param $data
     * @return bool
     */
    public function update(int $itemId, $data): bool
    {
        return (bool)authUser()->basketItems()->updateExistingPivot($itemId, $data);
    }

    /**
     * @param int $itemId
     * @return bool
     */
    public function delete(int $itemId): bool
    {

        if (authUser()) {
            $item = authUser()->basketItems()->where('item_id', $itemId)->first();
        }

        return $item->pivot->delete();
    }


}
