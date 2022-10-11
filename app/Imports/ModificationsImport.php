<?php

namespace App\Imports;

use App\Models\Generation;
use App\Models\Mark;
use App\Models\Model;
use App\Models\Modification;

class ModificationsImport extends AbstractImport
{
    protected $names = [
        'cid' => 'id для сайта',
        'mark' => 'марка',
        'model' => 'модель',
        'generation' => 'кузов',
        'years' => 'годы производства',
    ];

    protected $rules = [
        'cid' => 'required|integer|digits_between:1,255',
        'mark' => 'required|string|max:255',
        'model' => 'required|string|max:255',
        'years' => 'nullable|string|max:16',
        'generation' => 'nullable|string|max:255',
    ];


    private $marks = [];
    private $models = [];
    private $generations = [];
    private $hasNullGeneration = false;

    protected function filter($data)
    {
        if ($this->rows->where('cid', $data['cid'])->count()) return $this->skip('duplicate', ['name' => 'id']);
        $year = null;
        $year_to = null;
        if (!empty($data['years'])) {
            $years = explode('-', $data['years']);
            $year = (int)isset($years[0]) ? trim($years[0]) : 0;
            if (isset($years[1])) {
                if ($years[1]) {
                    $year_to = $years[1];
                } else {
                    $year_to = null;
                }
            } else $year_to = null;
//            $year_to = (int) isset($years[1])?trim($years[1]):$year;
            $years = get_range_data($year, $year_to);
            $year = $years[0];
            $year_to = $years[1];
        }
        $data['year'] = $year;
        $data['year_to'] = $year_to;
        unset($data['years']);
        $mark = mb_strtolower($data['mark']);
        $model = mb_strtolower($data['model']);
        $generation = mb_strtolower($data['generation']);
        if (!in_array($mark, $this->marks)) $this->marks[] = $mark;
        if (!in_array($model, $this->models)) $this->models[] = $model;
        if ($generation === '') {
            $this->hasNullGeneration = true;
            $data['generation'] = null;
        } elseif (!in_array($generation, $this->generations)) $this->generations[] = $generation;

        return $this->add($data);
    }

    protected function callback()
    {
        $final = [];
        $marks = Mark::selectRaw('`id`, LOWER(`name`) as `name`')->whereIn('name', $this->marks)->get();
        $markIncrement = Mark::getIncrement();
        $models = Model::selectRaw('`id`, `mark_id`, LOWER(`name`) as `name`')->whereIn('name', $this->models)->get();
        $modelIncrement = Model::getIncrement();
        $generationsQuery = Generation::selectRaw('`id`, `model_id`, LOWER(`name`) as `name`, `year`, `year_to`')->whereIn('name', $this->generations);
        if ($this->hasNullGeneration) $generationsQuery->orWhereNull('name');
        $generations = $generationsQuery->get();
        $generationIncrement = Generation::getIncrement();
        $marksInsert = [];
        $modelsInsert = [];
        $generationInsert = [];
        foreach ($this->rows as $row) {
            $mark = $marks->where('name', mb_strtolower($row['mark']))->first();
            if (!$mark) {
                $thisMarkId = $markIncrement++;
                $thisMarksInsert = [
                    'id' => $thisMarkId,
                    'name' => mb_strtolower($row['mark']),
                    'url' => to_url($row['mark'])
                ];
                $marks->push($thisMarksInsert);
                $thisMarksInsert['name'] = $row['mark'];
                $marksInsert[] = $thisMarksInsert;
            } else $thisMarkId = $mark['id'];
            $model = $models->where('name', mb_strtolower($row['model']))->where('mark_id', $thisMarkId)->first();
            if (!$model) {
                $thisModelId = $modelIncrement++;
                $thisModelsInsert = [
                    'id' => $thisModelId,
                    'name' => mb_strtolower($row['model']),
                    'mark_id' => $thisMarkId,
                ];
                $models->push($thisModelsInsert);
                $thisModelsInsert['name'] = $row['model'];
                $modelsInsert[] = $thisModelsInsert;
            } else $thisModelId = $model['id'];
            $generation = $generations->where('name', mb_strtolower($row['generation']))->where('year', $row['year'])->where('year_to', $row['year_to'])->where('model_id', $thisModelId)->first();
            if (!$generation) {
                $thisGenerationId = $generationIncrement++;
                $thisGenerationsInsert = [
                    'id' => $thisGenerationId,
                    'name' => mb_strtolower($row['generation']),
                    'model_id' => $thisModelId,
                    'year' => $row['year'],
                    'year_to' => $row['year_to'],
                ];
                $generations->push($thisGenerationsInsert);
                $thisGenerationsInsert['name'] = $row['generation'];
                $generationInsert[] = $thisGenerationsInsert;
            } else $thisGenerationId = $generation['id'];
            $final[] = [
                'cid' => $row['cid'],
                'generation_id' => $thisGenerationId,
            ];
        }
        if (count($marksInsert)) Mark::insert($marksInsert);
        if (count($modelsInsert)) Model::insert($modelsInsert);
        if (count($generationInsert)) Generation::insert($generationInsert);
        Modification::insertOrUpdate($final, ['generation_id']);
    }
}
