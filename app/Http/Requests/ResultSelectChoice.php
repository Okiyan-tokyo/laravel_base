<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ResultSelectRule;

// 結果表示ページの年度選択のバリデーション

class ResultSelectChoice extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "record_year_select"=>[
                "required",
                new ResultSelectRule
            ]
        ];
    }

    public function messages()
    {
        return [
            "record_year_select.required"=>"年度情報が入力されていません"
            // ルールのメッセージは既に定義
        ];
    }
}
