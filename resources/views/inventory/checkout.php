<?php
$extraJs = ['mascaras.js'];
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/4.0.9/jquery.inputmask.bundle.min.js"></script>

<div class="form-container">
    <h1>Baixa de Inventário</h1>
    <form id="formulario" action="/inventory/checkout/generate" method="post">
        <!-- Informações Básicas -->
        <div class="form-section">
            <h2>Informações do Evento</h2>

            <div class="form-group">
                <label for="cliente">Nome do Cliente:</label>
                <input type="text" id="cliente" name="cliente" required>
            </div>

            <div class="form-group">
                <label for="data">Data do Evento:</label>
                <input type="date" id="data" name="data" required>
            </div>

            <div class="form-group">
                <label for="local">Local do Evento:</label>
                <input type="text" id="local" name="local" required>
            </div>
        </div>

        <!-- Equipe e Fardamento -->
        <div class="form-section">
            <h2>Equipe e Fardamento</h2>

            <div class="form-group">
                <label for="equipe">Equipe:</label>
                <textarea id="equipe" name="equipe" placeholder="Liste os membros da equipe" required></textarea>
            </div>

            <div class="form-group">
                <label for="fardamento">Fardamento:</label>
                <textarea id="fardamento" name="fardamento" placeholder="Descrição do fardamento da equipe" required></textarea>
            </div>
        </div>

        <!-- Cardápio -->
        <div class="form-section">
            <h2>Cardápio</h2>

            <div class="form-group">
                <label for="cardapio">Descrição do Cardápio:</label>
                <textarea id="cardapio" name="cardapio" placeholder="Descreva o cardápio do evento" required></textarea>
            </div>
        </div>

        <!-- Materiais -->
        <div class="form-section">
            <h2>Materiais</h2>

            <div id="materiais-container">
                <!-- Lista dinâmica de materiais será inserida aqui via JavaScript -->
            </div>

            <button type="button" class="add-item" id="adicionar-material">+ Adicionar Material</button>
        </div>

        <button type="submit" class="submit-btn">Gerar Checklist PDF</button>
    </form>
</div>

<script>
    // Carregar o catálogo de itens ao iniciar a página
    let catalogoItens = <?= json_encode($items) ?>;
    let materiaisSelecionados = new Set();

    document.getElementById('adicionar-material').addEventListener('click', adicionarMaterial);

    function adicionarMaterial() {
        const container = document.getElementById('materiais-container');
        const materialDiv = document.createElement('div');
        materialDiv.className = 'material-item';

        const itensFiltrados = catalogoItens.filter(item => !materiaisSelecionados.has(item.id));

        if (itensFiltrados.length === 0) {
            alert('Todos os itens já foram selecionados!');
            return;
        }

        const html = `
            <div class="material-info">
                <select class="material-select" name="material_id[]" required onchange="atualizarMaterial(this)">
                    <option value="">Selecione um material</option>
                    ${itensFiltrados.map(item => `
                        <option value="${item.id}"
                                data-max="${item.quantidade}"
                                data-imagem="${item.imagem || ''}"
                                data-nome="${item.nome}">
                            ${item.nome} (Max: ${item.quantidade})
                        </option>
                    `).join('')}
                </select>
                <div class="quantidade-container">
                    <input type="number" class="material-quantity" name="material_quantidade[]"
                           min="1" placeholder="Quantidade" required>
                    <span class="quantidade-error"></span>
                </div>
            </div>
            <img class="material-image" style="display: none;">
            <button type="button" class="material-remove" onclick="removerMaterial(this)">Remover</button>
        `;

        materialDiv.innerHTML = html;
        container.appendChild(materialDiv);
    }

    function removerMaterial(button) {
        const materialItem = button.closest('.material-item');
        const select = materialItem.querySelector('select');
        const materialId = select.value;

        if (materialId) {
            materiaisSelecionados.delete(materialId);
        }

        materialItem.remove();
    }

    function atualizarMaterial(select) {
        const materialItem = select.closest('.material-item');
        const quantidadeInput = materialItem.querySelector('.material-quantity');
        const imagemElement = materialItem.querySelector('.material-image');
        const errorSpan = materialItem.querySelector('.quantidade-error');

        // Remover o ID anterior dos selecionados (se houver)
        const oldValue = select.dataset.lastValue;
        if (oldValue) {
            materiaisSelecionados.delete(oldValue);
        }

        const selectedOption = select.selectedOptions[0];
        const materialId = select.value;

        if (materialId) {
            materiaisSelecionados.add(materialId);
            select.dataset.lastValue = materialId;

            const maxQuantidade = parseInt(selectedOption.dataset.max);
            quantidadeInput.max = maxQuantidade;

            // Atualizar imagem
            const imagem = selectedOption.dataset.imagem;
            if (imagem) {
                imagemElement.src = '/dados/imagens/' + imagem;
                imagemElement.style.display = 'block';
            } else {
                imagemElement.style.display = 'none';
            }

            // Configurar validação de quantidade
            quantidadeInput.oninput = function() {
                const valor = parseInt(this.value);
                if (valor > maxQuantidade) {
                    errorSpan.textContent = `Quantidade máxima disponível: ${maxQuantidade}`;
                    this.value = maxQuantidade;
                } else {
                    errorSpan.textContent = '';
                }
            };
        } else {
            imagemElement.style.display = 'none';
            quantidadeInput.value = '';
            errorSpan.textContent = '';
        }
    }

    // Validação do formulário antes do envio
    document.getElementById('formulario').onsubmit = function(e) {
        const materiais = document.querySelectorAll('.material-item');
        let isValid = true;

        materiais.forEach(material => {
            const select = material.querySelector('select');
            const quantidade = material.querySelector('.material-quantity');
            const error = material.querySelector('.quantidade-error');

            if (select.value && quantidade.value) {
                const max = parseInt(select.selectedOptions[0].dataset.max);
                const valor = parseInt(quantidade.value);

                if (valor > max) {
                    error.textContent = `Quantidade máxima disponível: ${max}`;
                    isValid = false;
                }
            }
        });

        if (!isValid) {
            e.preventDefault();
            alert('Por favor, corrija as quantidades que excedem o limite disponível.');
        }

        return isValid;
    };
</script>
