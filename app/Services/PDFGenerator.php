<?php

namespace App\Services;

use \Mpdf\Mpdf;
use \Mpdf\HTMLParserMode;

/**
 * Serviço para geração de PDFs
 */
class PDFGenerator
{
    private $mpdf;
    private $css;

    public function __construct()
    {
        $this->mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_header' => 10,
            'margin_top' => 50,
            'margin_bottom' => 20,
            'margin_footer' => 10,
        ]);
    }

    /**
     * Carrega CSS de um arquivo
     *
     * @param string $cssPath Caminho para o arquivo CSS
     * @return self
     */
    public function loadCss(string $cssPath): self
    {
        if (file_exists($cssPath)) {
            $this->css = file_get_contents($cssPath);
            $this->mpdf->WriteHTML($this->css, HTMLParserMode::HEADER_CSS);
        }
        return $this;
    }

    /**
     * Define CSS inline
     *
     * @param string $css CSS como string
     * @return self
     */
    public function setCss(string $css): self
    {
        $this->css = $css;
        $this->mpdf->WriteHTML($css, HTMLParserMode::HEADER_CSS);
        return $this;
    }

    /**
     * Escreve conteúdo HTML no PDF
     *
     * @param string $html Conteúdo HTML
     * @return self
     */
    public function writeHtml(string $html): self
    {
        $this->mpdf->WriteHTML($html);
        return $this;
    }

    /**
     * Define cabeçalho HTML
     *
     * @param string $html HTML do cabeçalho
     * @return self
     */
    public function setHeader(string $html): self
    {
        $this->mpdf->SetHTMLHeader($html);
        return $this;
    }

    /**
     * Define rodapé HTML
     *
     * @param string $html HTML do rodapé
     * @return self
     */
    public function setFooter(string $html): self
    {
        $this->mpdf->SetHTMLFooter($html);
        return $this;
    }

    /**
     * Gera o PDF para download
     *
     * @param string $filename Nome do arquivo
     */
    public function download(string $filename): void
    {
        $this->mpdf->Output($filename, 'D');
        exit;
    }

    /**
     * Gera o PDF para visualização inline
     *
     * @param string $filename Nome do arquivo
     */
    public function inline(string $filename): void
    {
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="' . $filename . '"');
        header('Cache-Control: private, max-age=0, must-revalidate');
        header('Pragma: public');

        $this->mpdf->Output('', 'I');
        exit;
    }

    /**
     * Gera o PDF para uma string (útil para testes)
     *
     * @return string Conteúdo do PDF
     */
    public function toString(): string
    {
        return $this->mpdf->Output('', 'S');
    }
}
