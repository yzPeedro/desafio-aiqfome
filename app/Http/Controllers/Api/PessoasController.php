<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pessoa\IndexRequest;
use App\Http\Requests\Pessoa\StoreRequest;
use App\Http\Resources\PessoaResource;
use App\Models\Pessoa;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class PessoasController extends Controller
{
    /**
     * Retorna uma lista de pessoas de acordo com um termo.
     */
    public function index(IndexRequest $request): JsonResponse
    {
        return PessoaResource::collection(
            Pessoa::query()
                ->where('apelido', 'LIKE', "%{$request->validated('t')}%")
                ->orWhere('nome', 'LIKE', "%{$request->validated('t')}%")
                ->orWhere('stack', 'LIKE', '%"'.$request->validated('t').'"%')
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
