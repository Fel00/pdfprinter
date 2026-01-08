<?php

$html = "


    <h1>CONTRATO DE PRESTAÇÃO DE SERVIÇOS DE FORNECIMENTO DE BUFFET</h1>
    <br>
    <p><strong>CONTRATANTE: $contratante</strong> , inscrita no CPF sob o nº $cpf, e nº de telefone $telefoneCensurado. O evento realizar-se-á no Endereço: $endereco.</p>
    <p><strong>CONTRATADA: $contratadaNome</strong> , pessoa jurídica de direito privado, inscrita no CNPJ sob n. $cnpj, com sede na $contratadaEndereco, neste ato representada pela sócia $representante.</p>
    
    <p>As partes acima identificadas têm, entre si, justo e acertado o presente Contrato de Prestação de Serviços de Fornecimento de Buffet, que se regerá pelas cláusulas seguintes e pelas condições de preço, forma e termo de pagamento descritas no presente.</p>
    <br>
    
    <h2>DO OBJETO DO CONTRATO</h2>
    <p><strong>Cláusula 1ª.</strong> É objeto do presente contrato a prestação pela CONTRATADA à CONTRATANTE dos serviços de fornecimento de Buffet para $quantidade_pessoas pessoas.</p>
    <br>
    
    <p><strong>$tipoBufet</strong></p>
    
    <p><strong>Mesa fixa – $tipoBufet</strong></p>
    " . implode("\n", array_map(function($item) {
        return "<p>$item</p>";
    }, $mesa_fixa)) . "
    
    <p><strong>Volantes</strong></p>
    " . implode("\n", array_map(function($item) {
        return "<p>$item</p>";
    }, $volantes)) . "
    
    " . ($bebidas ? "<p>• Bebidas não alcoólicas</p>" : "") . "
    
    " . (!empty($loucas) ? "<p>• Louças e material para realizarmos o serviço</p><p>$loucas</p>" : "") . "
    
    " . (!empty($equipe) ? "<p>• Equipe</p><p>$equipe</p>" : "") . "
    <br>
    
    <h2>DO EVENTO</h2>
    <p><strong>Cláusula 2ª.</strong> O evento, para cuja realização são contratados os serviços, se realizará na data de $data, com início às $horarioInicio H, no endereço constante no preâmbulo.</p>
    <br>
    
    <h2>OBRIGAÇÕES DA CONTRATANTE</h2>
    <p><strong>Cláusula 3ª.</strong> A CONTRATANTE deverá fornecer à CONTRATADA todas as informações necessárias à realização adequada do serviço de fornecimento de buffet, devendo especificar os detalhes do evento, necessários ao perfeito fornecimento do serviço, e a forma como este deverá ser prestado.</p>
    <p><strong>Cláusula 4ª.</strong> A CONTRATANTE deverá efetuar o pagamento na forma e condições estabelecidas na cláusula 10ª.</p>
    <br>
    
    <h2>OBRIGAÇÕES DA CONTRATADA</h2>
    <p><strong>Cláusula 5ª.</strong> É dever da CONTRATADA oferecer um serviço de fornecimento de buffet de acordo com as especificações da CONTRATANTE, devendo o serviço iniciar-se às $horarioInicio H até às $horarioConclusao H.</p>
    <br>
    
    <p><strong>Parágrafo único.</strong> A CONTRATADA está obrigada a fornecer aos convidados dos CONTRATANTES produtos de alta qualidade, que deverão ser preparados e servidos dentro de rigorosas normas de higiene e limpeza.</p>
    
    <p><strong>Cláusula 6ª.</strong> A CONTRATADA será responsável pela organização e disposição em utensílios próprios.</p>
    
    <p><strong>Cláusula 7ª.</strong> A CONTRATADA compromete-se a chegar no local do evento às $horarioChegada H, a fim de arrumar os utensílios do serviço.</p>
    
    <p><strong>Cláusula 8ª.</strong> A CONTRATADA será a única e exclusiva responsável por todos os seus empregados que trabalharem no evento, cabendo a ela o cumprimento das obrigações sociais, trabalhistas, previdenciárias, tributárias, entre outras, referentes à prestação dos serviços ora contratados.</p>
    
    <p><strong>Cláusula 9ª.</strong> A CONTRATADA irá retirar todo o material utilizado, fornecido por ela, logo após o evento.</p>
    <br>
    
    <h2>DO PREÇO E DAS CONDIÇÕES DE PAGAMENTO</h2>
    <p><strong>Cláusula 10ª.</strong> O serviço contratado no presente instrumento será remunerado pela quantia de $valor_bufet pelo valor do bufet, mais $valor_deslocamento pelo valor do deslocamento, totalizando uma quantia de $valor_total, devendo ser paga a quantia correspondente à 50% (cinquenta por cento) do valor no ato da assinatura do presente instrumento e o restante até o dia do evento.</p>
    
    <p><strong>Parágrafo único.</strong> O pagamento deverá ocorrer através de transferência bancária, na seguinte conta:</p>
    <p><strong>" . getConfigCaju('banco') . "<br>
    AG " . getConfigCaju('agencia') . "<br>
    CONTA " . getConfigCaju('conta') . "</strong></p>
    <p><strong>PIX: " . getConfigCaju('pix') . "</strong></p>
    <p><strong>$representante<br>
    CNPJ: $cnpj</strong></p>
    <br>
    
    <h2>DO INADIMPLEMENTO</h2>
    <p><strong>Cláusula 11ª.</strong> Em caso de inadimplemento por parte do CONTRATANTE quanto ao pagamento do serviço prestado, deverá incidir sobre o valor do presente instrumento, multa pecuniária de 2%, juros de mora de 1% ao mês e correção monetária.</p>
    
    <p><strong>Parágrafo único.</strong> Em caso de cobrança judicial, devem ser acrescidas custas processuais e 20% de honorários advocatícios.</p>
    <br>
    
    <h2>DA DEVOLUÇÃO</h2>
    <p><strong>Cláusula 12ª.</strong> Todos os utensílios e objetos fornecidos pela CONTRATADA, deverão ser devolvidos em perfeito estado de conservação, sob pena da CONTRATANTE arcar com os respectivos valores de reposição.</p>
    
    <p><strong>Parágrafo único.</strong> Os valores de reposição deverão ser pagos até um dia após a realização do evento, sob pena de incidir a aplicação da multa, dos juros e da correção monetária, previstos na cláusula anterior.</p>
    <br>
    
    <h2>DA RESCISÃO</h2>
    <p><strong>Cláusula 13ª.</strong> O presente contrato poderá ser rescindido unilateralmente pela CONTRATANTE, estando ciente de que o valor de 50% (cinquenta por cento) pago será retido pela CONTRATADA, tendo em vista a reserva da data e os prejuízos arcados em decorrência disso.</p>
    
    <p><strong>Parágrafo único.</strong> A CONTRATANTE poderá remarcar o evento, com antecedência mínima de 15 (quinze) dias, de acordo com a disponibilidade da CONTRATADA.</p>
    <br>
    
    <h2>DAS MULTAS CONTRATUAIS</h2>
    <p><strong>Cláusula 14ª.</strong> Salvo o caso de rescisão já previsto na cláusula imediatamente anterior, fica estabelecido que a parte infratora a quaisquer cláusulas do presente contrato, pagará à parte prejudicada multa equivalente a 20% (vinte por cento) sobre o valor do contrato, independente de ação judicial específica para ressarcimento de perdas e danos que poderá ser movida pela parte prejudicada.</p>
    <br>
    
    <h2>DO FORO</h2>
    <p><strong>Cláusula 15ª.</strong> Para dirimir quaisquer controvérsias oriundas do presente contrato, as partes elegem o foro da comarca de Fortaleza, Ceará.</p>
    <br>
    
    <p>Por estarem assim justos e contratados, firmam o presente instrumento, em duas vias de igual teor, juntamente com 2 (duas) testemunhas.</p>
    
    <p>Fortaleza, $data.</p>

    <div style='text-align: center; margin-top: 200px;'>
        <p>_____________________________________</p>
        <p><strong> $contratante </strong></p>
        
        <br><br>
        
        <p>______________________________________</p>
        <p><strong>$contratadaNome</strong></p>
        
        <br><br>
        
        <p>______________________________________</p>
        <p>(Nome, RG e assinatura da Testemunha 1)</p>
        
        <br><br>
        
        <p>______________________________________</p>
        <p>(Nome, RG e assinatura da Testemunha 2)</p>
    </div>

    <div style='text-align: center; margin-top: 50px;'>
        <img src='" . getConfigCaju('logo') . "' alt='Logo Caju' style='width: 200px; height: auto;'>
    </div>
    
";
