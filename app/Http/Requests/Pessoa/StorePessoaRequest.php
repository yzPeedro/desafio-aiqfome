<?php

namespace App\Http\Requests\Pessoa;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePessoaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'apelido' => ['required', 'unique:pessoas,apelido', 'max:32'],
            'nome' => ['required', 'max:100'],
            'nascimento' => ['required', 'date', 'date_format:Y-m-d'],
            'stack' => ['array'],
            'stack.*' => ['string', 'max:32']
        ];
    }
}
