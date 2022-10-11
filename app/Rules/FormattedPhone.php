<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class FormattedPhone implements Rule
{
    protected $allowedCodes = [
        '+77'
    ];

    protected $message;

    /**
     * @param string $attribute
     * @param mixed $phone
     * @return bool
     */
    public function passes($attribute, $phone)
    {
        $phoneParts = explode(' ', $phone);
        $parsedPhone = preg_replace('/[^0-9]/', '', $phone);

        if (!in_array($phoneParts[0], $this->allowedCodes)) {
            $this->message = __('Код страны не правильный (Разрешен: +77)');
        } elseif (strlen($parsedPhone) !== 11) {
            $this->message = __('Не правильный формат телефонного номера!');
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
