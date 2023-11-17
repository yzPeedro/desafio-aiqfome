<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pessoa\StorePessoaRequest;
use App\Http\Resources\PessoaResource;
use App\Models\Pessoa;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PessoasController extends Controller
{
    public function index(Request $request)
    {
        if (! $request->input('t')) {
            return response()->json([
                'message' => 'Parâmetro "t" não informado.'
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

    public function store(StorePessoaRequest $request): JsonResponse
    {
        $pessoa = Pessoa::query()->create(['id' => Str::uuid(), ...$request->validated()]);

        return PessoaResource::make($pessoa)
            ->response($request)
            ->header('Location', route('pessoas.show', ['pessoa' => $pessoa->getKey()]))
            ->setStatusCode(201);
    }

    public function show(Pessoa $pessoa): JsonResponse
    {
        return PessoaResource::make($pessoa)->response();
    }
}
