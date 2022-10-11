<?php


namespace App\Services\BasketService\Drivers;


use Illuminate\Support\Collection;

interface BasketDriver
{
    public function getItems(): Collection;

    public function add(int $itemId, array $data): bool;

    public function update(int $itemId, array $data): bool;

    public function delete(int $itemId): bool;
}
