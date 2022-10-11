<?php


namespace App\ValueObjects;


use App\Models\ColorFilter;
use App\Models\Items;
use App\Models\ItemSizes;

class BasketItem extends ValueObject
{
    /**
     * @var Items
     */
    protected $item;

    /**
     * @var ItemSizes
     */
    protected $size;

     /**
     * @var ColorFilter
     */
    protected $color;

    /**
     * @var integer
     */
    protected $count;

    /**
     * BasketItem constructor.
     *
     * @param Items     $item
     * @param int       $count
     * @param ItemSizes $size
     */
    public function __construct(Items $item, int $count, ItemSizes $size = null , ColorFilter $color = null)
    {
        $this->setItem($item);
        $this->setCount($count);
        $this->setSize($size);
        $this->setColor($color);
    }

    /**
     * @return mixed
     */
    public function getPrice(bool $exchanged = false)
    {
        return $this->size ?
            ($exchanged ? $this->size->exchangedPrice : $this->size->price) :
            ($exchanged ? $this->item->exchangedPrice : $this->item->price);
    }

    /**
     * @return float|int
     */
    public function getSum(bool $exchanged = false)
    {
        return $this->count * $this->getDiscountedPrice($exchanged);
    }

    /**
     * @return mixed
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @return mixed
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param mixed $size
     */
    public function setSize($size)
    {
        $this->size = $size;
    }

    /**
     * @param mixed $size
     */
    public function setColor($color)
    {
        $this->color = $color;
    }

    /**
     * @return mixed
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @return integer
     */
    public function getItemId()
    {
        return $this->item->id;
    }

    /**
     * @return mixed
     */
    public function getItemDiscount()
    {
        return $this->item->delivery_price;
    }

    /**
     * @return int|mixed
     */
    public function getDiscountedPrice(bool $exchanged = false)
    {
        return $this->getPrice($exchanged) - ($this->getPrice($exchanged) * $this->getItemDiscount() / 100);
    }

    /**
     * @return mixed
     */
    public function getItemTitle()
    {
        return $this->item->title;
    }

    /**
     * @return mixed
     */
    public function getItemCode()
    {
        return $this->item->code;
    }

    /**
     * @param mixed $count
     */
    public function setCount($count)
    {
        $this->count = $count;
    }

    /**
     * @return mixed
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @param mixed $item
     */
    public function setItem($item) : void
    {
        $this->item = $item;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'itemId'   => $this->getItemId(),
            'title'    => $this->getItemTitle(),
            'discount' => $this->getItemDiscount(),
            'sizeName' => $this->size ? $this->size->name : null,
            'colorName' => $this->color ? $this->color->name : null,
            'sizeId'   => $this->size ? $this->size->id : null,
            'colorId'   => $this->color ? $this->color->id : null,
            'price'    => $this->getDiscountedPrice(),
            'sum'      => $this->getSum(),
            'count'    => $this->count
        ];
    }
}
