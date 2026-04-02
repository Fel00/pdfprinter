<?php

namespace App\Config;

/**
 * Classe de configuração centralizada
 * Suporta múltiplas fontes de configuração (Caju, Feiju, etc.)
 */
class Config
{
    private static $instance = null;
    private $configs = [];

    private function __construct()
    {
        $this->loadConfigs();
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function loadConfigs(): void
    {
        $configDir = __DIR__ . '/../../config/';

        if (is_dir($configDir)) {
            foreach (glob($configDir . '*.php') as $file) {
                $name = basename($file, '.php');
                $this->configs[$name] = require $file;
            }
        }
    }

    /**
     * Obtém configuração de uma fonte específica
     *
     * @param string $source Nome do arquivo de config (caju, feiju)
     * @param string|null $key Chave específica ou null para array completo
     * @param mixed $default Valor padrão se chave não existir
     * @return mixed
     */
    public function get(string $source, ?string $key = null, $default = null)
    {
        if (!isset($this->configs[$source])) {
            return $default;
        }

        $config = $this->configs[$source];

        if ($key === null) {
            return $config;
        }

        return $config[$key] ?? $default;
    }

    /**
     * Obtém configuração da Caju
     */
    public function getCaju(?string $key = null, $default = null)
    {
        return $this->get('caju', $key, $default);
    }

    /**
     * Obtém configuração da Feiju
     */
    public function getFeiju(?string $key = null, $default = null)
    {
        return $this->get('feiju', $key, $default);
    }
}
