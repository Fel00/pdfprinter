<?php
// Aumentar limite de memória
ini_set('memory_limit', '256M');

require_once __DIR__ . '/vendor/autoload.php';

// Recebe os dados do formulário
$evento_nome = $_POST['evento_nome'];
$evento_data = date('d.m.Y', strtotime($_POST['evento_data']));
$evento_pessoas = $_POST['quantidade_pessoas'] . " pessoas";
$evento_inicio = date('H:i', strtotime($_POST['evento_inicio']));
$evento_local = $_POST['evento_local'];

// Valores e pagamento
$valor_por_pessoa = $_POST['valor_por_pessoa'];
$quantidade_pessoas = $_POST['quantidade_pessoas'];
$valor_total = number_format($valor_por_pessoa * $quantidade_pessoas, 2, ',', '.');
$deslocamento = $_POST['deslocamento'];
$forma_pagamento = $_POST['forma_pagamento'];
$condicoes = $_POST['condicoes'];
$observacao = $_POST['observacao'];

// Arrays do cardápio
$menu = [
    'entradinhas' => [
        'titulo' => 'entradinhas volantes',
        'itens' => $_POST['entradinhas']
    ],
    'antepastos' => [
        'titulo' => 'antepastos',
        'itens' => $_POST['antepastos']
    ],
    'almoco' => [
        'titulo' => 'Almoço',
        'itens' => $_POST['almoco']
    ]
];

// Processar novos campos
$garcom = isset($_POST['garcom']) ? $_POST['garcom'] : false;
$ornamentacao = isset($_POST['ornamentacao']) ? array_filter($_POST['ornamentacao']) : [];
$loucas = isset($_POST['loucas']) ? trim($_POST['loucas']) : '';
$equipe = isset($_POST['equipe']) ? trim($_POST['equipe']) : '';

// Estrutura de serviços dinâmica baseada nos campos preenchidos
$servicos = [];

// Garçom (apenas se checkbox marcado)
if ($garcom) {
    $servicos[] = [
        'titulo' => 'garcom para servir bebidas',
        'itens' => []
    ];
}

// Ornamentação (apenas se houver itens)
if (!empty($ornamentacao)) {
    $servicos[] = [
        'titulo' => 'Ornamentação',
        'itens' => $ornamentacao
    ];
}

// Louças (apenas se preenchido)
if (!empty($loucas)) {
    $servicos[] = [
        'titulo' => 'Louças e material para realizarmos o serviço',
        'itens' => [$loucas]
    ];
}

// Equipe (apenas se preenchido)
if (!empty($equipe)) {
    $servicos[] = [
        'titulo' => 'equipe:',
        'itens' => [$equipe]
    ];
}

// HTML do orçamento (template)
include 'orcamento.php';

// Configurações otimizadas do mPDF
$mpdf = new \Mpdf\Mpdf([
    'format' => [187, 334],
    'margin_left' => 0,
    'margin_right' => 0,
    'margin_top' => 0,
    'margin_bottom' => 0,
    'margin_header' => 0,
    'margin_footer' => 0,
    'bleedMargin' => 0,
    'crossMarkMargin' => 0,
    'cropMarkMargin' => 0,
    'nonPrintMargin' => 0,
    'mode' => 'utf-8',
    'tempDir' => sys_get_temp_dir(),
    'allow_output_buffering' => true
]);

$mpdf->WriteHTML($html);
$mpdf->Output('orcamento.pdf', 'I'); 