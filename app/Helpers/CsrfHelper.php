<?php

namespace App\Helpers;

/**
 * Classe para gerenciamento de tokens CSRF
 */
class CsrfHelper
{
    /**
     * Inicializa a sessão se ainda não estiver ativa
     */
    public static function initSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Gera um novo token CSRF
     *
     * @return string Token gerado
     */
    public static function generateToken(): string
    {
        self::initSession();

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = SecurityHelper::randomString(32);
        }

        return $_SESSION['csrf_token'];
    }

    /**
     * Obtém o token CSRF atual
     *
     * @return string Token atual ou novo token
     */
    public static function getToken(): string
    {
        return self::generateToken();
    }

    /**
     * Valida um token CSRF
     *
     * @param string $token Token a ser validado
     * @return bool True se válido
     */
    public static function validateToken(string $token): bool
    {
        self::initSession();

        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * Verifica o token CSRF da requisição POST
     * Retorna false se inválido, pode ser usado para bloquear a requisição
     *
     * @param string $key Nome do campo do token (padrão: csrf_token)
     * @return bool
     */
    public static function verifyRequest(string $key = 'csrf_token'): bool
    {
        if (!SecurityHelper::isPost()) {
            return true;
        }

        $token = SecurityHelper::postRaw($key, '');
        return self::validateToken($token);
    }

    /**
     * Regenera o token CSRF
     * Útil após operações sensíveis
     */
    public static function regenerateToken(): void
    {
        self::initSession();
        $_SESSION['csrf_token'] = SecurityHelper::randomString(32);
    }

    /**
     * Renderiza um campo hidden com o token CSRF
     *
     * @return string HTML do campo hidden
     */
    public static function renderField(): string
    {
        $token = self::getToken();
        return '<input type="hidden" name="csrf_token" value="' . $token . '"\u003e';
    }
}
