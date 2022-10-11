<?php
/**
 * Created by PhpStorm.
 * User: pogho
 * Date: 8/4/2019
 * Time: 7:23 PM
 */

namespace App\Traits;


use Carbon\Carbon;

trait InteractsWithCalendarDays
{
    /**
     * @param string $attribute
     * @param array|null $formats
     * @return string|null
     */
    public function calendar(string $attribute, array $formats = null)
    {
        $value = $this->getAttribute($attribute);

        if (is_null($formats)) {
            $formats = [
                'nextWeek' => 'L',
                'lastDay' => 'L',
                'lastWeek' => 'L',
            ];
        }

        if ($value) {
            return Carbon::make($value)->calendar(null, $formats);
        }

        return null;
    }
}
