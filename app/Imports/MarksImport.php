<?php

namespace App\Imports;

use App\Models\Mark;

class MarksImport extends AbstractImport
{
    protected $rules = [
        'id' => 'nullable|integer',
        'name' => 'required|string|max:255',
    ];
    protected $names = [
        'id' => 'id',
        'name' => 'марка',
    ];

    protected function filter($data)
    {
        if ($data['id'] && $this->rows->where('id', $data['id'])->count()) return $this->skip('duplicate', ['name' => 'id']);
        if ($this->rows->filter(function ($item) use ($data) {
            return mb_strtolower($data['name']) == mb_strtolower($item['name']);
        })->count()) return $this->skip('duplicate', ['name' => 'марка']);

        return $this->add($data);
    }

    protected function callback()
    {
        $ids = $this->rows->pluck('id')->filter();
        $names = $this->rows->pluck('name');
        $result_ids = Mark::whereIn('id', $ids)->pluck('id')->toArray();
        $result_names = array_map('mb_strtolower', Mark::whereIn('name', $names)->whereNotIn('id', $ids)->pluck('name')->toArray());
        $inserts = [];
        $updates = [];
        foreach ($this->rows as $row) {
            $lower_name = mb_strtolower($row['name']);
            if (in_array($lower_name, $result_names)) {
                $this->addError($row['_row'], 'duplicate', ['name' => 'марка']);
                continue;
            }
            $row['id'] = (int)$row['id'];
            if ($row['id']) {
                if (!in_array($row['id'], $result_ids)) {
                    $this->addError($row['_row'], 'not_found', ['name' => 'id']);
                    continue;
                }
                $updates[] = [
                    'id' => $row['id'],
                    'name' => $row['name'],
                    'url' => to_url($row['name']),
                ];
            } else {
                $inserts[] = [
                    'name' => $row['name'],
                    'url' => to_url($row['name']),
                ];
            }
            $result_names[] = $lower_name;
        }
        if (count($updates)) Mark::insertOrUpdate($updates, ['name', 'url']);
        if (count($inserts)) Mark::insert($inserts);
    }
}
