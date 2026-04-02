<?php

namespace App\Controllers;

use App\Config\Config;
use App\Helpers\FormatHelper;
use App\Helpers\SecurityHelper;
use App\Services\PDFGenerator;

/**
 * Controller para gestão de contratos
 */
class ContractController extends BaseController
{
    private $pdfGenerator;

    public function __construct()
    {
        parent::__construct();
        $this->pdfGenerator = new PDFGenerator();
    }

    /**
     * Exibe formulário de contrato Caju
     */
    public function formCaju(): void
    {
        $this->render('contract/form_caju', [
            'title' => 'Contrato Caju Catering',
            'config' => $this->config->getCaju()
        ]);
    }

    /**
     * Exibe formulário de contrato Feiju
     */
    public function formFeiju(): void
    {
        $this->render('contract/form_feiju', [
            'title' => 'Contrato Feiju',
            'config' => $this->config->getFeiju()
        ]);
    }

    /**
     * Gera PDF do contrato Caju
     */
    public function generateCaju(): void
    {
        if (!$this->isPost()) {
            $this->redirect('contract/caju');
        }

        $config = $this->config->getCaju();
        $data = $this->processContractData('caju');

        // Gera o HTML do contrato
        $html = $this->render('contract/template_caju', array_merge($data, [
            'config' => $config
        ]), true);

        // Gera o PDF
        $filename = FormatHelper::generateFilename($data['contratante']);
        $this->pdfGenerator->writeHtml($html)->download($filename);
    }

    /**
     * Gera PDF do contrato Feiju
     */
    public function generateFeiju(): void
    {
        if (!$this->isPost()) {
            $this->redirect('contract/feiju');
        }

        $config = $this->config->getFeiju();
        $data = $this->processContractData('feiju');

        // Gera o HTML do contrato
        $html = $this->render('contract/template_feiju', array_merge($data, [
            'config' => $config
        ]), true);

        // Gera o PDF
        $filename = FormatHelper::generateFilename($data['contratante']);
        $this->pdfGenerator->writeHtml($html)->download($filename);
    }

    /**
     * Processa os dados do formulário de contrato
     *
     * @param string $type Tipo de contrato (caju/feiju)
     * @return array Dados processados
     */
    private function processContractData(string $type): array
    {
        $contratante = SecurityHelper::post('contratante', '');
        $cpf = SecurityHelper::post('cpf', '');
        $telefone = SecurityHelper::post('telefone', '');
        $telefoneCensurado = FormatHelper::censurarTelefone($telefone);
        $endereco = SecurityHelper::post('endereco', '');
        $tipoBufet = SecurityHelper::post('tipo_bufet', '');

        // Descrição para bufê personalizado
        $descricaoBufet = '';
        $personalizadoKey = $type === 'caju' ? 'Caju Personalizada' : 'Feiju Personalizada';
        if ($tipoBufet === $personalizadoKey) {
            $descricaoBufet = SecurityHelper::postRaw('descricao_bufet', '');
        }

        $data = SecurityHelper::post('data', '');
        $dataExtenso = FormatHelper::dataPorExtenso($data);
        $horarioInicio = SecurityHelper::post('horario_inicio', '');
        $horarioConclusao = SecurityHelper::post('horario_conclusao', '');
        $horarioChegada = SecurityHelper::post('horario_chegada', '');

        // Processa valores
        $valorBufetRaw = SecurityHelper::postRaw('valor_bufet', '0');
        $valorDeslocamentoRaw = SecurityHelper::postRaw('valor_deslocamento', '0');

        $valorBufetNum = FormatHelper::parseCurrency($valorBufetRaw);
        $valorDeslocamentoNum = FormatHelper::parseCurrency($valorDeslocamentoRaw);
        $valorTotalNum = $valorBufetNum + $valorDeslocamentoNum;

        // Dados específicos da Caju
        $cardapio = [];
        if ($type === 'caju') {
            $mesaFixa = array_filter(SecurityHelper::postRaw('mesa_fixa', []));
            $volantes = array_filter(SecurityHelper::postRaw('volantes', []));
            $bebidas = SecurityHelper::postRaw('bebidas', false);
            $loucas = SecurityHelper::post('loucas', '');
            $equipe = SecurityHelper::post('equipe', '');
            $quantidadePessoas = SecurityHelper::post('quantidade_pessoas', '');

            $cardapio = [
                'mesa_fixa' => $mesaFixa,
                'volantes' => $volantes,
                'bebidas' => $bebidas,
                'loucas' => $loucas,
                'equipe' => $equipe,
                'quantidade_pessoas' => $quantidadePessoas
            ];
        }

        return [
            'contratante' => $contratante,
            'cpf' => $cpf,
            'telefone' => $telefone,
            'telefoneCensurado' => $telefoneCensurado,
            'endereco' => $endereco,
            'tipoBufet' => $tipoBufet,
            'descricao_bufet' => $descricaoBufet,
            'data' => $dataExtenso,
            'data_raw' => $data,
            'horarioInicio' => $horarioInicio,
            'horarioConclusao' => $horarioConclusao,
            'horarioChegada' => $horarioChegada,
            'valor_bufet' => FormatHelper::formatCurrency($valorBufetNum),
            'valor_deslocamento' => FormatHelper::formatCurrency($valorDeslocamentoNum),
            'valor_total' => FormatHelper::formatCurrency($valorTotalNum),
            'cardapio' => $cardapio
        ];
    }
}
