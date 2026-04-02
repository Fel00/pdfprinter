<?php

namespace App\Helpers;

/**
 * Classe auxiliar para formatação de dados
 */
class FormatHelper
{
    /**
     * Formata uma data para formato por extenso em português
     *
     * @param string $data Data no formato Y-m-d
     * @return string Data por extenso (ex: "1 de janeiro de 2024")
     */
    public static function dataPorExtenso(string $data): string
    {
        $dt = new \DateTime($data, new \DateTimeZone('America/Sao_Paulo'));
        $fmt = new \IntlDateFormatter(
            'pt_BR',
            \IntlDateFormatter::LONG,
            \IntlDateFormatter::NONE,
            'America/Sao_Paulo',
            \IntlDateFormatter::GREGORIAN,
            "d 'de' MMMM 'de' y"
        );

        return $fmt->format($dt);
    }

    /**
     * Censura um número de telefone para exibição em contratos
     * Ex: (85) 99999-9999 vira (85) 9**99-9999
     *
     * @param string $telefone Número de telefone
     * @return string Telefone censurado
     */
    public static function censurarTelefone(string $telefone): string
    {
        $telefoneLimpo = preg_replace('/\D/', '', $telefone);

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
        }

        return $telefone;
    }

    /**
     * Converte uma string de valor monetário para float
     * Aceita formatos como "R$ 1.234,56" ou "1234.56"
     *
     * @param string $str Valor como string
     * @return float Valor como float
     */
    public static function parseCurrency(string $str): float
    {
        $str = trim($str);
        // Remove qualquer caractere que não seja dígito, ponto, vírgula ou sinal
        $str = preg_replace('/[^0-9,\.\-]/u', '', $str);

        // Se houver separador de milhares (ponto) e separador decimal (vírgula)
        if (strpos($str, ',') !== false && strpos($str, '.') !== false) {
            $str = str_replace('.', '', $str);
            $str = str_replace(',', '.', $str);
        } elseif (strpos($str, ',') !== false && strpos($str, '.') === false) {
            // Assume vírgula como separador decimal
            $str = str_replace(',', '.', $str);
        }

        return is_numeric($str) ? (float) $str : 0.0;
    }

    /**
     * Formata um valor numérico para formato brasileiro (R$ 1.234,56)
     *
     * @param float $num Valor numérico
     * @return string Valor formatado
     */
    public static function formatCurrency(float $num): string
    {
        return 'R$ ' . number_format($num, 2, ',', '.');
    }

    /**
     * Formata uma data do formato ISO para formato brasileiro
     *
     * @param string $data Data no formato Y-m-d
     * @return string Data no formato d/m/Y
     */
    public static function formatDateBR(string $data): string
    {
        $dt = new \DateTime($data);
        return $dt->format('d/m/Y');
    }

    /**
     * Gera um nome de arquivo seguro baseado no contratante e data
     *
     * @param string $contratante Nome do contratante
     * @return string Nome do arquivo
     */
    public static function generateFilename(string $contratante): string
    {
        $dataAtual = date('d-m-Y');
        $contratanteLimpo = preg_replace('/[^a-zA-Z0-9\-_]/', '_', $contratante);
        return "{$contratanteLimpo}_{$dataAtual}.pdf";
    }
}
