<p align="center"><img src="https://static.wixstatic.com/media/00f415_cde5691d99574ffd9027b7c9ef8fea4b~mv2.png/v1/fit/w_2500,h_1330,al_c/00f415_cde5691d99574ffd9027b7c9ef8fea4b~mv2.png" width="400" alt="Aiqfome Logo"></p>

## DESAFIO DESENVOLVEDOR PHP PLENO - AIQFOME

### Sobre o desafio

Este repositório é a minha solução para o desafio de desenvolvedor PHP Pleno para o Aiqfome.
O teste foi inspirado em um desafio real que ficou famoso na internet recentemente,
o [Rinha de Backend](https://github.com/zanfranceschi/rinha-de-backend-2023-q3),
no qual o objetivo é criar uma rixa entre linguagens e frameworks para avaliar qual tecnologia se desempenha melhor em
determinados
cenários pré-definidos. O desafio propõe a criação de uma API REST simples para gerenciar pessoas em um sistema.

## Tecnologias utilizadas

- [Laravel 10](https://laravel.com/docs/10.x) - Framework PHP
- [PHP 8.2](https://www.php.net/releases/8.2/en.php) - Linguagem de programação
- [MySQL](https://dev.mysql.com/) - Banco de dados
- [GitHub Actions](https://github.com/features/actions) - CI/CD

## Processo de desenvolvimento

Ao analisar a proposta do desafio, percebi que o framework Laravel seria ideal para agilizar e assegurar o funcionamento
de alguns requisitos funcionais da aplicação, para me assegurar de que todas as requisições apontadas para as rotas de
API funcionassem corretamente, criei um middleware Chamado `EnsureAcceptJson` que garante que todas as requisições que
chegam na API sejam do tipo `application/json`. Em seguida desenhei o banco de dados, criei a migration e o model de
`Pessoa` e logo em seguida criei um Resource (ferramenta do laravel que auxilia a tratativa e padronização de dados)
para a model Pessoa, assim padronizando todos os retornos da API. Após isso, criei o controller de api chamado
`PessoaController` no qual utilizei o parâmetro `--api` do artisan para criar um controller já com os métodos
predefinidos
para uma API REST. Após isso, utilizei os métodos de `index`, `show`, `store`, e removi os
métodos
`update` e `destroy`, pois não faziam parte dos requisitos funcionais do desafio. Após isso, criei os testes unitários
para os métodos `index`, `show` e `store`, no qual utilizei o banco de dados de teste do laravel para testar
as requisições. Aplicação foi desenvolvida utilizando as seguintes regras de princípios da programação: SOLID, 
Clean Code, Object Calisthenics e Clean Architecture.

## Principais desafios

- Durante o processo de desenvolvimento percebi que eu nunca tinha realizado uma validação de sintaxe para diferenciar
requisições as 422 de 400 e isso foi extremamente divertido, no começo quebrei um pouco a cabeça pensando em como eu poderia
fazer isso funcionar sem que ficasse um código muito robusto ou difícil de entender, foi aí que me lembrei de um método
na classe do Form Request chamado `failedValidation` que é chamado quando a validação falha, assim eu poderia identificar
um erro de sintaxe e retornar o código 400.
 
<br />

- A princípio eu imaginei e desenhei o campo "stack" da entidade Pessoa do banco de dados como um JSON, assim eu poderia
converter ele através do atributo "cast" na model Pessoa, porém ao realizar os testes (e é nesse momento que o TDD salva a gente)
eu percebi que o método `whereJsonContains` do query builder padrão do Laravel não conseguia encontrar um dado no banco de dados
caso ele apresentasse um único caractere em uma "Case" diferente, ou seja, ele não iria encontrar a pessoa que tivesse a stack
"NODE" quando o termo de pesquisa fosse "node". Foi aí que eu decidi mudar o campo "stack" para um valor do tipo
texto e realizar a conversão através do atributo "cast" no model Pessoa, assim fazendo com que tanto a busca quanto o retorno funcionassem corretamente. Esse não foi exatamente um grande desafio, mas eu quebrei a cabeça
até descobrir que o problema estava na query.

## Principais Ferramentas utilizadas

| Ferramenta        | Descrição                                                                      | Motivo do uso                                                                                                                                                                                                |
|-------------------|--------------------------------------------------------------------------------|--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| FormRequests      | Permite criar uma classe auxiliar para validar requisições no Laravel          | Me auxiliou na validação dos campos de acordo com regras pre-definidas, retornando erros como 422 e 400 automaticamente como era especificado em um dos requisitos do sistema.                               |
| Middlewares       | Permite criar uma classe que auxilia na interceptação de requisições           | Me auxiliou na interceptação de requisições para garantir que todas as requisições que chegam na API sejam do tipo `application/json`, mas também poderiam ser utilizados para validar tokens por exemplo    |
| Resources         | Permite criar uma classe que auxilia a tratativa e retorno de dados no sistema | Me Auxiliou na tratativa de retorno dos dados, tendo em vista que um dos retornos da API não poderia mostrar o ID no corpo da resposta por exemplo, mas sim em um header específico.                         |
| PHPUnit           | Permite criar testes unitários para o sistema                                  | Me auxiliou na criação de testes automatizados para garantir o funcionamento correto das requisições                                                                                                         |
| Model Binding     | Permite criar um binding entre o model e o controller                          | Me auxiliou a criar um binding entre o model e o controller, assim evitando a necessidade de buscar o registro no banco de dados                                                                             |
| Exception Handler | Permite criar uma classe que auxilia na interceptação de exceções              | Me auxiliou na interceptação de exceções para garantir que todas as exceções que chegam na API não fossem retornadas diretamente para o usuário, assim garantindo a segurança dos dados apresentados no JSON |
| Route Resource    | Permite criar um binding entre o model e a rota                                | Me auxiliou a criar todas as rotas necessárias em poucas linhas de código                                                                                                                                    |
| Model Factory     | Permite criar uma classe que auxilia na criação de dados para testes           | Me auxiliou a criar dados para testes de forma rápida e prática                                                                                                                                              |

> Obs: Todas as ferramentas utilizadas foram escolhidas com base na documentação do Laravel.

## Como rodar o projeto

Você pode rodar o projeto da maneira padrão do laravel, instalando as dependências com o composer e rodando o servidor e os testes,
ou você pode fazer da maneira mais legal: rodando o projeto com o comando criado por mim:

```bash
composer aiqfome
```

Este comando já vai fazer tudo que você precisa para instalar o projeto enquanto você toma seu cafézinho.
Logo em seguida basta configurar o seu banco de dados e partir para os testes.

> Obs.: As especificações dos comandos podem ser encontradas nos arquivos `composer.json` na chave `scripts` 

# Observações

Eu gostaria de ter utilizado algumas ferramentas a mais no projeto, ainda mais após descobrir que o teste se tratava
de uma inspiração direta do desafio [Rinha de Backend](https://github.com/zanfranceschi/rinha-de-backend-2023-q3), que foi
um desafio que experimentei na época, apesar de não ter contribuído diretamente para o projeto.

Já que o desafio não buscava avaliar o desempenho da aplicação, decidi que não utilizaria
JOBS e FILAS, apesar de serem ferramentas que eu gosto muito e que eu acredito que poderiam ter me 
ajudado a mostrar um pouco mais dos meus conhecimentos em Backend.

## Obrigado pela oportunidade 💜
