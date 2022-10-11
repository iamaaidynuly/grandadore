<?php

namespace App\Imports;

use App\Models\Analog;
use App\Models\Part;
use Illuminate\Support\Facades\DB;

class AnalogsImport extends AbstractImport
{
    protected $names = [
        'ref' => 'запчасть',
        'brand' => 'производитель',
        'analogs' => 'номера',
    ];

    protected $rules = [
        'ref' => 'required|string',
        'brand' => 'required|string',
        'analogs' => 'nullable|string',
    ];

    private $refs = [];

    protected function filter($data)
    {
        $data['ref'] = mb_strtolower($data['ref']);
        if (!in_array($data['ref'], $this->refs)) $this->refs[] = $data['ref'];
        $thisBrand = mb_strtolower($data['brand']);
        if ($this->rows->filter(function ($item) use ($thisBrand, $data) {
            return ($item['ref'] = $data['ref'] && mb_strtolower($item['brand']) == $thisBrand);
        })->count()) $this->skip(['duplicate_in_file']);
        if ($data['analogs']) $data['analogs'] = explode(',', $data['analogs']);
        else $data['analogs'] = [];

        return $this->add($data);
    }

    protected function callback()
    {
        $parts = Part::selectRaw('`id`, LOWER(`ref`) as `ref`')->whereIn('ref', $this->refs)->get();
        $sort = Analog::selectRaw('MAX(`sort`) as max_sort')->first()->max_sort;
        $removes = [];
        $inserts = [];
        foreach ($this->rows as $row) {
            $findPart = $parts->where('ref', $row['ref'])->first();
            if (!$findPart) {
                $this->addError($row['_row'], 'not_found', ['name' => 'запчасть']);
                continue;
            }
            $removes[] = [
                'part_id' => $findPart->id,
                'brand' => $row['brand'],
            ];
            foreach ($row['analogs'] as $analog) {
                $inserts[] = [
                    'part_id' => $findPart->id,
                    'brand' => $row['brand'],
                    'number' => $analog,
                    'sort' => $sort
                ];
            }
        }
        DB::transaction(function () use ($removes, $inserts) {
            if (count($removes)) {
                $query = Analog::query();
                foreach ($removes as $remove) {
                    $query->orWhere(function ($q) use ($remove) {
                        $q->where($remove);
                    });
                }
                $query->delete();
            }
            if (count($inserts)) Analog::insert($inserts);
        });
    }
}
