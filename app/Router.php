<?php

namespace App;

/**
 * Router simples para mapear URLs para controllers
 */
class Router
{
    private $routes = [];
    private $basePath = '';

    public function __construct()
    {
        $this->basePath = dirname($_SERVER['SCRIPT_NAME']);
    }

    /**
     * Registra uma rota GET
     */
    public function get(string $route, array $handler): self
    {
        $this->routes['GET'][$this->normalizeRoute($route)] = $handler;
        return $this;
    }

    /**
     * Registra uma rota POST
     */
    public function post(string $route, array $handler): self
    {
        $this->routes['POST'][$this->normalizeRoute($route)] = $handler;
        return $this;
    }

    /**
     * Registra rotas para um recurso CRUD
     */
    public function resource(string $route, string $controller): self
    {
        // Formulários GET
        $this->get($route, [$controller, 'index']);
        $this->get($route . '/create', [$controller, 'create']);
        $this->get($route . '/{id}', [$controller, 'show']);
        $this->get($route . '/{id}/edit', [$controller, 'edit']);

        // Ações POST
        $this->post($route, [$controller, 'store']);
        $this->post($route . '/{id}/update', [$controller, 'update']);
        $this->post($route . '/{id}/delete', [$controller, 'destroy']);

        return $this;
    }

    /**
     * Processa a requisição atual
     */
    public function dispatch(): void
    {
        $uri = $this->getCurrentUri();
        $method = $_SERVER['REQUEST_METHOD'];

        // Verifica rotas exatas primeiro
        if (isset($this->routes[$method][$uri])) {
            $this->executeHandler($this->routes[$method][$uri]);
            return;
        }

        // Verifica rotas com parâmetros
        foreach ($this->routes[$method] as $route => $handler) {
            if ($this->matchRoute($route, $uri, $params)) {
                $this->executeHandler($handler, $params);
                return;
            }
        }

        // Rota não encontrada
        $this->notFound();
    }

    /**
     * Obtém a URI atual
     */
    private function getCurrentUri(): string
    {
        $uri = $_SERVER['REQUEST_URI'];

        // Remove query string
        if (($pos = strpos($uri, '?')) !== false) {
            $uri = substr($uri, 0, $pos);
        }

        // Remove base path
        $uri = preg_replace('#^' . preg_quote($this->basePath, '#') . '#', '', $uri);

        // Normaliza
        return $this->normalizeRoute($uri);
    }

    /**
     * Normaliza uma rota
     */
    private function normalizeRoute(string $route): string
    {
        $route = trim($route, '/');
        return $route === '' ? '/' : $route;
    }

    /**
     * Verifica se uma rota com parâmetros corresponde à URI
     */
    private function matchRoute(string $route, string $uri, ?array &$params = null): bool
    {
        // Converte parâmetros da rota em regex
        $pattern = preg_replace('/\{([^}]+)\}/', '([^/]+)', $route);
        $pattern = '#^' . $pattern . '$#';

        if (preg_match($pattern, $uri, $matches)) {
            array_shift($matches); // Remove o primeiro match (string completa)
            $params = $matches;
            return true;
        }

        return false;
    }

    /**
     * Executa o handler da rota
     */
    private function executeHandler(array $handler, array $params = []): void
    {
        [$controller, $method] = $handler;

        if (!class_exists($controller)) {
            $controller = "App\\Controllers\\" . $controller;
        }

        if (!class_exists($controller)) {
            throw new \Exception("Controller não encontrado: {$controller}");
        }

        $instance = new $controller();

        if (!method_exists($instance, $method)) {
            throw new \Exception("Método não encontrado: {$method} em {$controller}");
        }

        call_user_func_array([$instance, $method], $params);
    }

    /**
     * Resposta 404
     */
    private function notFound(): void
    {
        http_response_code(404);
        header('Content-Type: text/html; charset=utf-8');
        echo '<!DOCTYPE html>
<html>
<head>
    <title>404 - Página não encontrada</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
        h1 { color: #23413a; }
        a { color: #23413a; }
    </style>
</head>
<body>
    <h1>404 - Página não encontrada</h1>
    <p>A página que você está procurando não existe.</p>
    <p><a href="/">Voltar para a página inicial</a></p>
</body>
</html>';
        exit;
    }
}
