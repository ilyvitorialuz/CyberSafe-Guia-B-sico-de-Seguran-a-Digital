<?php

namespace App\Exception;

use Exception;

/**
 * BusinessRuleException
 * 
 * Exceção customizada para violações de regras de negócio.
 * Diferencia-se de exceções técnicas, permitindo tratamento específico de erros de lógica aplicacional.
 * 
 * Uso: Lançada quando uma operação viola uma regra de negócio definida,
 * como tentativa de criar um contato sem email válido, duplicação de dados, etc.
 */
class BusinessRuleException extends Exception
{
    /**
     * @var array Dados contextuais da erro (opcional)
     */
    private array $context = [];

    /**
     * Construtor
     * 
     * @param string $message Mensagem de erro
     * @param int $code Código de erro
     * @param array $context Dados contextuais opcionais
     * @param Exception|null $previous Exceção anterior
     */
    public function __construct(
        string $message = '',
        int $code = 0,
        array $context = [],
        ?Exception $previous = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->context = $context;
    }

    /**
     * Obtém os dados contextuais da exceção
     * 
     * @return array
     */
    public function getContext(): array
    {
        return $this->context;
    }

    /**
     * Retorna uma representação amigável da exceção para o usuário
     * 
     * @return array
     */
    public function getErrorResponse(): array
    {
        return [
            'error' => true,
            'message' => $this->getMessage(),
            'code' => $this->getCode(),
            'context' => $this->context,
        ];
    }
}
