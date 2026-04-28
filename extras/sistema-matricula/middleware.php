<?php

class Middleware {
    public static function validarCampos($dados) {
        // Verifica se os campos obrigatórios existem e não estão vazios
        if (empty($dados['nome']) || empty($dados['idade']) || empty($dados['curso'])) {
            die("Erro de Validação: Todos os campos (Nome, Idade e Curso) devem ser preenchidos.");
        }

        // Verifica se a idade é um número válido
        if (!is_numeric($dados['idade'])) {
            die("Erro de Validação: O campo idade deve ser um número.");
        }

        return true;
    }
}
