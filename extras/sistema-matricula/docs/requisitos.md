# Atividades - Sistema de Matrícula

## Passo 1: Preparando o Terreno (Migration)
Nos seus projetos, criem o arquivo `migration.php`. A responsabilidade deste arquivo é rodar as configurações iniciais do banco de dados.

A classe `Migration` deve usar o **PDO** para criar um arquivo `database.sqlite` (caso não exista) e executar o comando SQL de criação da tabela:
```sql
CREATE TABLE IF NOT EXISTS alunos (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nome TEXT,
    idade INTEGER,
    curso TEXT
);
```
> **Nota:** Os alunos deverão executar esse arquivo uma única vez antes de testarem a aplicação completa.

---

## Passo 2: A Base de Dados (Model)
Criem o arquivo `model.php` contendo a classe `AlunoModel`. Este é o único local do sistema que vai se comunicar com a tabela de alunos.

### Regra de Ouro (POO):
*   A classe deve ter propriedades privadas (`nome`, `idade`, `curso`).
*   Métodos públicos (**Getters** e **Setters**).
*   O método `save()` deve:
    1.  Instanciar uma conexão **PDO** com o arquivo `database.sqlite`.
    2.  Usar um `INSERT INTO` com **Prepared Statements** para evitar SQL Injection.
    3.  Salvar os dados do objeto na tabela.

---

## Passo 3: Regras Complexas e Especializadas (Service)
Criem o arquivo `service.php` com a classe `MatriculaService`. O Serviço não lida com requisições HTTP nem com comandos SQL, ele apenas resolve regras de negócio.

Este serviço deve:
*   Receber os dados do aluno.
*   Simular uma regra avançada (ex: verificar idade mínima do curso ou lógica de bolsa de estudos).
*   Retornar os dados processados ou lançar uma exceção (`Exception`) caso a regra falhe.

---

## Passo 4: O Maestro e a Interface (Controller e View)
Criem o arquivo `controller.php` contendo a classe `MatriculaController`. Este é o gerente do processo.

### Controller:
O método `processarMatricula()` deve:
1.  Receber os dados vindos da requisição.
2.  Chamar o `MatriculaService` para aplicar as regras.
3.  Se aprovado, instanciar o `AlunoModel` para salvar no SQLite.
4.  Decidir a resposta para o usuário (sucesso ou erro).

### View:
Construam a interface no arquivo `view.php`:
*   Um formulário HTML contendo: Nome, Idade e Curso.
*   Configurado para enviar os dados via método `POST` para a raiz do servidor.

---

## Passo 5: A Porta de Entrada, Rotas e Segurança (Index, Router e Middleware)
Vamos conectar as requisições:

*   **`index.php`**: O *Front Controller*. Ponto de entrada único que recebe a visita do navegador e aciona o `router.php`.
*   **`router.php`**: A classe `Router` avalia a URL e o método.
    *   Requisições **GET** chamam a exibição do `view.php`.
    *   Requisições **POST** acionam o `Controller`.
*   **`middleware.php`**: Atua como segurança antes do Controller.
    *   Verifica se todos os campos foram preenchidos.
    *   Verifica se a idade é um número.
    *   Se a validação falhar, o processo é encerrado com uma mensagem de aviso.

---

## Passo 6: Mão na Massa e Servidor Built-in
1.  Abram o terminal na pasta do projeto.
2.  Rodem a migration:
    ```bash
    php migration.php
    ```
3.  Iniciem o servidor:
    ```bash
    php -S localhost:8000
    ```
4.  Acessem a aplicação pelo navegador.

### Testes recomendados:
*   Enviar o formulário vazio para checar o bloqueio do `middleware.php`.
*   Quebrar a regra de negócio do curso para ver a recusa do `service.php`.
*   Enviar dados válidos e confirmar a gravação no `database.sqlite` (usando extensões como SQLite Viewer).
*   Monitorar o terminal do PHP para depuração.

Ao finalizar, construam uma pasta separada, façam o commit estruturado e subam para o GitHub.
