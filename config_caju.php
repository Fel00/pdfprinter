<?php
// Configurações da empresa Caju Catering
// Estas informações devem ser preenchidas conforme o contrato oficial

$config_caju = [
    'nome' => 'DRINKE EVENTOS E SERVICOS LTDA',
    'cnpj' => '51.880.357/0001-42',
    'endereco' => 'alameda das Angélicas, 298, Cidade 2000, Fortaleza, Ceará',
    'representante' => 'FRANCISCO TAUNAY ANDRADE DE ALENCAR',
    'telefone' => '(85) 00000-0000', // Telefone a ser preenchido
    'email' => 'contato@cajucatering.com', // Email a ser preenchido
    
    // Dados bancários
    'banco' => 'BANCO DO NORDESTE',
    'agencia' => '300',
    'conta' => '29059-4',
    'pix' => '51880357000142',
    
    // Logo
    'logo' => 'img/caju.png'
];

// Função para obter configuração
function getConfigCaju($key = null) {
    global $config_caju;
    if ($key === null) {
        return $config_caju;
    }
    return isset($config_caju[$key]) ? $config_caju[$key] : null;
}
?>
