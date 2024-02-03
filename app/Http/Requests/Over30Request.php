<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\Over30RankKind;
use App\Rules\Over30Season;

// 全結果表示の時のバリデーション

class Over30Request extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */

    //GETパラメータをバリデーションするために追加する。
    protected function prepareForValidation()
    {
        //getで取得したパラメータをmergeする。
        $this->merge([
            'rank_kind' => $this->route('rank_kind'),
            'season' => $this->route('season')
        ]);
    }

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
            "rank_kind"=>new Over30RankKind,
            "season"=>new Over30Season
        ];
    }

}
