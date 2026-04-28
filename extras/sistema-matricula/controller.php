<?php
require_once 'model.php';
require_once 'service.php';

class MatriculaController {
    public function processarMatricula($dados) {
        try {
            $service = new MatriculaService();
            
            // Aplica as regras de negócio
            $dadosValidados = $service->validarRegras($dados);
            
            // Instancia o Model e salva
            $aluno = new AlunoModel();
            $aluno->setNome($dadosValidados['nome']);
            $aluno->setIdade($dadosValidados['idade']);
            $aluno->setCurso($dadosValidados['curso']);
            
            if ($aluno->save()) {
                $mensagem = "Matrícula realizada com sucesso para " . $dadosValidados['nome'] . "!";
                if ($dadosValidados['bolsa']) {
                    $mensagem .= " Parabéns, você recebeu a Bolsa Melhor Idade!";
                }
                return ['sucesso' => true, 'mensagem' => $mensagem];
            }
            
        } catch (Exception $e) {
            return ['sucesso' => false, 'mensagem' => $e->getMessage()];
        }
    }
}
