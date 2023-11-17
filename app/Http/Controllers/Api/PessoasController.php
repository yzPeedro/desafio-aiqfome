<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pessoa\StoreRequest;
use App\Http\Resources\PessoaResource;
use App\Models\Pessoa;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Throwable;

class PessoasController extends Controller
{
    /**
     * Retorna uma lista de pessoas de acordo com um termo.
     *
     * @throws Throwable
     */
    public function index(Request $request): JsonResponse
    {
        throw_if(! $request->input('t'),
            (new ValidationException(
                validator([], ['t' => 'required'], ['t' => 'O termo de busca é obrigatório.'])
            ))->status(400)
        );

        return PessoaResource::collection(
            Pessoa::query()
                ->where('apelido', 'LIKE', "%{$request->input('t')}%")
                ->orWhere('nome', 'LIKE', "%{$request->input('t')}%")
                ->orWhere('stack', 'LIKE', '%"'.$request->input('t').'"%')
                ->get()
        )->response();
    }

    /**
     * Cria uma nova pessoa.
     */
    public function store(StoreRequest $request): JsonResponse
    {
        $pessoa = Pessoa::query()->create(['id' => Str::uuid(), ...$request->validated()]);

        return PessoaResource::make($pessoa)
            ->response($request)
            ->header('Location', route('pessoas.show', ['pessoa' => $pessoa->getKey()]))
            ->setStatusCode(201);
    }

    /**
     * Lista dados de uma pessoa.
     */
    public function show(Pessoa $pessoa): JsonResponse
    {
        return PessoaResource::make($pessoa)->response();
    }

    /**
     * Retorna a quantidade de pessoas cadastradas.
     */
    public function count(): JsonResponse
    {
        return response()->json(['data' => Pessoa::query()->count()]);
    }
}
