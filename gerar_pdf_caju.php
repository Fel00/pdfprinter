<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config_caju.php';
use Mpdf\Mpdf;
function formataDataExtenso($data)
{
    $dt = new DateTime($data);
    $fmt = new IntlDateFormatter(
        'pt_BR',
        IntlDateFormatter::LONG,
        IntlDateFormatter::NONE,
        'America/Sao_Paulo',
        IntlDateFormatter::GREGORIAN,
        "d 'de' MMMM 'de' y"
    );

    return $fmt->format($dt);
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
    $loucas = isset($_POST['loucas']) ? trim($_POST['loucas']) : '';
    $equipe = isset($_POST['equipe']) ? trim($_POST['equipe']) : '';
    
    $data = formataDataExtenso(htmlspecialchars($_POST['data']));
    $horarioInicio = htmlspecialchars($_POST['horario_inicio']);
    $horarioConclusao = htmlspecialchars($_POST['horario_conclusao']);
    $horarioChegada = htmlspecialchars($_POST['horario_chegada']);

    // Valores: receber bufet e deslocamento e recalcular servidor-side (aceita formatos como "R$ 1.234,56" ou "1234.56")
    $valor_bufet_raw = isset($_POST['valor_bufet']) ? $_POST['valor_bufet'] : '0';
    $valor_deslocamento_raw = isset($_POST['valor_deslocamento']) ? $_POST['valor_deslocamento'] : '0';

    function parseCurrency($str)
    {
        $str = trim($str);
        // Remove qualquer caractere que não seja dígito, ponto, vírgula ou sinal
        $str = preg_replace('/[^0-9,\.\-]/u', '', $str);
        // Se houver separador de milhares (ponto) e separador decimal (vírgula), remover pontos e trocar vírgula por ponto
        if (strpos($str, ',') !== false && strpos($str, '.') !== false) {
            $str = str_replace('.', '', $str);
            $str = str_replace(',', '.', $str);
        } elseif (strpos($str, ',') !== false && strpos($str, '.') === false) {
            // Assume vírgula como separador decimal
            $str = str_replace(',', '.', $str);
        }
        return is_numeric($str) ? (float) $str : 0.0;
    }

    function formatBR($num)
    {
        return 'R$ ' . number_format($num, 2, ',', '.');
    }

    $valor_bufet_num = parseCurrency($valor_bufet_raw);
    $valor_deslocamento_num = parseCurrency($valor_deslocamento_raw);
    $valor_total_num = $valor_bufet_num + $valor_deslocamento_num;

    $valor_bufet = formatBR($valor_bufet_num);
    $valor_deslocamento = formatBR($valor_deslocamento_num);
    $valor_total = formatBR($valor_total_num);
    
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
