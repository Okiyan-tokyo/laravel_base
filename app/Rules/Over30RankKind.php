<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

// 30位以下表示のテーブルの種類
class Over30RankKind implements Rule
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
        return preg_match("/^(part|full|withnum)$/u",$value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'テーブルの種類が違います';
    }
}
