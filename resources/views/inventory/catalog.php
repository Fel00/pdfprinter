<?php
$extraJs = ['mascaras.js'];

// Verifica mensagens
$success = $_GET['success'] ?? null;
$error = $_GET['error'] ?? null;
?>

<style>
    .form-box {
        max-width: 1000px;
        margin: 30px auto;
        background: #fff;
        padding: 20px;
        border-radius: 6px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .search-box {
        padding: 10px;
        margin-bottom: 20px;
        width: 100%;
        border: 2px solid #23413a;
        border-radius: 4px;
        font-size: 16px;
    }

    .item-list {
        list-style: none;
        padding: 0;
    }

    .item-card {
        border: 1px solid #ddd;
        padding: 15px;
        margin-bottom: 15px;
        border-radius: 6px;
        display: flex;
        align-items: start;
        gap: 20px;
    }

    .item-image {
        flex: 0 0 200px;
    }

    .item-details {
        flex: 1;
    }

    .item-actions {
        display: flex;
        gap: 10px;
        margin-top: 10px;
    }

    .btn-edit,
    .btn-delete {
        padding: 6px 12px;
        border-radius: 4px;
        border: none;
        cursor: pointer;
    }

    .btn-edit {
        background: #23413a;
        color: white;
    }

    .btn-delete {
        background: #dc3545;
        color: white;
    }

    .quantity-edit {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 10px;
    }

    .quantity-edit input {
        width: 80px;
        text-align: center;
    }

    .no-items {
        text-align: center;
        color: #666;
        padding: 20px;
    }

    .highlight {
        background-color: #fff3cd;
    }

    .thumb {
        max-width: 150px;
        margin-top: 8px;
    }
</style>

<?php if ($error): ?>
    <div class="msg-error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<?php if ($success): ?>
    <div class="msg-success">Item adicionado com sucesso!</div>
<?php endif; ?>

<div class="form-container">
    <h2>Catálogo - Adicionar Material</h2>
    <form method="post" enctype="multipart/form-data">
        <div class="form-section">
            <label for="nome">Nome</label>
            <input id="nome" name="nome" type="text" value="<?= $_POST['nome'] ?? '' ?>" required>

            <label for="descricao">Descrição</label>
            <textarea id="descricao" name="descricao" rows="4"><?= $_POST['descricao'] ?? '' ?></textarea>

            <label for="quantidade">Quantidade disponível</label>
            <input id="quantidade" name="quantidade" type="number" min="0" value="<?= $_POST['quantidade'] ?? 0 ?>">

            <label for="imagem">Imagem (JPG/PNG/GIF, max 2MB)</label>
            <input id="imagem" name="imagem" type="file" accept="image/*">

            <button class="submit-btn" type="submit">Salvar</button>
            <p style="margin-top:12px;"><a href="/inventory">Voltar ao Inventário</a></p>
        </div>
    </form>
</div>

<div class="form-box">
    <h3>Itens do Catálogo</h3>
    <input type="text" class="search-box" placeholder="Pesquisar itens..." id="searchBox">

    <div id="itemsList">
        <?php if (empty($items)): ?>
            <p class="no-items">Nenhum item cadastrado.</p>
        <?php else: ?>
            <div class="item-list">
                <?php foreach ($items as $it): ?>
                    <div class="item-card" data-id="<?= htmlspecialchars($it['id']) ?>" data-nome="<?= htmlspecialchars($it['nome']) ?>">
                        <div class="item-image">
                            <?php if (!empty($it['imagem'])): ?>
                                <img class="thumb" src="/dados/imagens/<?= rawurlencode($it['imagem']) ?>" alt="<?= htmlspecialchars($it['nome']) ?>">
                            <?php endif; ?>
                        </div>

                        <div class="item-details">
                            <h4><?= htmlspecialchars($it['nome']) ?></h4>

                            <div class="quantity-edit">
                                <label>Quantidade:</label>
                                <input type="number" min="0" value="<?= htmlspecialchars($it['quantidade']) ?>"
                                       onchange="updateQuantidade('<?= htmlspecialchars($it['id']) ?>', this.value)">
                            </div>

                            <?php if (!empty($it['descricao'])): ?>
                                <p><?= nl2br(htmlspecialchars($it['descricao'])) ?></p>
                            <?php endif; ?>

                            <div class="item-actions">
                                <button class="btn-delete" onclick="excluirItem('<?= htmlspecialchars($it['id']) ?>')">Excluir</button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    // Pesquisa em tempo real
    document.getElementById('searchBox').addEventListener('input', function (e) {
        const searchTerm = e.target.value.toLowerCase();
        const items = document.querySelectorAll('.item-card');

        items.forEach(item => {
            const nome = item.getAttribute('data-nome').toLowerCase();
            const matches = nome.includes(searchTerm);

            item.style.display = matches ? '' : 'none';
            item.classList.remove('highlight');

            if (searchTerm && matches) {
                item.classList.add('highlight');
            }
        });
    });

    // Atualizar quantidade
    function updateQuantidade(id, novaQuantidade) {
        if (novaQuantidade < 0) return;

        fetch('/inventory/catalog', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'action=update_quantidade&id=' + encodeURIComponent(id) + '&quantidade=' + encodeURIComponent(novaQuantidade)
        })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert('Erro ao atualizar quantidade: ' + data.error);
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                alert('Erro ao atualizar quantidade');
            });
    }

    // Excluir item
    function excluirItem(id) {
        if (!confirm('Tem certeza que deseja excluir este item?')) return;

        fetch('/inventory/catalog', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'action=delete_item&id=' + encodeURIComponent(id)
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const item = document.querySelector(`.item-card[data-id="${id}"]`);
                    item.remove();
                    if (document.querySelectorAll('.item-card').length === 0) {
                        document.getElementById('itemsList').innerHTML = '<p class="no-items">Nenhum item cadastrado.</p>';
                    }
                } else {
                    alert('Erro ao excluir item: ' + (data.error || 'Erro desconhecido'));
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                alert('Erro ao excluir item');
            });
    }
</script>
