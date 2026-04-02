<?php
/**
 * Autoload simples para classes do namespace App
 * Usado quando o Composer não está disponível
 */

spl_autoload_register(function ($class) {
    // Namespace base
    $prefix = 'App\\';

    // Diretório base
    $base_dir = __DIR__ . '/';

    // Verifica se a classe usa o namespace
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    // Pega o nome relativo da classe
    $relative_class = substr($class, $len);

    // Substitui separador de namespace por separador de diretório
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // Se o arquivo existir, carrega
    if (file_exists($file)) {
        require $file;
    }
});
