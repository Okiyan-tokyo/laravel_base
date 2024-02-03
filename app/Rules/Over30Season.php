<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Archive;

class Over30Season implements Rule
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
        // archiveに入っているか？
        // allもOK
        return in_array($value,array_merge(["all"],Archive::groupBy("season")->pluck("season")->toArray()));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return '年度選択のエラーです';
    }
}
