<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class StoreQuestionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */

    public function authorize()
    {
        abort_if(Gate::denies('question_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        echo "rule";
        return [
            'category_id'   => [
                'required',
                'integer',
            ],
            'question_text' => [
                'required',
            ],
             'description' =>[
                 'mimes:png,jpeg,jpg'
             ]
        ];
    }
}
