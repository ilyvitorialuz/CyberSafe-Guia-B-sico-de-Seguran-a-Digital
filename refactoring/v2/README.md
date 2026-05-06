# CyberSafe - Refatoração v2

## Arquitetura Refatorada

Esta versão implementa uma arquitetura profissional em camadas com os seguintes padrões:

### 1. **Padrão Repository**
- Isolamento de lógica de acesso a dados
- Interface `IContatoRepository` define o contrato
- Implementação `ContatoRepository` contém todo SQL
- Facilita testes e mudança de banco de dados

### 2. **Injeção de Dependência (DI)**
- Controller não instancia Service
- Service não instancia Repository
- Todas as dependências vêm pelo construtor
- Reduz acoplamento entre camadas

### 3. **Camada de Serviço (Business Logic)**
- `ContatoService` contém regras de negócio
- Validações complexas ocorrem aqui
- Lança `BusinessRuleException` para erros de negócio
- Não conhece detalhes de como os dados são persistidos

### 4. **Controlador Enxuto**
- Apenas recebe requisição e delega para Service
- Try-catch simples para tratamento de erros
- Renderiza view ou redireciona
- Não contém lógica de negócio

### 5. **Middleware de Validação**
- Sanitização de entrada antes do Controller
- Proteção contra XSS
- Validação de tipos de dados
- CSRF token support

### 6. **Gerenciamento de Banco de Dados**
- Database Singleton em `config/Database.php`
- Lê configurações de `config/config.ini`
- Uma única instância PDO em toda aplicação
- Sem múltiplas conexões concorrentes

### 7. **Configuração Segura**
- `.env` para desenvolvimento
- `config/config.ini` para banco de dados
- Ambos adicionados ao `.gitignore`
- Suporta `APP_DEBUG` para modo desenvolvimento

## Estrutura de Diretórios

```
.
├── config/
│   ├── Database.php           # Singleton de conexão
│   ├── config.ini.example     # Exemplo de configuração
│   └── config.ini             # Configuração real (gitignored)
├── src/
│   ├── Controller/
│   │   └── ContatoController.php      # Controller enxuto
│   ├── Service/
│   │   └── ContatoService.php         # Regras de negócio
│   ├── Repository/
│   │   ├── IContatoRepository.php     # Interface
│   │   └── ContatoRepository.php      # Implementação
│   ├── Models/
│   │   └── Contato.php               # Entidade simples
│   ├── Exception/
│   │   └── BusinessRuleException.php  # Exceção customizada
│   └── Middleware/
│       └── ValidationMiddleware.php   # Validação e sanitização
├── resources/
│   └── views/
│       ├── contatos/
│       │   ├── index.php
│       │   └── show.php
│       └── errors/
│           └── error.php
├── index.php                  # Container DI e roteador
├── .env                       # Variáveis de ambiente (gitignored)
├── .env.example              # Exemplo de .env
└── .gitignore                # Arquivo de exclusão
```

## Como Usar

### 1. Configurar Banco de Dados

```bash
# Copiar arquivo de configuração
cp config/config.ini.example config/config.ini

# Editar config/config.ini com seus dados
```

### 2. Criar Tabela SQL

```sql
CREATE TABLE contatos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    mensagem TEXT NOT NULL,
    categoria VARCHAR(50) DEFAULT 'Contato Geral',
    dataCriacao DATETIME DEFAULT CURRENT_TIMESTAMP
);
```

### 3. Iniciar Servidor

```bash
# Usar PHP built-in server
php -S localhost:8000
```

### 4. Acessar Aplicação

```
http://localhost:8000/contatos
```

## Fluxo de uma Requisição

```
Requisição HTTP
    ↓
index.php (Container DI)
    ↓
ValidationMiddleware (sanitiza entrada)
    ↓
Controller (recebe dados)
    ↓
Service (valida regras de negócio)
    ↓
Repository (persiste dados)
    ↓
Banco de Dados
    ↓
Resposta (View ou Redirecionamento)
```

## Testes Recomendados

### 1. Teste de Erro de Banco de Dados

- Alterar nome da tabela em `ContatoRepository`
- Verificar se erro é capturado sem stack trace para usuário

### 2. Teste de XSS

- Enviar `<script>alert('XSS')</script>` no formulário
- Verificar se middleware sanitiza e remove tags

### 3. Teste de Validação

- Enviar formulário vazio
- Enviar email inválido
- Enviar mensagem com menos de 10 caracteres

### 4. Teste de Autenticação de Banco de Dados

- Alterar credenciais em `config.ini`
- Verificar se aplicação trata erro graciosamente

## Benefícios da Refatoração

✅ **Desacoplamento**: Camadas independentes e testáveis
✅ **Manutenibilidade**: Código organizado e fácil de encontrar
✅ **Escalabilidade**: Adicionar novos repositories/services é simples
✅ **Segurança**: Validação em middleware, sanitização em service
✅ **Profissionalismo**: Segue padrões da indústria
✅ **SOLID**: Implementa princípios SOLID na prática

## Próximas Melhorias

- [ ] Adicionar testes unitários (PHPUnit)
- [ ] Implementar logging
- [ ] Adicionar autenticação de usuários
- [ ] Criar CLI para gerenciar banco de dados
- [ ] Documentação via OpenAPI/Swagger
- [ ] Docker setup
