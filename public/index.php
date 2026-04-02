<?php
/**
 * Front Controller - Entry point da aplicação
 */

// Carrega autoload do Composer
require_once __DIR__ . '/../vendor/autoload.php';

// Carrega autoload manual do App (caso Composer não tenha sido atualizado)
require_once __DIR__ . '/../app/autoload.php';

// Configurações de erro (desativar em produção)
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Define timezone
ini_set('date.timezone', 'America/Sao_Paulo');

// Carrega o Router e define as rotas
use App\Router;

$router = new Router();

// === ROTAS HOME ===
$router->get('/', ['HomeController', 'index']);

// === ROTAS DE CONTRATOS ===
$router->get('/contract/caju', ['ContractController', 'formCaju']);
$router->post('/contract/caju/generate', ['ContractController', 'generateCaju']);
$router->get('/contract/feiju', ['ContractController', 'formFeiju']);
$router->post('/contract/feiju/generate', ['ContractController', 'generateFeiju']);

// === ROTAS DE INVENTÁRIO ===
$router->get('/inventory', ['InventoryController', 'index']);
$router->get('/inventory/catalog', ['InventoryController', 'catalog']);
$router->post('/inventory/catalog', ['InventoryController', 'catalogAction']);
$router->get('/inventory/checkout', ['InventoryController', 'checkout']);
$router->post('/inventory/checkout/generate', ['InventoryController', 'generateChecklist']);

// === ROTAS DE ORÇAMENTO ===
$router->get('/budget', ['BudgetController', 'form']);
$router->post('/budget/generate', ['BudgetController', 'generate']);

// Executa o roteador
$router->dispatch();
