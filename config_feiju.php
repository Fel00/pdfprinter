<?php
// Configurações da empresa Feiju Delivery
// Ajuste conforme o contrato oficial

$config_feiju = [
    'nome' => 'FEIJU DELIVERY',
    'cnpj' => '23.639.340/0001-62',
    'endereco' => 'Rua Delmiro Gouveia, 1281, Varjota, Fortaleza, Ceará',
    'representante' => 'JULIANA PEREIRA GOUVEIA',
    'telefone' => '(85) 00000-0000',
    'email' => 'contato@feiju.delivery',

    // Dados bancários
    'banco' => 'BANCO ITAÚ',
    'agencia' => '1338',
    'conta' => '21351-3',
    'pix' => '85992078225',

    // Logo
    'logo' => 'img/feiju.jpg',
];

// Função para obter configuração
function getConfigFeiju($key = null)
{
    global $config_feiju;
    if ($key === null) {
        return $config_feiju;
    }
    return isset($config_feiju[$key]) ? $config_feiju[$key] : null;
}

