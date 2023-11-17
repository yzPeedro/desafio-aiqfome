<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PessoaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            $this->mergeWhen(! $request->isMethod('POST'), ['id' => $this->resource?->id]),
            'apelido' => $this->resource?->apelido,
            'nome' => $this->resource?->nome,
            'nascimento' => $this->resource?->nascimento,
            'stack' => $this->resource?->stack,
        ];
    }
}
