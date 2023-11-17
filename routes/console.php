<?php

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

use Illuminate\Support\Facades\Artisan;

Artisan::command('aiqfome:install', function () {
    config('database.connections.mysql.host', $this->ask('Me diz aí, qual é a HOST do seu banco de dados?', 'localhost'));
    config('database.connections.mysql.port', $this->ask('Qual é a PORTA do seu banco de dados?', '3306'));
    config('database.connections.mysql.database', $this->ask('Qual é o NOME do seu banco de dados?', 'desafio'));
    config('database.connections.mysql.username', $this->ask('Qual é o USUÁRIO do seu banco de dados?', 'root'));
    config('database.connections.mysql.password', $this->secret('Qual é a SENHA do seu banco de dados? prometo não contar pra ninguém!'));

    $this->info('Banco de dados configurado com sucesso! 💜');
    $this->call('migrate');
    $this->call('test');
});
