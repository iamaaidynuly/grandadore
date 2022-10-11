<?php

namespace App\Imports;

use App\Models\Mark;
use App\Models\Model;
use Illuminate\Support\Facades\DB;

class ModelsImport extends AbstractImport
{
    protected $rules = [
        'id' => 'nullable|integer',
        'mark' => 'required|string|max:255',
        'model' => 'required|string|max:255',
    ];
    protected $names = [
        'id' => 'id',
        'mark' => 'марка',
        'model' => 'модель',
    ];

    private $marks = [];
    private $models = [];

    protected function filter($data)
    {
        if ($this->rows->filter(function ($item) use ($data) {
            return (mb_strtolower($item['mark']) == mb_strtolower($data['mark']) && mb_strtolower($item['model']) == mb_strtolower($data['model'])) || ($data['id'] && $data['id'] == $item['id']);
        })->count()) return $this->skip('duplicate_in_file');
        $lower_mark = mb_strtolower($data['mark']);
        if (!in_array($lower_mark, $this->marks)) $this->marks[] = $lower_mark;
        $lower_model = mb_strtolower($data['model']);
        if (!in_array($lower_model, $this->models)) $this->models[] = $lower_model;

        return $this->add($data);
    }

    protected function callback()
    {
        $file_ids = $this->rows->pluck('id')->filter()->toArray();
        $ids = Model::whereIn('id', $file_ids)->pluck('id')->toArray();
        $marks = Mark::selectRaw('`id`, LOWER(`name`) as `name`')->whereIn('name', $this->marks)->get();
        $models = Model::selectRaw('`id`, LOWER(`name`) as `name`, `mark_id`')->whereIn('name', $this->models)->whereNotIn('id', $ids)->get();
        $markIncrement = Mark::getIncrement();
        $inserts = [];
        $updates = [];
        $marksInsert = [];
        foreach ($this->rows as $row) {
            $this_mark_insert = false;
            if ($row['id']) {
                $this_id = (int)$row['id'];
                if (!in_array($this_id, $ids)) {
                    $this->addError($row['_row'], 'not_found', ['name' => 'модель']);
                    continue;
                }
                $edit = true;
            } else $edit = false;
            $find_mark = $marks->where('name', mb_strtolower($row['mark']))->first();
            if (!$find_mark) {
                $thisMarkId = $markIncrement;
                $this_mark_insert = [
                    'id' => $thisMarkId,
                    'name' => $row['mark'],
                    'url' => to_url($row['mark']),
                ];
            } else {
                $thisMarkId = $find_mark['id'];
                if ($models->filter(function ($item) use ($row, $thisMarkId) {
                    return ($item->name == mb_strtolower($row['model']) && $item['mark_id'] == $thisMarkId);
                })->count()) {
                    $this->addError($row['_row'], 'duplicate', ['name' => 'модель']);
                    continue;
                }
            }
            if ($edit) {
                $updates[] = [
                    'id' => $this_id,
                    'name' => $row['model'],
                    'mark_id' => $thisMarkId,
                ];
            } else {
                $inserts[] = [
                    'name' => $row['model'],
                    'mark_id' => $thisMarkId,
                ];
            }
            if ($this_mark_insert) {
                ++$markIncrement;
                $marksInsert[] = $this_mark_insert;
                $this_mark_insert['name'] = mb_strtolower($this_mark_insert['name']);
                $marks->push($this_mark_insert);
            }
        }
        DB::transaction(function () use ($marksInsert, $inserts, $updates) {
            if (count($marksInsert)) Mark::insert($marksInsert);
            if (count($inserts)) Model::insert($inserts);
            if (count($updates)) Model::insertOrUpdate($updates, ['mark_id', 'name']);
        });
    }
}
