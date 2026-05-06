<?php
/**
 * View: Página de Erro
 * 
 * Variáveis disponíveis:
 * - $error: Mensagem de erro
 */
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erro - CyberSafe</title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>
    <div class="container">
        <div class="alert alert-error">
            <h2>Erro</h2>
            <p><?php echo htmlspecialchars($error ?? 'Ocorreu um erro desconhecido.'); ?></p>
        </div>
        <a href="/contatos" class="btn btn-primary">Voltar</a>
    </div>
</body>
</html>
