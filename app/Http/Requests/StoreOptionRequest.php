<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class StoreOptionRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('option_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'question_id' => [
                'required',
                'integer',
            ],
            'choice_text' => [
                'required',

            ],
            'correct_choice' => [
                'required',
                'integer',
            ],
            'points'      => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
        ];
    }
}
