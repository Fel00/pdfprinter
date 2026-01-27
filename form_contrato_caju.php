<?php include 'header.php'; ?>
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/forms.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/4.0.9/jquery.inputmask.bundle.min.js"></script>
    <script src="js/mascaras.js"></script>
    <script src="js/calculo.js"></script>
</head>
<body>
    <div class="form-container">
        <h1>Contrato Caju Catering</h1>
        <form id="formulario" action="gerar_pdf_caju.php" method="post">
            <div class="form-section">
                <h2>Informações do Contratante</h2>
                <div class="form-group">
                    <label for="contratante">Contratante:</label>
                    <input type="text" id="contratante" name="contratante" required>
                </div>
                <div class="form-group">
                    <label>
                        <input type="checkbox" id="useCnpj" name="useCnpj">
                        Usar CNPJ
                    </label>
                </div>
                <div class="form-group">
                    <label for="cpf" id="cpfLabel">CPF:</label>
                    <input type="text" id="cpf" name="cpf" required>
                </div>
                <div class="form-group">
                    <label for="telefone">Telefone:</label>
                    <input type="text" id="telefone" name="telefone" required>
                </div>
            </div>

            <div class="form-section">
                <h2>Informações do Evento</h2>
                <div class="form-group">
                    <label for="endereco">Endereço Do evento:</label>
                    <input type="text" id="endereco" name="endereco" required>
                </div>
                <div class="form-group">
                    <label for="quantidade_pessoas">Quantidade de Pessoas:</label>
                    <input type="number" id="quantidade_pessoas" name="quantidade_pessoas" required>
                </div>
                <div class="form-group">
                    <label for="tipo_bufet">Tipo de Bufê:</label>
                    <select id="tipo_bufet" name="tipo_bufet" required>
                        <option value="" disabled selected>Selecione uma opção</option>
                        <option value="ALMOÇO INTIMISTA">ALMOÇO INTIMISTA</option>
                        <option value="JANTAR INTIMISTA">JANTAR INTIMISTA</option>
                        <option value="COCKTAIL">COCKTAIL</option>
                        <option value="BRUNCH">BRUNCH</option>
                        <option value="Caju Personalizada">Caju Personalizada</option>
                    </select>
                </div>
                <div id="descricao_bufet_container" style="display: none;">
                    <div class="form-group">
                        <label for="descricao_bufet">Descrição do Bufê:</label>
                        <textarea id="descricao_bufet" name="descricao_bufet" placeholder="Descreva sua Caju Personalizada"></textarea>
                    </div>
                </div>
            </div>

            <!-- Cardápio -->
            <div class="form-section">
                <h2>Cardápio</h2>
                
                <!-- Mesa Fixa -->
                <div class="form-group">
                    <label>Mesa Fixa:</label>
                    <div id="mesa_fixa-container" class="menu-items">
                        <input type="text" name="mesa_fixa[]" placeholder="Adicione um item da mesa fixa">
                    </div>
                    <button type="button" class="add-item" onclick="addMenuItem('mesa_fixa-container', 'mesa_fixa[]')">+ Adicionar Item</button>
                </div>

                <!-- Volantes -->
                <div class="form-group">
                    <label>Volantes:</label>
                    <div id="volantes-container" class="menu-items">
                        <input type="text" name="volantes[]" placeholder="Adicione um item volante">
                    </div>
                    <button type="button" class="add-item" onclick="addMenuItem('volantes-container', 'volantes[]')">+ Adicionar Item</button>
                </div>

                <!-- Bebidas -->
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="bebidas" value="1"> 
                        Bebidas não alcoólicas
                    </label>
                </div>

                <!-- Louças -->
                <div class="form-group">
                    <label for="loucas">Louças e material:</label>
                    <input type="text" id="loucas" name="loucas" placeholder="Ex: Pratos, talheres e guardanapos">
                </div>

                <!-- Equipe -->
                <div class="form-group">
                    <label for="equipe">Equipe:</label>
                    <input type="text" id="equipe" name="equipe" placeholder="Ex: 2 copeiras e 2 garçons">
                </div>
            </div>

            <div class="form-section">
                <h2>Data e Horários</h2>
                <div class="form-group">
                    <label for="data">Data do evento:</label>
                    <input type="date" id="data" name="data" required>
                </div>
                <div class="horarios">
                    <div class="form-group">
                        <label for="horario_inicio">Horário de Início:</label>
                        <input type="time" id="horario_inicio" name="horario_inicio" required>
                    </div>
                    <div class="form-group">
                        <label for="horario_conclusao">Horário de Conclusão:</label>
                        <input type="time" id="horario_conclusao" name="horario_conclusao" required>
                    </div>
                    <div class="form-group">
                        <label for="horario_chegada">Horário de Chegada:</label>
                        <input type="time" id="horario_chegada" name="horario_chegada" required>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h2>Valores</h2>
                <div class="form-group">
                    <label for="valor_bufet">Valor do Bufê:</label>
                    <input type="text" id="valor_bufet" name="valor_bufet" required>
                </div>
                <div class="form-group">
                    <label for="valor_deslocamento">Valor do Deslocamento:</label>
                    <input type="text" id="valor_deslocamento" name="valor_deslocamento" required>
                </div>
                <div class="form-group">
                    <label for="valor_total">Valor Total:</label>
                    <input type="text" id="valor_total" name="valor_total" readonly>
                </div>
            </div>

            <button type="submit" class="submit-btn">Gerar Contrato</button>
        </form>
    </div>

    <script>
        function addMenuItem(containerId, inputName) {
            const container = document.getElementById(containerId);
            const input = document.createElement('input');
            input.type = 'text';
            input.name = inputName;
            input.placeholder = 'Adicione um item';
            container.appendChild(input);
        }

        document.getElementById('tipo_bufet').addEventListener('change', function() {
            var descricaoContainer = document.getElementById('descricao_bufet_container');
            descricaoContainer.style.display = this.value === 'Caju Personalizada' ? 'block' : 'none';
        });
    </script>
</body>
</html>
