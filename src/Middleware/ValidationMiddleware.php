<?php

namespace App\Middleware;

use App\Exception\BusinessRuleException;

/**
 * ValidationMiddleware
 * 
 * Middleware para validação e sanitização de entrada (input validation).
 * Aplica medidas de segurança ANTES de os dados chegarem ao Controller.
 * 
 * Responsabilidades:
 * - Sanitizar dados POST/GET
 * - Prevenir injeções XSS
 * - Validar tipos de dados
 * - Proteger contra CSRF (tokens)
 */
class ValidationMiddleware
{
    /**
     * Processa requisição e valida dados de entrada
     * 
     * @param array $data Dados a validar (geralmente $_POST ou $_GET)
     * @param array $rules Regras de validação
     * @return array Dados sanitizados
     * @throws BusinessRuleException Se falhar validação
     */
    public static function validate(array $data, array $rules): array
    {
        $sanitized = [];

        foreach ($rules as $field => $rule) {
            if (!isset($data[$field])) {
                if ($rule['required'] ?? false) {
                    throw new BusinessRuleException(
                        "Campo obrigatório ausente: {$field}",
                        400,
                        ['campo' => $field]
                    );
                }
                $sanitized[$field] = $rule['default'] ?? null;
                continue;
            }

            $value = $data[$field];

            // Sanitiza de acordo com o tipo
            if (isset($rule['type'])) {
                $value = match ($rule['type']) {
                    'email' => self::sanitizeEmail($value),
                    'text' => self::sanitizeText($value),
                    'textarea' => self::sanitizeTextarea($value),
                    'number' => self::sanitizeNumber($value),
                    'url' => self::sanitizeUrl($value),
                    default => $value,
                };
            }

            // Valida comprimento mínimo
            if (isset($rule['min']) && strlen((string)$value) < $rule['min']) {
                throw new BusinessRuleException(
                    "{$field} deve ter no mínimo {$rule['min']} caracteres.",
                    400,
                    ['campo' => $field, 'minimo' => $rule['min']]
                );
            }

            // Valida comprimento máximo
            if (isset($rule['max']) && strlen((string)$value) > $rule['max']) {
                throw new BusinessRuleException(
                    "{$field} não pode exceder {$rule['max']} caracteres.",
                    400,
                    ['campo' => $field, 'maximo' => $rule['max']]
                );
            }

            // Validação customizada
            if (isset($rule['validate']) && is_callable($rule['validate'])) {
                if (!$rule['validate']($value)) {
                    throw new BusinessRuleException(
                        "Validação falhou para o campo: {$field}",
                        400,
                        ['campo' => $field]
                    );
                }
            }

            $sanitized[$field] = $value;
        }

        return $sanitized;
    }

    /**
     * Sanitiza email
     */
    private static function sanitizeEmail(string $email): string
    {
        return filter_var($email, FILTER_SANITIZE_EMAIL) ?: '';
    }

    /**
     * Sanitiza texto simples (remove tags HTML e scripts)
     */
    private static function sanitizeText(string $text): string
    {
        // Remove tags HTML
        $text = strip_tags($text);
        // Remove espaços extras
        $text = preg_replace('/\s+/', ' ', $text);
        return trim($text);
    }

    /**
     * Sanitiza textarea (permite quebras de linha, mas remove scripts)
     */
    private static function sanitizeTextarea(string $text): string
    {
        // Remove tags HTML perigosas
        $text = strip_tags($text, '<br><p><em><strong>');
        // Remove espaços extras mas preserva quebras de linha
        $text = preg_replace('/^[ \t]+/m', '', $text);
        return trim($text);
    }

    /**
     * Sanitiza número
     */
    private static function sanitizeNumber($value): int|float
    {
        if (filter_var($value, FILTER_VALIDATE_INT) !== false) {
            return (int)$value;
        }
        if (filter_var($value, FILTER_VALIDATE_FLOAT) !== false) {
            return (float)$value;
        }
        return 0;
    }

    /**
     * Sanitiza URL
     */
    private static function sanitizeUrl(string $url): string
    {
        return filter_var($url, FILTER_SANITIZE_URL) ?: '';
    }

    /**
     * Verifica e valida CSRF Token
     * 
     * @param string $token Token fornecido
     * @return bool
     */
    public static function validateCsrfToken(string $token): bool
    {
        if (!isset($_SESSION['csrf_token'])) {
            return false;
        }

        return hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * Gera um CSRF Token
     * 
     * @return string
     */
    public static function generateCsrfToken(): string
    {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }
}
