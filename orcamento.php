<?php

$css = file_get_contents('css/pdf_style.css');

$html = '
<html>
<head>
    <style>' . $css . '</style>
</head>
<body>
    <div class="page">
        <img src="img/caju/img19.jpg" alt="Capa">
    </div>
    <pagebreak />
    <div class="page">
        <img src="img/caju/pag2.jpg" alt="Segunda página">
    </div>
    <pagebreak />
    <div class="page">
        <img src="img/caju/pag3.jpg" alt="Terceira página">
    </div>
    <pagebreak />
    <div class="page pagina-orcamento">
        <div class="titulo-container">
            <div class="titulo-orcamento">
                Orça<br>mento
            </div>
            <div class="logo-caju">
                <img src="img/umcaju.png" alt="Logo Caju" />
            </div>
        </div>
        <div class="evento-info">
            <div class="evento-nome">' . $evento_nome . '</div>
            <div class="evento-data">Data: ' . $evento_data . '</div>
            <div class="evento-pessoas">' . $evento_pessoas . '</div>
            <div class="evento-inicio">Inicio às <span>' . $evento_inicio . '</span></div>
            <div class="evento-local">' . $evento_local . '</div>
        </div>
    </div>
    <pagebreak />
    <div class="page pagina-cardapio">
        <div class="titulo-cardapio">Mesa fixa - BUFFET ALMOÇO</div>
        
        ' . implode("\n", array_map(function($secao, $key) {
            $html = '<div class="secao-cardapio">';
            $html .= '<div class="secao-titulo">' . $secao['titulo'] . '</div>';
            $html .= implode("\n", array_map(function($item) {
                return '<div class="item-cardapio">' . $item . '</div>';
            }, $secao['itens']));
            $html .= '</div>';
            return $html;
        }, $menu, array_keys($menu))) . '

        <div class="servicos-lista">
        ' . implode("\n", array_map(function($servico) {
            $html = '<div class="servico-titulo">• ' . $servico['titulo'] . '</div>';
            if (!empty($servico['itens'])) {
                $html .= implode("\n", array_map(function($item) {
                    return '<div class="servico-item">' . $item . '</div>';
                }, $servico['itens']));
            }
            return $html;
        }, $servicos)) . '
        </div>
    </div>
    <pagebreak />
    <div class="page pagina-valores">
        <div class="valores-container">
            <div class="titulo-valores">Valor do investimento</div>
            
            <div class="valor-principal">
                <div class="valor-pessoa">Buffet por pessoa</div>
                <div class="valor-destaque">' . $valor_por_pessoa . '</div>
            </div>

            <div class="valor-total">
                <div class="total-texto">valor para ' . $quantidade_pessoas . ' pessoas</div>
                <div class="total-valor">R$ ' . $valor_total . '</div>
            </div>

            <div class="deslocamento">
                Deslocamento ' . $deslocamento . '
            </div>

            <div class="pagamento">
                <div class="forma-pagamento">' . $forma_pagamento . '</div>
                ' . implode("<br>", array_map(function($condicao) {
                    return '<div class="condicao-pagamento">' . $condicao . '</div>';
                }, $condicoes)) . '
            </div>

            <div class="observacao">
                ' . $observacao . '
            </div>
        </div>
    </div>
    <pagebreak />
    <div class="page">
        <img src="img/caju/pag7.jpg" alt="Sétima página">
    </div>
    <pagebreak />
    <div class="page">
        <img src="img/caju/pag8.jpg" alt="Oitava página">
    </div>
    <pagebreak />
    <div class="page">
        <img src="img/caju/pag9.jpg" alt="Nona página">
    </div>
</body>
</html>';