<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pessoa\StoreRequest;
use App\Http\Resources\PessoaResource;
use App\Models\Pessoa;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PessoasController extends Controller
{
    /**
     * Retorna uma lista de pessoas de acordo com um termo.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        if (! $request->input('t')) {
            return response()->json([
                'message' => 'Parâmetro "t" não informado.',
            ], 400);
        }

        return PessoaResource::collection(
            Pessoa::query()
                ->where('apelido', 'LIKE', "%{$request->input('t')}%")
                ->orWhere('nome', 'LIKE', "%{$request->input('t')}%")
                ->orWhereJsonContains('stack', $request->input('t'))
                ->get()
        )->response();
    }

    /**
     * Cria uma nova pessoa.
     *
     * @param StoreRequest $request
     * @return JsonResponse
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
     *
     * @param Pessoa $pessoa
     * @return JsonResponse
     */
    public function show(Pessoa $pessoa): JsonResponse
    {
        return PessoaResource::make($pessoa)->response();
    }

    /**
     * Retorna a quantidade de pessoas cadastradas.
     *
     * @return JsonResponse
     */
    public function count(): JsonResponse
    {
        return response()->json(['data' => Pessoa::query()->count()]);
    }
}
