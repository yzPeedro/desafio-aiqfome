<?php

namespace App\Http\Requests\Pessoa;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class StoreRequest extends FormRequest
{
    public const BAD_REQUEST_RULES = ['String', 'Array', 'Date'];

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
            'apelido' => ['required', 'string', 'unique:pessoas,apelido', 'max:32'],
            'nome' => ['required', 'string', 'max:100'],
            'nascimento' => ['required', 'date', 'date_format:Y-m-d'],
            'stack' => ['array'],
            'stack.*' => ['string', 'max:32'],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        collect($validator->failed())->each(function (array $error) use ($validator) {
            if (array_intersect(array_keys($error), self::BAD_REQUEST_RULES)) {
                throw (new ValidationException($validator))
                    ->errorBag($this->errorBag)
                    ->redirectTo($this->getRedirectUrl())
                    ->status(ResponseAlias::HTTP_BAD_REQUEST);
            }
        });

        throw (new ValidationException($validator))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl())
            ->status(ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
    }
}
