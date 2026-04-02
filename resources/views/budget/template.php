<?php
$css = file_get_contents(__DIR__ . '/../../../public/css/pdf_style.css');
?>

<html>
<head>
    <style><?=$css?></style>
</head>
<body>
    <div class="page">
        <img src="/img/caju/img19.jpg" alt="Capa">
    </div>

    <div style="page-break-after: always;"></div>
    <div class="page">
        <img src="/img/caju/pag2.jpg" alt="Segunda página">
    </div>

    <div style="page-break-after: always;"></div>
    <div class="page">
        <img src="/img/caju/pag3.jpg" alt="Terceira página">
    </div>

    <div style="page-break-after: always;"></div>
    <div class="page pagina-orcamento">
        <div class="titulo-container">
            <div class="titulo-orcamento">
                Orça<br>mento
            </div>
            <div class="logo-caju">
                <img src="/img/umcaju.png" alt="Logo Caju" />
            </div>
        </div>
        <div class="evento-info">
            <div class="evento-nome"><?=$evento_nome?></div>
            <div class="evento-data">Data: <?=$evento_data?></div>
            <div class="evento-pessoas"><?=$evento_pessoas?></div>
            <div class="evento-inicio">Inicio às <span><?=$evento_inicio?></span></div>
            <div class="evento-local"><?=$evento_local?></div>
        </div>
    </div>

    <div style="page-break-after: always;"></div>
    <div class="page pagina-cardapio">
        <div class="titulo-cardapio">Mesa fixa - BUFFET ALMOÇO</div>

        <?php foreach ($menu as $secao): ?>
            <div class="secao-cardapio">
                <div class="secao-titulo"><?=$secao['titulo']?></div>
                <?php foreach ($secao['itens'] as $item): ?>
                    <div class="item-cardapio"><?=$item?></div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>

        <div class="servicos-lista">
        <?php foreach ($servicos as $servico): ?>
            <div class="servico-titulo">• <?=$servico['titulo']?></div>
            <?php foreach ($servico['itens'] as $item): ?>
                <div class="servico-item"><?=$item?></div>
            <?php endforeach; ?>
        <?php endforeach; ?>
        </div>
    </div>

    <div style="page-break-after: always;"></div>
    <div class="page pagina-valores">
        <div class="valores-container">
            <div class="titulo-valores">Valor do investimento</div>

            <div class="valor-principal">
                <div class="valor-pessoa">Buffet por pessoa</div>
                <div class="valor-destaque"><?=$valor_por_pessoa?></div>
            </div>

            <div class="valor-total">
                <div class="total-texto">valor para <?=$quantidade_pessoas?> pessoas</div>
                <div class="total-valor">R$ <?=$valor_total?></div>
            </div>

            <div class="deslocamento">
                Deslocamento <?=$deslocamento?>
            </div>

            <div class="pagamento">
                <div class="forma-pagamento"><?=$forma_pagamento?></div>
                <?php foreach ($condicoes as $condicao): ?>
                    <div class="condicao-pagamento"><?=$condicao?></div>
                <?php endforeach; ?>
            </div>

            <div class="observacao">
                <?=$observacao?>
            </div>
        </div>
    </div>

    <div style="page-break-after: always;"></div>
    <div class="page">
        <img src="/img/caju/pag7.jpg" alt="Sétima página">
    </div>

    <div style="page-break-after: always;"></div>
    <div class="page">
        <img src="/img/caju/pag8.jpg" alt="Oitava página">
    </div>

    <div style="page-break-after: always;"></div>
    <div class="page">
        <img src="/img/caju/pag9.jpg" alt="Nona página">
    </div>
</body>
</html>
