<h1>Checklist de Materiais</h1>
<img src="/img/caju.png" class="logo" alt="Logo Caju" style="width:60px; height:auto;">

<div class="info-section">
    <h2>Informações do Evento</h2>
    <table class="info-table">
        <tr>
            <td><strong>Cliente:</strong></td>
            <td><?= htmlspecialchars($cliente) ?></td>
            <td><strong>Data:</strong></td>
            <td><?= $data ?></td>
        </tr>
        <tr>
            <td><strong>Local:</strong></td>
            <td colspan="3"><?= htmlspecialchars($local) ?></td>
        </tr>
    </table>
</div>

<div class="info-section">
    <h2>Equipe e Fardamento</h2>
    <p><strong>Equipe:</strong><br><?= nl2br(htmlspecialchars($equipe)) ?></p>
    <p><strong>Fardamento:</strong><br><?= nl2br(htmlspecialchars($fardamento)) ?></p>
</div>

<div class="info-section">
    <h2>Cardápio</h2>
    <p><?= nl2br(htmlspecialchars($cardapio)) ?></p>
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
        <tbody>
            <?php foreach ($materiais as $material): ?>
                <tr>
                    <td><?= htmlspecialchars($material['nome']) ?></td>
                    <td><?= htmlspecialchars($material['quantidade']) ?></td>
                    <td class="checkbox">☐</td>
                    <td class="checkbox">☐</td>
                    <td class="obs"></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="signatures">
    <div class="signature-field">
        <div class="line">____________________________</div>
        <div class="label">Responsável pela Retirada</div>
    </div>
    <br><br><br>
    <div class="signature-field">
        <div class="line">____________________________</div>
        <div class="label">Responsável pela Devolução</div>
    </div>
</div>
