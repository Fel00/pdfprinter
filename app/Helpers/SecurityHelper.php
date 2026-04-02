<?php

namespace App\Helpers;

/**
 * Classe auxiliar para funções de segurança
 */
class SecurityHelper
{
    /**
     * Sanitiza uma string para exibição segura em HTML
     *
     * @param string $text Texto a ser sanitizado
     * @return string Texto sanitizado
     */
    public static function sanitize(string $text): string
    {
        return htmlspecialchars(trim($text), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    /**
     * Sanitiza texto mantendo quebras de linha
     *
     * @param string $text Texto a ser sanitizado
     * @return string Texto sanitizado com nl2br
     */
    public static function sanitizeWithBreaks(string $text): string
    {
        return nl2br(self::sanitize($text));
    }

    /**
     * Valida se uma requisição é POST
     *
     * @return bool
     */
    public static function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    /**
     * Valida se uma requisição é GET
     *
     * @return bool
     */
    public static function isGet(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }

    /**
     * Obtém um valor do POST de forma segura
     *
     * @param string $key Chave do POST
     * @param mixed $default Valor padrão
     * @return mixed
     */
    public static function post(string $key, $default = null)
    {
        return isset($_POST[$key]) ? self::sanitize($_POST[$key]) : $default;
    }

    /**
     * Obtém um valor do GET de forma segura
     *
     * @param string $key Chave do GET
     * @param mixed $default Valor padrão
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        return isset($_GET[$key]) ? self::sanitize($_GET[$key]) : $default;
    }

    /**
     * Obtém um valor do POST sem sanitização (para arrays/campos especiais)
     *
     * @param string $key Chave do POST
     * @param mixed $default Valor padrão
     * @return mixed
     */
    public static function postRaw(string $key, $default = null)
    {
        return $_POST[$key] ?? $default;
    }

    /**
     * Valida se uma string é um email válido
     *
     * @param string $email Email a ser validado
     * @return bool
     */
    public static function isValidEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Gera uma string aleatória segura
     *
     * @param int $length Comprimento da string
     * @return string String aleatória
     */
    public static function randomString(int $length = 32): string
    {
        return bin2hex(random_bytes($length / 2));
    }
}
