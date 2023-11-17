<?php

namespace App\Http\Requests\Pessoa;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class StoreRequest extends FormRequest
{
    /**
     * Regras de validação que retornam o código de resposta 400 (erros de sintaxe).
     *
     * @var array<string>
     */
    public const BAD_REQUEST_RULES = ['String', 'Array', 'Date'];

    /**
     * Retorna se o usuário está autorizado a fazer a requisição.
     *
     * @return bool
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
            'apelido' => ['required', 'string', 'unique:pessoas,apelido', 'max:32'],
            'nome' => ['required', 'string', 'max:100'],
            'nascimento' => ['required', 'date', 'date_format:Y-m-d'],
            'stack' => ['array'],
            'stack.*' => ['string', 'max:32'],
        ];
    }

    /**
     * Reescreve o método de validação para retornar
     * a mensagem de erro com o código de resposta 400 caso seja um erro de sintaxe ou 422
     * caso seja uma requisição inválida.
     *
     * @param Validator $validator
     * @return void
     * @throws ValidationException
     */
    protected function failedValidation(Validator $validator): void
    {
        if (
            array_intersect(array_keys(Arr::collapse($validator->failed())), self::BAD_REQUEST_RULES)
        ) {
            throw (new ValidationException($validator))
                ->errorBag($this->errorBag)
                ->redirectTo($this->getRedirectUrl())
                ->status(ResponseAlias::HTTP_BAD_REQUEST);
        }

        throw (new ValidationException($validator))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl())
            ->status(ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
    }
}
