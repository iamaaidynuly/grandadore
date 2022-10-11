<?php

namespace App\Http\Traits;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

trait InsertOrUpdate
{
    public static function insertOrUpdate($values, $duplicates)
    {
        $duplicatesRaw = '';
        foreach ($duplicates as $duplicate) {
            if ($duplicatesRaw) $duplicatesRaw .= ', ';
            $duplicatesRaw .= "`{$duplicate}`=VALUES(`{$duplicate}`)";
        }
        $builder = (new self)->getQuery();
        $query = $builder->grammar->compileInsert($builder, $values) . ' ON DUPLICATE KEY UPDATE ' . $duplicatesRaw;
        $bindings = Arr::flatten($values, 1);
        DB::insert($query, $bindings);

        return true;
    }
}
