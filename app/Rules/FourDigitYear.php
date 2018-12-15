<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class FourDigitYear implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        //
        list($year, $month, $day) = explode('-', $value);

        return (strlen($year) != 4) ? false : true;

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The date is an incorrect format.';
    }
}
