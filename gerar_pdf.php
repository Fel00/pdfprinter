<?php
require_once __DIR__ . '/vendor/autoload.php';
use Mpdf\Mpdf;

function formataDataExtenso($data)
{
    setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf8');
    $timestamp = strtotime($data);
    return strftime('%d de %B de %Y', $timestamp);
}
function censurarTelefone($telefone)
{
    // Remover caracteres não numéricos
    $telefoneLimpo = preg_replace('/\D/', '', $telefone);

    // Verificar comprimento do telefone para aplicar a máscara correta
    if (strlen($telefoneLimpo) === 11) {
        // Formato com DDD e 9 dígitos: (XX) 9XXXX-XXXX
        return preg_replace('/(\d{2})(\d{3})\d{2}(\d{4})/', '($1) $2**$3', $telefoneLimpo);
    } elseif (strlen($telefoneLimpo) === 10) {
        // Formato com DDD e 8 dígitos: (XX) XXXX-XXXX
        return preg_replace('/(\d{2})(\d{2})\d{2}(\d{4})/', '($1) $2**$3', $telefoneLimpo);
    } elseif (strlen($telefoneLimpo) === 9) {
        // Sem DDD e com 9 dígitos: 9XXXX-XXXX
        return preg_replace('/(\d{3})\d{2}(\d{4})/', '$1**$2', $telefoneLimpo);
    } elseif (strlen($telefoneLimpo) === 8) {
        // Sem DDD e com 8 dígitos: XXXX-XXXX
        return preg_replace('/(\d{2})\d{2}(\d{4})/', '$1**$2', $telefoneLimpo);
    } else {
        // Retorna como está caso o formato seja inesperado
        return $telefone;
    }
}



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $contratante = htmlspecialchars($_POST['contratante']);
    $cpf = htmlspecialchars($_POST['cpf']);
    $telefone = htmlspecialchars($_POST['telefone']);
    $telefoneCensurado = censurarTelefone($telefone);
    $endereco = htmlspecialchars($_POST['endereco']);
    $tipoBufet = htmlspecialchars($_POST['tipo_bufet']);
    if ($tipoBufet === "Feiju Personalizada:") {
        $descricao_bufet = $_POST['descricao_bufet'];
    } else {
        $descricao_bufet = '';
    }
    $data = formataDataExtenso(htmlspecialchars($_POST['data']));
    $horarioInicio = htmlspecialchars($_POST['horario_inicio']);
    $horarioConclusao = htmlspecialchars($_POST['horario_conclusao']);
    $horarioChegada = htmlspecialchars($_POST['horario_chegada']);
    $valor_bufet = $_POST['valor_bufet'];
    $valor_deslocamento = $_POST['valor_deslocamento'];
    $valor_total = $_POST['valor_total'];
    $contratadaNome = "FEIJU DELIVERY";
    $cnpj = "23.639.340/0001-62";
    $contratadaEndereco = "Rua Delmiro Gouveia, 1281, Varjota, Fortaleza, Ceará";
    $representante = "JULIANA PEREIRA GOUVEIA";

    $dataAtual = date('d-m-Y');
    $nomeArquivo = "{$contratante}_{$dataAtual}.pdf";

    include 'contrato.php';

    $mpdf = new Mpdf();
    $mpdf->WriteHTML($html);
    $mpdf->Output($nomeArquivo, 'D');
    exit;
} else {
    echo "Nenhum dado foi recebido!";
}
?>