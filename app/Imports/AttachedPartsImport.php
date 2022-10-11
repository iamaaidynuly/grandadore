<?php

namespace App\Imports;

use App\Models\Part;
use Illuminate\Support\Facades\DB;

class AttachedPartsImport extends AbstractImport
{
    protected $rules = [
        'part' => 'required|string',
        'attached_parts' => 'nullable|string',
    ];
    protected $names = [
        'part' => 'запчасть',
        'attached_parts' => 'прикрепленные запчасти',
    ];

    private $allParts = [];

    protected function filter($data)
    {
        if ($this->rows->where('part', $data['part'])->count()) return $this->skip('duplicate', ['name' => 'запчасть']);
        if (!in_array($data['part'], $this->allParts)) $this->allParts[] = $data['part'];
        $attached_parts = explode(',', $data['attached_parts']);
        $data['attached_parts'] = [];
        foreach ($attached_parts as $key => $attached_part) {
            $attached_part = trim($attached_part);
            if (!in_array($attached_part, $this->allParts)) $this->allParts[] = $attached_part;
            if (!in_array($attached_part, $data['attached_parts'])) $data['attached_parts'][] = $attached_part;
        }

        return $this->add($data);
    }

    protected function callback()
    {
        $final = [];
        $removeParts = [];
        $parts = Part::selectRaw('`id`, LOWER(`ref`) as `ref`')->whereIn('ref', $this->allParts)->orderBy('id', 'asc')->get();
        foreach ($this->rows as $row) {
            $findPart = $parts->where('ref', mb_strtolower($row['part']))->first();
            if (!$findPart) {
                $this->addError($row['_row'], 'not_found', ['name' => 'запчасть']);
                continue;
            }
            $thisParts = [];
            foreach ($row['attached_parts'] as $part) {
                if ($part === '' || $part === null) continue;
                $findAttachedPart = $parts->where('ref', mb_strtolower($part))->first();
                if (!$findAttachedPart) {
                    $this->addError($row['_row'], 'not_found', ['name' => 'прикрепленный запчасть "' . $part . '"']);
                    $thisParts = false;
                    break;
                }
                if ($findAttachedPart->id == $findPart->id) {
                    $this->addError($row['_row'], 'part_part');
                    $thisParts = false;
                    break;
                }
                $thisParts[] = [
                    'part_id' => $findPart->id,
                    'attached_part_id' => $findAttachedPart->id,
                ];
            }
            if ($thisParts === false) continue;
            $final = array_merge($final, $thisParts);
            $removeParts[] = $findPart->id;
        }
        if (count($final)) {
            $table_name = 'attached_parts';
            DB::table($table_name)->whereIn('part_id', $removeParts)->delete();
            DB::table($table_name)->insert($final);
        }
    }
}
