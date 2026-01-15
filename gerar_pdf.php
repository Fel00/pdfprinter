<?php
require_once __DIR__ . '/vendor/autoload.php';
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