<?php

namespace App\Http\Requests;

use App\Rules\OddNumber;

class StoreGameRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $fieldPropertyRule = ['required', 'numeric', 'integer', 'min:5', 'max:99', new OddNumber];

        return [
            'width' => $fieldPropertyRule,
            'height' => $fieldPropertyRule,
        ];
    }
}
