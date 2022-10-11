<?php

namespace App\Imports;

use App\Models\Engine;
use App\Models\Mark;
use Illuminate\Support\Facades\DB;

class EnginesImport extends AbstractImport
{
    protected $rules = [
        'number' => 'required|integer',
        'name' => 'required|string|max:255',
    ];

    protected $names = [
        'number' => '№',
        'marks' => 'марка',
        'name' => 'двигатель',
        'years' => 'годы производства'
    ];

    private $marks = [];

    protected function filter($data)
    {
        if ($this->rows->filter(function ($item) use ($data) {
            return (mb_strtolower($item['number']) == mb_strtolower($data['number'])) || (mb_strtolower($item['name']) == mb_strtolower($data['name']));
        })->count()) return $this->skip('duplicate_in_file');
        $year = null;
        $year_to = null;
        if (!empty($data['years'])) {
            $years = explode('-', $data['years']);
            $year = (int)isset($years[0]) ? trim($years[0]) : 0;
            $year_to = (int)isset($years[1]) ? trim($years[1]) : 0;
            $years = get_range_data($year, $year_to);
            $year = $years[0];
            $year_to = $years[1];
        }
        $data['year'] = $year;
        $data['year_to'] = $year_to;
        unset($data['years']);
        $marks = explode(',', $data['marks']);
        $newMarks = [];
        foreach ($marks as $mark) {
            $mark = trim($mark);
            if (!in_array($mark, $this->marks)) $this->marks[] = mb_strtolower($mark);
            $newMarks[] = $mark;
        }
        $data['marks'] = $newMarks;
        $data['number'] = (int)$data['number'];

        return $this->add($data);
    }

    protected function callback()
    {
        $numbers = $this->rows->pluck('number');
        $names = $this->rows->pluck('names');
        $marks = $this->marks;
        $newMarks = [];
        $markIncrement = Mark::getIncrement();
        $markMaxCid = ((int)Mark::selectRaw('MAX(`cid`) as cid')->first()->cid) + 1;
        $result_numbers = Engine::select('id', 'number')->whereIn('number', $numbers)->get();
        $result_names = Engine::selectRaw('LOWER(`name`) as `lower_name`')->whereIn('name', $names)->whereNotIn('number', $numbers)->get()->pluck('lower_name');
        $result_marks = Mark::selectRaw('`id`, LOWER(`name`) as `name`')->whereIn('name', $marks)->get();
        $increment = Engine::getIncrement();
        $insert_marks = [];
        $final = [];
        $delete_marks = [];
        foreach ($this->rows as $row) {
            if ($result_names->where('name', $row['name'])->first()) {
                $this->addError($row['_row'], 'duplicate', ['name' => 'Двигатель']);
                continue;
            }
            $find = $result_numbers->where('number', $row['number'])->first();
            if ($find) {
                $is_increment = false;
                $id = $find->id;
            } else {
                $is_increment = true;
                $id = $increment;
            }
            $this_insert_marks = [];
            $attachingMarks = [];
            foreach ($row['marks'] as $mark) {
                $this_mark = $result_marks->where('name', mb_strtolower($mark))->first();
                if (!$this_mark) {
                    $thisMarkId = $markIncrement++;
                    $thisMarksInsert = [
                        'id' => $thisMarkId,
                        'cid' => $markMaxCid++,
                        'name' => mb_strtolower($mark),
                        'url' => to_url($mark)
                    ];
                    $result_marks->push($thisMarksInsert);
                    $thisMarksInsert['name'] = $mark;
                    $newMarks[] = $thisMarksInsert;
                } else $thisMarkId = $this_mark['id'];
                if (!in_array($thisMarkId, $attachingMarks)) {
                    $attachingMarks[] = $thisMarkId;
                    $this_insert_marks[] = [
                        'engine_id' => $id,
                        'mark_id' => $thisMarkId
                    ];
                }
            }
            if ($is_increment) {
                ++$increment;
            } else {
                $delete_marks[] = $id;
            }
            $insert_marks = array_merge($insert_marks, $this_insert_marks);
            $final[] = [
                'id' => $id,
                'number' => $row['number'],
                'year' => $row['year'],
                'year_to' => $row['year_to'],
                'name' => $row['name'],
            ];
        }
        if (count($newMarks)) Mark::insert($newMarks);
        if (count($final)) Engine::insertOrUpdate($final, ['number', 'year', 'year_to', 'name']);
        if (count($delete_marks)) DB::table('engine_mark')->whereIn('engine_id', $delete_marks)->delete();
        if (count($insert_marks)) DB::table('engine_mark')->insert($insert_marks);
    }

}
