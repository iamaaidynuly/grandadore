<?php

namespace App\Services\Banners;

class BannerModel implements \ArrayAccess, \Countable, \IteratorAggregate
{
    private $params;

    public function __construct($params)
    {
        $this->params = $params;
    }

    public function offsetExists($offset)
    {
        return isset($this->params[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->params[$offset];
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->params);
    }

    public function offsetSet($offset, $value)
    {
        return false;
    }

    public function offsetUnset($offset)
    {
        return false;
    }

    public function count()
    {
        return count($this->params);
    }

    public function flip()
    {
        $result = [];
        $keys = $this->params[0]->getKeys();
        foreach ($this->params as $param) {
            foreach ($keys as $key) {
                $value = $param->{$key};
                if (!empty($value)) $result[$key][] = $value;
            }
        }

        return $result;
    }

    public function __get($key)
    {
        return $this->params[0]->$key;
    }

    public function __call($method, $expression)
    {
        return $this->params[0]->$method(...$expression);
    }
}

