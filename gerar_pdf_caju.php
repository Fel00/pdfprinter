<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config_caju.php';
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
    $quantidade_pessoas = htmlspecialchars($_POST['quantidade_pessoas']);
    $tipoBufet = htmlspecialchars($_POST['tipo_bufet']);
    if ($tipoBufet === "Caju Personalizada") {
        $descricao_bufet = $_POST['descricao_bufet'];
    } else {
        $descricao_bufet = '';
    }
    
    // Processar cardápio
    $mesa_fixa = isset($_POST['mesa_fixa']) ? array_filter($_POST['mesa_fixa']) : [];
    $volantes = isset($_POST['volantes']) ? array_filter($_POST['volantes']) : [];
    $bebidas = isset($_POST['bebidas']) ? $_POST['bebidas'] : false;
    $ornamentacao = isset($_POST['ornamentacao']) ? array_filter($_POST['ornamentacao']) : [];
    $loucas = isset($_POST['loucas']) ? trim($_POST['loucas']) : '';
    $equipe = isset($_POST['equipe']) ? trim($_POST['equipe']) : '';
    
    $data = formataDataExtenso(htmlspecialchars($_POST['data']));
    $horarioInicio = htmlspecialchars($_POST['horario_inicio']);
    $horarioConclusao = htmlspecialchars($_POST['horario_conclusao']);
    $horarioChegada = htmlspecialchars($_POST['horario_chegada']);
    $valor_total = $_POST['valor_total'];
    
    // Informações da Caju Catering
    $contratadaNome = getConfigCaju('nome');
    $cnpj = getConfigCaju('cnpj');
    $contratadaEndereco = getConfigCaju('endereco');
    $representante = getConfigCaju('representante');

    $dataAtual = date('d-m-Y');
    $nomeArquivo = "{$contratante}_{$dataAtual}.pdf";

    include 'contrato_caju.php';

    $mpdf = new Mpdf();
    $mpdf->WriteHTML($html);
    $mpdf->Output($nomeArquivo, 'D');
    exit;
} else {
    echo "Nenhum dado foi recebido!";
}
?>
