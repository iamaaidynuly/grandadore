<?php

namespace App\Rules;

use App\Models\User;
use Illuminate\Contracts\Validation\Rule;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class FormattedUniquePhone implements Rule
{
    protected $allowedCodes = [
        '+77'
    ];

    protected $message;

    protected $except;

    public function __construct($except = null)
    {
        $this->except = $except;
    }

    /**
     * @param string $attribute
     * @param mixed $phone
     * @return bool
     */
    public function passes($attribute, $phone)
    {
        $number = (int)$phone;
        if($number != 0 && gettype($number) == 'integer'){
            $parsedPhone = preg_replace('/[^0-9]/', '', $phone);
        }else{
            $this->message = __('Пожалуйста, заполните эл. почту или телефонный номер.');
            return is_null($this->message);
        }

        $query = User::query();

        if ($this->except) {
            $query = $query->where('id', '!=', $this->except);
        }
      
        if ($query->where('phone', $parsedPhone)->exists()) {
            $this->message = __('Этот номер телефона уже зарегистрирован в системе.');
        }

        return is_null($this->message);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
