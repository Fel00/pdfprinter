<?php

namespace App\Controllers;

use App\Config\Config;
use App\Helpers\SecurityHelper;
use App\Services\PDFGenerator;

/**
 * Controller para gestão de orçamentos
 */
class BudgetController extends BaseController
{
    private $pdfGenerator;

    public function __construct()
    {
        parent::__construct();
        $this->pdfGenerator = new PDFGenerator();
    }

    /**
     * Exibe formulário de orçamento
     */
    public function form(): void
    {
        $this->render('budget/form', [
            'title' => 'Orçamento Caju Catering',
            'config' => $this->config->getCaju()
        ]);
    }

    /**
     * Gera PDF do orçamento
     */
    public function generate(): void
    {
        if (!$this->isPost()) {
            $this->redirect('budget/form');
        }

        // Dados do evento
        $eventoNome = SecurityHelper::post('evento_nome', '');
        $eventoData = SecurityHelper::post('evento_data', '');
        $eventoDataFormatada = date('d/m/Y', strtotime($eventoData));
        $quantidadePessoas = (int) SecurityHelper::post('quantidade_pessoas', 0);
        $eventoInicio = SecurityHelper::post('evento_inicio', '');
        $eventoLocal = SecurityHelper::post('evento_local', '');

        // Cardápio
        $entradinhas = array_filter(SecurityHelper::postRaw('entradinhas', []));
        $antepastos = array_filter(SecurityHelper::postRaw('antepastos', []));
        $almoco = array_filter(SecurityHelper::postRaw('almoco', []));

        $menu = [];
        if (!empty($entradinhas)) {
            $menu[] = [
                'titulo' => 'Entradinhas Volantes',
                'itens' => $entradinhas
            ];
        }
        if (!empty($antepastos)) {
            $menu[] = [
                'titulo' => 'Antepastos',
                'itens' => $antepastos
            ];
        }
        if (!empty($almoco)) {
            $menu[] = [
                'titulo' => 'Almoço',
                'itens' => $almoco
            ];
        }

        // Serviços adicionais
        $garcom = SecurityHelper::postRaw('garcom', false);
        $ornamentacao = array_filter(SecurityHelper::postRaw('ornamentacao', []));
        $loucas = SecurityHelper::post('loucas', '');
        $equipe = SecurityHelper::post('equipe', '');

        $servicos = [];

        // Ornamentação
        if (!empty($ornamentacao)) {
            $servicos[] = [
                'titulo' => 'Ornamentação',
                'itens' => $ornamentacao
            ];
        }

        // Louças
        if (!empty($loucas)) {
            $servicos[] = [
                'titulo' => 'Louças e material',
                'itens' => [$loucas]
            ];
        }

        // Equipe
        if (!empty($equipe)) {
            $servicos[] = [
                'titulo' => 'Equipe',
                'itens' => [$equipe]
            ];
        }

        // Garçom
        if ($garcom) {
            $servicos[] = [
                'titulo' => 'Garçom para servir bebidas',
                'itens' => []
            ];
        }

        // Valores
        $valorPorPessoa = SecurityHelper::post('valor_por_pessoa', '');
        $deslocamento = SecurityHelper::post('deslocamento', '');

        // Calcula valor total
        $valorTotal = $quantidadePessoas * $this->extractNumericValue($valorPorPessoa);
        $valorTotalFormatado = number_format($valorTotal, 2, ',', '.');

        // Forma de pagamento
        $formaPagamento = SecurityHelper::post('forma_pagamento', '');
        $condicoes = array_filter(SecurityHelper::postRaw('condicoes', []));
        $observacao = SecurityHelper::post('observacao', '');

        // Carrega CSS
        $cssPath = __DIR__ . '/../../public/css/pdf_style.css';

        // Gera o HTML
        $html = $this->render('budget/template', [
            'evento_nome' => $eventoNome,
            'evento_data' => $eventoDataFormatada,
            'evento_pessoas' => $quantidadePessoas . ' pessoas',
            'evento_inicio' => $eventoInicio,
            'evento_local' => $eventoLocal,
            'menu' => $menu,
            'servicos' => $servicos,
            'valor_por_pessoa' => $valorPorPessoa,
            'quantidade_pessoas' => $quantidadePessoas,
            'valor_total' => $valorTotalFormatado,
            'deslocamento' => $deslocamento,
            'forma_pagamento' => $formaPagamento,
            'condicoes' => $condicoes,
            'observacao' => $observacao
        ], true);

        // Gera PDF
        $this->pdfGenerator->loadCss($cssPath);
        $this->pdfGenerator->writeHtml($html);

        $filename = 'Orcamento_' . preg_replace('/[^a-zA-Z0-9]/', '_', $eventoNome) . '.pdf';
        $this->pdfGenerator->inline($filename);
    }

    /**
     * Extrai valor numérico de string monetária
     *
     * @param string $value
     * @return float
     */
    private function extractNumericValue(string $value): float
    {
        // Remove R$, pontos e converte vírgula para ponto
        $value = str_replace(['R$', ' ', '.'], '', $value);
        $value = str_replace(',', '.', $value);
        return (float) $value;
    }
}
