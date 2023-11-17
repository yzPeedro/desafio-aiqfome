<?php

namespace App\Http\Requests\Pessoa;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class IndexRequest extends FormRequest
{
    /**
     * Retorna se o usuário está autorizado a fazer a requisição.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Retorna as regras de validação para a requisição.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            't' => ['required', 'string', 'max:32']
        ];
    }

    /**
     * Reescreve o método de validação para retornar erro 400.
     *
     * @param Validator $validator
     * @return void
     * @throws ValidationException
     */
    protected function failedValidation(Validator $validator): void
    {
        throw (new ValidationException($validator))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl())
            ->status(ResponseAlias::HTTP_BAD_REQUEST);
    }
}
