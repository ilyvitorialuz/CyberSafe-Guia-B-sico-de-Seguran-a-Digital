<?php
/**
 * View: Detalhes de um Contato
 * 
 * Variáveis disponíveis:
 * - $contato: Objeto Contato
 */
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contato - CyberSafe</title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>
    <div class="container">
        <h1>Detalhes do Contato</h1>

        <div class="card">
            <div class="card-body">
                <p><strong>ID:</strong> <?php echo $contato->getId(); ?></p>
                <p><strong>Nome:</strong> <?php echo htmlspecialchars($contato->getNome()); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($contato->getEmail()); ?></p>
                <p><strong>Categoria:</strong> <?php echo htmlspecialchars($contato->getCategoria()); ?></p>
                <p><strong>Data de Criação:</strong> <?php echo $contato->getDataCriacao(); ?></p>
                <p><strong>Mensagem:</strong></p>
                <p><?php echo htmlspecialchars($contato->getMensagem()); ?></p>

                <a href="/contatos" class="btn btn-primary">Voltar</a>
                <form method="POST" action="/contatos/<?php echo $contato->getId(); ?>/delete" style="display:inline;">
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Tem certeza?')">Deletar</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
