<?php

class MatriculaService {
    /**
     * Valida as regras de negócio para a matrícula.
     * 
     * @param array $dados Dados do aluno (nome, idade, curso)
     * @return array Dados processados
     * @throws Exception Caso alguma regra de negócio falhe
     */
    public function validarRegras($dados) {
        $nome = $dados['nome'];
        $idade = (int)$dados['idade'];
        $curso = $dados['curso'];

        // Regra de Negócio: Idade mínima de 16 anos para qualquer curso
        if ($idade < 16) {
            throw new Exception("O aluno deve ter pelo menos 16 anos para se matricular em qualquer curso.");
        }

        // Regra de Negócio: Lógica de Bolsa de Estudos (exemplo)
        // Se o aluno tiver mais de 60 anos, ele ganha uma "Bolsa Melhor Idade"
        $bolsa = false;
        if ($idade >= 60) {
            $bolsa = true;
        }

        return [
            'nome' => $nome,
            'idade' => $idade,
            'curso' => $curso,
            'bolsa' => $bolsa
        ];
    }
}
