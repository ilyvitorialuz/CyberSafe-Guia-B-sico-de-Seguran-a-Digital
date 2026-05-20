# Diagnóstico Interno de Refatoração - CyberSafe

## 1. Remoção de `require` de classes
- [x] **Status:** 100% concluído.
- **Observações:** Todas as classes são carregadas automaticamente via `spl_autoload_register` configurado no `app/bootstrap.php`. Os únicos `require` remanescentes são para carregamento de arquivos de configuração (`routes.php`) e renderização de Views, o que é permitido pelo padrão MVC.

## 2. Separação de CSS/JS das Views
- [x] **Status:** Concluído.
- **Dificuldades encontradas:** A principal dificuldade foi garantir que as chamadas de API (`fetch`) no `controller.js` estivessem alinhadas com as rotas definidas no backend, especialmente ao lidar com parâmetros dinâmicos como o ID na exclusão de contatos.
- **Localização:** 
  - CSS: `assets/css/style.css`
  - JS: `assets/js/script.js` e `assets/js/controller.js`

## 3. Autoload e Namespaces
- [x] **Status:** Funcionando via PSR-4 customizado.
- **Detalhes:** O mapeamento utiliza a constante `__DIR__`, evitando caminhos absolutos e garantindo portabilidade entre diferentes ambientes (laboratório, local, produção).
- **Namespace Raiz:** `App\` mapeado para a pasta `/app/`.

## 4. Front Controller
- [x] **Status:** `index.php` configurado como ponto de entrada único.
- **Ajuste realizado:** Corrigido o caminho do bootstrap que apontava para uma pasta `src/` inexistente.
