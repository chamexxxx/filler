<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class UpdateGameRequest extends BaseRequest
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
        return [
            'playerId' => 'required|numeric|integer|min:1|max:2',
            'color' => ['required', 'string', Rule::in(config('constants.colors'))]
        ];
    }
}
