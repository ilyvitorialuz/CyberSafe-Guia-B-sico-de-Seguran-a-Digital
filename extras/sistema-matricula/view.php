<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Matrícula</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 500px; margin: 50px auto; padding: 20px; border: 1px solid #ccc; border-radius: 10px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input, select { width: 100%; padding: 8px; box-sizing: border-box; }
        button { background-color: #28a745; color: white; padding: 10px 15px; border: none; border-radius: 5px; cursor: pointer; width: 100%; }
        .alert { padding: 10px; margin-bottom: 20px; border-radius: 5px; }
        .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-danger { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>
    <h2>Matrícula de Aluno</h2>

    <?php if (isset($resultado)): ?>
        <div class="alert <?php echo $resultado['sucesso'] ? 'alert-success' : 'alert-danger'; ?>">
            <?php echo $resultado['mensagem']; ?>
        </div>
    <?php endif; ?>

    <form action="/" method="POST">
        <div class="form-group">
            <label for="nome">Nome Completo:</label>
            <input type="text" id="nome" name="nome" required>
        </div>
        <div class="form-group">
            <label for="idade">Idade:</label>
            <input type="number" id="idade" name="idade" required>
        </div>
        <div class="form-group">
            <label for="curso">Curso:</label>
            <select id="curso" name="curso" required>
                <option value="">Selecione um curso</option>
                <option value="Desenvolvimento Web">Desenvolvimento Web</option>
                <option value="Ciência de Dados">Ciência de Dados</option>
                <option value="Design UX/UI">Design UX/UI</option>
            </select>
        </div>
        <button type="submit">Realizar Matrícula</button>
    </form>
</body>
</html>
