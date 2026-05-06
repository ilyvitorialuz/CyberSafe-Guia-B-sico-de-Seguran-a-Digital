# 🏗️ Plano de Implementação Avançado: Arquitetura Decoplada e Padrões de Projeto (PHP)

Este guia detalha a refatoração profunda do **CyberSafe**, transpondo padrões de backend profissional (como Repository, Injeção de Dependência e Middlewares avançados) utilizando PHP para garantir segurança, testabilidade e manutenção simplificada.

---

## 🛠️ Passo 1: Segurança e Configuração Base (.env)

A configuração do sistema será isolada de forma que dados sensíveis não fiquem expostos no código.

*   **Arquivo `.env`**: Guardar constantes globais (ex: `DB_HOST`, `DB_NAME`, `DB_USER`).
*   **Arquivo `.env.example`**: Template com as variáveis necessárias.
*   **Classe `Config.php`**: Implementar o padrão **Singleton** para carregar as variáveis do `.env` uma única vez e fornecer acesso global seguro através de um método estático.

---

## 🔄 Passo 2: Evolução do Banco de Dados (Migrations)

Garantir que a estrutura do banco de dados acompanhe as mudanças do projeto de forma versionada.

*   **`Migration.php`**: Classe responsável por executar scripts SQL para criação de tabelas (`users`, `contatos`) e índices.
*   **Versionamento**: Controle de quais migrations já foram executadas para evitar duplicidade e perda de dados.
*   **Abstração**: Uso de **PDO** para garantir portabilidade entre diferentes bancos de dados (MySQL, SQLite, etc.).

---

## 📦 Passo 3: Contratos e Abstração de Dados (Repository)

Isolar a persistência em Repositórios para que a regra de negócio não conheça detalhes do SQL.

*   **`RepositoryInterface.php`**: Definir os métodos obrigatórios: `save()`, `find()`, `delete()`.
*   **`UserRepository.php`**: Implementa a lógica de persistência de usuários.
*   **`ContactRepository.php`**: Implementa a persistência das mensagens de contato.
*   **Entidades**: Uso de classes POO (Plain Old PHP Objects) para representar os dados.

---

## 💉 Passo 4: Injeção de Dependência e Regras de Negócio (Service)

Refatoração dos serviços para garantir desacoplamento total e testabilidade.

*   **`AuthService.php`**: Lida com login e cadastro.
    *   **Injeção de Dependência**: O Service recebe o repositório via construtor (ex: `public function __construct(UserRepositoryInterface $repository)`).
*   **`BusinessRuleException.php`**: Exceção customizada para falhas em regras de negócio. O Service lança esta exceção em vez de retornar valores booleanos ambíguos.

---

## 🎮 Passo 5: Controlador Enxuto e Tratamento de Erros

O Controller atua apenas como maestro do fluxo.

*   **`ContactController.php`**: Método `store()` utiliza apenas um bloco `try-catch`.
    *   **Sucesso**: Invoca o Service e delega a resposta para a View.
    *   **Erro**: Captura `BusinessRuleException` e exibe a mensagem amigável na View.
*   **Limpeza**: O Controller não possui lógica de validação complexa ou acesso direto ao banco.

---

## 🛡️ Passo 6: Front Controller, Router e Middleware

Centralização de todas as requisições em um único ponto de entrada.

*   **`index.php` (Front Controller)**: Ponto de entrada que inicializa o sistema e o Container de DI.
*   **`Router.php`**: Mapeia as URLs para os métodos correspondentes nos Controllers.
*   **`Middleware.php`**: Camada de interceptação para:
    *   **Sanitização**: Uso de `filter_input_array` e limpeza de strings contra XSS antes de chegar ao Controller.
    *   **Autenticação**: Verificar se o usuário tem permissão para acessar determinadas rotas.

---

## 🧪 Passo 7: Testes de Integridade e Deploy

1.  **Validação de Segurança**: Testar o Middleware com payloads maliciosos para garantir o bloqueio de ataques XSS.
2.  **Mocking**: Testar Services injetando repositórios "falsos" (Mocks) para isolar a lógica de negócio.
3.  **Refatoração Contínua**: Garantir que o código segue os padrões PSR (PHP Standard Recommendations).

---

## 🚀 Passo 8: Versionamento e Entrega

1.  **Git Status**: Revisão final das mudanças.
2.  **Commit Semântico**: `git commit -m "feat: implementa arquitetura MVC completa em PHP com Repository e DI"`.
3.  **Documentação**: Atualizar o `README.md` com as instruções de como subir o servidor PHP local (`php -S localhost:8000`).

---
*Este plano reflete o compromisso do CyberSafe com as melhores práticas de Engenharia de Software utilizando o ecossistema PHP.*
