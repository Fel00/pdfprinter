<?php

namespace App\Controllers;

use App\Config\Config;

/**
 * Controller base com funcionalidades comuns
 */
abstract class BaseController
{
    protected $config;
    protected $baseUrl;
    protected $viewPath;

    public function __construct()
    {
        $this->config = Config::getInstance();
        $this->baseUrl = $this->getBaseUrl();
        $this->viewPath = __DIR__ . '/../../resources/views/';
    }

    /**
     * Obtém a URL base do projeto
     *
     * @return string
     */
    protected function getBaseUrl(): string
    {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $script = dirname($_SERVER['SCRIPT_NAME']);
        return $protocol . '://' . $host . $script;
    }

    /**
     * Renderiza uma view
     *
     * @param string $view Nome da view (pasta/arquivo)
     * @param array $data Dados para a view
     * @param bool $return Retornar como string (true) ou exibir (false)
     * @return string|null
     */
    protected function render(string $view, array $data = [], bool $return = false): ?string
    {
        $file = $this->viewPath . $view . '.php';

        if (!file_exists($file)) {
            throw new \Exception("View não encontrada: {$view}");
        }

        // Extrai os dados para variáveis
        extract($data);

        if ($return) {
            ob_start();
            include $file;
            return ob_get_clean();
        }

        include $file;
        return null;
    }

    /**
     * Retorna uma resposta JSON
     *
     * @param array $data Dados a serem retornados
     * @param int $statusCode Código HTTP
     */
    protected function json(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    /**
     * Redireciona para uma URL
     *
     * @param string $url URL de destino
     * @param int $statusCode Código HTTP
     */
    protected function redirect(string $url, int $statusCode = 302): void
    {
        header("Location: {$url}", true, $statusCode);
        exit;
    }

    /**
     * Obtém o caminho para asset (CSS, JS, img)
     *
     * @param string $path Caminho relativo ao asset
     * @return string URL completa
     */
    protected function asset(string $path): string
    {
        return $this->baseUrl . '/' . $path;
    }

    /**
     * Valida se a requisição é POST
     *
     * @return bool
     */
    protected function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    /**
     * Obtém dados do POST
     *
     * @param string|null $key Chave específica ou null para todos
     * @param mixed $default Valor padrão
     * @return mixed
     */
    protected function input(?string $key = null, $default = null)
    {
        if ($key === null) {
            return $_POST;
        }
        return $_POST[$key] ?? $default;
    }

    /**
     * Obtém dados do GET
     *
     * @param string|null $key Chave específica ou null para todos
     * @param mixed $default Valor padrão
     * @return mixed
     */
    protected function query(?string $key = null, $default = null)
    {
        if ($key === null) {
            return $_GET;
        }
        return $_GET[$key] ?? $default;
    }
}
