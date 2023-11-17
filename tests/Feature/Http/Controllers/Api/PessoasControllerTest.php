<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Pessoa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PessoasControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testSistemaNaoDeveCadastrarPessoaComParametrosInvalidos()
    {
        $this->post(route('pessoas.store'), [
            'nome' => 'teste',
        ])->assertStatus(422)
            ->assertJsonValidationErrors([
                'apelido',
                'nascimento',
            ]);
    }

    public function testSistemaDeveRetornarBadRequestQuandoOsParametrosForemInvalidos()
    {
        $this->post(route('pessoas.store'), [
            'apelido' => 'apelido',
            'nome' => 1,
            'nascimento' => '2021-01-01',
            'stack' => ['php', 'laravel'],
        ])
            ->assertStatus(400)
            ->assertJsonValidationErrors([
                'nome',
            ]);
    }

    public function testSistemaDeveRetornarBadRequestQuandoStackTiverParametrosInvalidos()
    {
        $this->post(route('pessoas.store'), [
            'apelido' => 'apelido',
            'nome' => 'nome',
            'nascimento' => '2021-01-01',
            'stack' => [1, 'laravel'],
        ])
            ->assertStatus(400)
            ->assertJsonValidationErrors([
                'stack.0',
            ]);
    }

    public function testSistemaDeveCadastrarPessoaComOsParametrosValidos()
    {
        $this->post(route('pessoas.store'), [
            'apelido' => 'apelido',
            'nome' => 'nome',
            'nascimento' => '2021-01-01',
            'stack' => ['php', 'laravel'],
        ])->assertStatus(201)
            ->assertJsonMissingPath('data.id')
            ->assertHeader('Location', route('pessoas.show', [
                'pessoa' => Pessoa::query()->first()->id,
            ]))
            ->assertJsonStructure([
                'data' => [
                    'apelido',
                    'nome',
                    'nascimento',
                    'stack',
                ],
            ]);
    }

    public function testSistemaDeveCadastrarPessoaComOsParametrosValidosESemStack()
    {
        $this->post(route('pessoas.store'), [
            'apelido' => 'apelido',
            'nome' => 'nome',
            'nascimento' => '2021-01-01',
        ])->assertStatus(201)
            ->assertJsonMissingPath('data.id')
            ->assertHeader('Location', route('pessoas.show', [
                'pessoa' => Pessoa::query()->first()->id,
            ]))
            ->assertJsonFragment(['stack' => null])
            ->assertJsonStructure([
                'data' => [
                    'apelido',
                    'nome',
                    'nascimento',
                    'stack',
                ],
            ]);
    }

    public function testSistemaDeveRetornarErroCasoPessoaNaoExistaAoBuscar()
    {
        $this->get(route('pessoas.show', ['pessoa' => '123']))
            ->assertStatus(404);
    }

    public function testSistemaDeveRetornarSucessoCasoPessoaExistaAoBuscar()
    {
        $this->get(route('pessoas.show', ['pessoa' => Pessoa::factory()->create()->id]))
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'apelido',
                    'nome',
                    'nascimento',
                    'stack',
                ],
            ]);
    }

    public function testSistemaDeveRetornarTodasAsPessoasCasoNaoExistaTermo()
    {
        $this->get(route('pessoas.index'))
            ->assertStatus(400);
    }

    public function testSistemaDeveRetornarTodasAsPessoasFiltradasDeAcordoComOTermo()
    {
        Pessoa::factory()->create(['stack' => ['php', 'LARAVEL']]);

        $this->get(route('pessoas.index').'?t=php')
            ->assertStatus(200)
            ->assertJsonFragment(['stack' => ['php', 'LARAVEL']])
            ->assertJsonStructure(['data' => [
                [
                    'id',
                    'apelido',
                    'nome',
                    'nascimento',
                    'stack',
                ],
            ],
            ]);
    }

    public function testSistemaDeveRetornarVazioCasoOTermoNaoSejaEncontrado(): void
    {
        $this->get(route('pessoas.index').'?t=termo')
            ->assertStatus(200)
            ->assertJsonStructure(['data' => []]);
    }

    public function testSistemaDeveRetornarContagemCorretaDePessoas()
    {
        $this->get(route('pessoas.count'))
            ->assertStatus(200)
            ->assertJsonFragment(['data' => 0]);

        Pessoa::factory(3)->create();

        $this->get(route('pessoas.count'))
            ->assertStatus(200)
            ->assertJsonFragment(['data' => 3]);
    }
}
