<?php
/**
 * View: Lista de Contatos
 * 
 * Variáveis disponíveis:
 * - $contatos: Array de objetos Contato
 * - $stats: Array com estatísticas
 */
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contatos - CyberSafe</title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>
    <div class="container">
        <h1>Gerenciador de Contatos</h1>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($_GET['success']); ?>
            </div>
        <?php endif; ?>

        <section class="contatos-form">
            <h2>Novo Contato</h2>
            <form method="POST" action="/contatos">
                <div class="form-group">
                    <label for="nome">Nome:</label>
                    <input type="text" id="nome" name="nome" required minlength="3">
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="mensagem">Mensagem:</label>
                    <textarea id="mensagem" name="mensagem" required minlength="10"></textarea>
                </div>

                <div class="form-group">
                    <label for="categoria">Categoria:</label>
                    <select id="categoria" name="categoria">
                        <option value="Contato Geral">Contato Geral</option>
                        <option value="Suporte">Suporte</option>
                        <option value="Dúvida">Dúvida</option>
                        <option value="Feedback">Feedback</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Enviar</button>
            </form>
        </section>

        <section class="contatos-list">
            <h2>Contatos Salvos (<?php echo $stats['total']; ?>)</h2>

            <?php if (empty($contatos)): ?>
                <p>Nenhum contato salvo ainda.</p>
            <?php else: ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Categoria</th>
                            <th>Data de Criação</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($contatos as $contato): ?>
                            <tr>
                                <td><?php echo $contato->getId(); ?></td>
                                <td><?php echo htmlspecialchars($contato->getNome()); ?></td>
                                <td><?php echo htmlspecialchars($contato->getEmail()); ?></td>
                                <td><?php echo htmlspecialchars($contato->getCategoria()); ?></td>
                                <td><?php echo $contato->getDataCriacao(); ?></td>
                                <td>
                                    <a href="/contatos/<?php echo $contato->getId(); ?>" class="btn btn-sm btn-info">Ver</a>
                                    <form method="POST" action="/contatos/<?php echo $contato->getId(); ?>/delete" style="display:inline;">
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza?')">Deletar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </section>
    </div>
</body>
</html>
