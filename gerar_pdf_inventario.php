<?php
require_once __DIR__ . '/vendor/autoload.php';

// Configurar o mPDF
$mpdf = new \Mpdf\Mpdf([
    'mode' => 'utf-8',
    'format' => 'A4',
    'margin_header' => 10,
    'margin_top' => 50,
    'margin_bottom' => 20,
    'margin_footer' => 10,
]);

// Dados do formulário
$cliente = $_POST['cliente'];
$data = date('d/m/Y', strtotime($_POST['data']));
$local = $_POST['local'];
$equipe = $_POST['equipe'];
$fardamento = $_POST['fardamento'];
$cardapio = $_POST['cardapio'];

// Carregar catálogo de itens
$catalogoJson = file_get_contents('dados/inventario.json');
$catalogoItens = json_decode($catalogoJson, true);
$catalogoMap = array();
foreach ($catalogoItens as $item) {
    $catalogoMap[$item['id']] = $item;
}

// Estilo CSS específico para o inventário
$css = file_get_contents('css/pdf_inventory_style.css');
$mpdf->WriteHTML($css, \Mpdf\HTMLParserMode::HEADER_CSS);

// Conteúdo HTML
$html = '
<div class="header">
    <img src="img/caju.png" class="logo">
    <h1>Checklist de Materiais</h1>
</div>

<div class="info-section">
    <h2>Informações do Evento</h2>
    <table class="info-table">
        <tr>
            <td><strong>Cliente:</strong></td>
            <td>' . htmlspecialchars($cliente) . '</td>
            <td><strong>Data:</strong></td>
            <td>' . $data . '</td>
        </tr>
        <tr>
            <td><strong>Local:</strong></td>
            <td colspan="3">' . htmlspecialchars($local) . '</td>
        </tr>
    </table>
</div>

<div class="info-section">
    <h2>Equipe e Fardamento</h2>
    <p><strong>Equipe:</strong><br>' . nl2br(htmlspecialchars($equipe)) . '</p>
    <p><strong>Fardamento:</strong><br>' . nl2br(htmlspecialchars($fardamento)) . '</p>
</div>

<div class="info-section">
    <h2>Cardápio</h2>
    <p>' . nl2br(htmlspecialchars($cardapio)) . '</p>
</div>

<div class="info-section">
    <h2>Lista de Materiais</h2>
    <table class="materials-table">
        <thead>
            <tr>
                <th>Material</th>
                <th>Quantidade</th>
                <th>Conferido</th>
                <th>Retornou</th>
                <th>Observações</th>
            </tr>
        </thead>
        <tbody>';

// Adicionar materiais à tabela
if (isset($_POST['material_id']) && is_array($_POST['material_id'])) {
    for ($i = 0; $i < count($_POST['material_id']); $i++) {
        $materialId = $_POST['material_id'][$i];
        $quantidade = $_POST['material_quantidade'][$i];
        
        if (isset($catalogoMap[$materialId])) {
            $material = $catalogoMap[$materialId];
            $html .= '
            <tr>
                <td>' . htmlspecialchars($material['nome']) . '</td>
                <td>' . htmlspecialchars($quantidade) . '</td>
                <td class="checkbox">☐</td>
                <td class="checkbox">☐</td>
                <td class="obs"></td>
            </tr>';
        }
    }
}

$html .= '
        </tbody>
    </table>
</div>

<div class="signatures">
    <div class="signature-field">
        <div class="line">____________________________</div>
        <div class="label">Responsável pela Retirada</div>
    </div>
    <br>
    <br>
    <br>
    <div class="signature-field">
        <div class="line">____________________________</div>
        <div class="label">Responsável pela Devolução</div>
    </div>
</div>
';

// Gerar o PDF
$mpdf->WriteHTML($html);

// Adicionar cabeçalho em todas as páginas
$mpdf->SetHTMLHeader('
<div style="text-align: right; font-style: italic;">
    Data do Evento: ' . $data . '<br>
    Cliente: ' . htmlspecialchars($cliente) . '
</div>');

// Adicionar rodapé em todas as páginas
$mpdf->SetHTMLFooter('
<div style="text-align: center; font-size: 10pt;">
    Página {PAGENO} de {nb}
</div>');

// Configurar headers para abrir em nova guia
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="Checklist_' . preg_replace('/[^a-zA-Z0-9]/', '_', $cliente) . '.pdf"');
header('Cache-Control: private, max-age=0, must-revalidate');
header('Pragma: public');

// Saída do PDF
$mpdf->Output('', \Mpdf\Output\Destination::INLINE);