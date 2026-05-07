# Análise Técnica e Arquitetural - Projeto CyberSafe

Este documento fornece uma análise detalhada das escolhas arquiteturais, isolamento de responsabilidades, mecanismos de blindagem e práticas de versionamento adotadas no projeto CyberSafe.

## 1. Defesa Arquitetural: MVC, Interfaces e Injeção de Dependência

O projeto foi estruturado utilizando o padrão **MVC (Model-View-Controller)**, o que permite uma separação clara entre a interface do usuário, a lógica de negócio e o acesso aos dados.

### Benefícios do Desacoplamento
*   **Interfaces:** O uso da `RepositoryInterface` estabelece um contrato formal para a persistência de dados. Isso garante que qualquer implementação de repositório (seja para MySQL, SQLite ou mesmo um Mock para testes) siga a mesma assinatura de métodos, facilitando a substituição tecnológica sem impacto nas camadas superiores.
*   **Injeção de Dependência (DI):** No `Router.php`, as dependências dos Controllers (Services) e dos Services (Repositories) são resolvidas e injetadas via construtor. Isso evita o acoplamento rígido (hardcoding de `new Class()`) dentro das classes de negócio, tornando o sistema:
    *   **Testável:** É possível injetar mocks de repositórios para testar as regras de negócio dos Services de forma isolada.
    *   **Flexível:** Mudanças na forma como um objeto é construído são centralizadas no Router (ou em um Container de DI futuro).

---

## 2. Diagnóstico de Isolamento de Responsabilidades

O projeto demonstra uma maturidade avançada no isolamento de camadas, mas apresenta pontos de atenção identificados no diagnóstico:

### Pontos Fortes
*   **Queries SQL Isoladas:** Não existem queries SQL nos Services ou Controllers. Toda a interação com o banco de dados está confinada nas classes do diretório `Repositories`.
*   **Regras de Negócio no Service:** Validações de e-mail duplicado (`AuthService`) e obrigatoriedade de campos (`ContactService`) estão onde devem estar: na camada de Serviço.

### Oportunidades de Melhoria (Leaks Identificados)
*   **Gestão de Sessão no Controller:** No `AuthController::login`, a inicialização de `session_start()` e a manipulação direta de `$_SESSION` ocorrem dentro do Controller. Em uma arquitetura ideal, isso poderia ser delegado a um `SessionService` ou um Middleware de Autenticação para isolar o Controller de detalhes de infraestrutura de estado.
*   **Sanitização de JSON:** O `Middleware::sanitize()` atualmente limpa apenas `$_GET` e `$_POST`. Como a aplicação consome JSON via `php://input`, os dados chegam ao Controller sem uma pré-sanitização automática, dependendo exclusivamente da validação manual no Service.

---

## 3. Blindagem da Aplicação: Erros, Validação e Sanitização

A aplicação implementa múltiplas camadas de proteção:

*   **Tratamento de Erros:** O uso de `BusinessRuleException` permite que a aplicação capture falhas de lógica (ex: "E-mail já cadastrado") e responda com status codes HTTP apropriados (400/401), enquanto erros genéricos de sistema são capturados como 500, protegendo detalhes técnicos do servidor (fail-safe).
*   **Prevenção de SQL Injection:** Todas as interações com o banco de dados utilizam **PDO com Prepared Statements**. Os parâmetros nunca são concatenados diretamente na string SQL, eliminando o risco de injeção.
*   **Validação de Entrada:** O `ContactService` utiliza `filter_var` para validar e-mails, garantindo que apenas dados formatados corretamente sejam processados.

---

## 4. Integridade do Versionamento

O projeto segue rigorosas práticas de versionamento para garantir a colaboração e a segurança:

*   **Histórico de Commits:** Utiliza-se o padrão de **Commits Semânticos** (ex: `feat:`, `chore:`, `docs:`), o que torna o histórico legível e profissional, facilitando o rastreamento de mudanças.
*   **Segurança de Configurações:** O arquivo `.env` (contendo credenciais) e o banco de dados local `cybersafe.db` foram devidamente removidos do rastreamento do Git e adicionados ao `.gitignore`. Isso garante que informações sensíveis do ambiente de desenvolvimento não vazem para o repositório público.
*   **Templates de Configuração:** O uso de `.env.example` permite que novos desenvolvedores configurem seus ambientes sem acesso às chaves reais de produção/desenvolvimento do autor original.
