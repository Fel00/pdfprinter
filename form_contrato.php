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
        <h1>Contrato Feiju</h1>
        <form id="formulario" action="gerar_pdf.php" method="post">
            <div class="form-section">
                <h2>Informações do Contratante</h2>
                <div class="form-group">
                    <label for="contratante">Contratante:</label>
                    <input type="text" id="contratante" name="contratante" required>
                </div>
                <div class="form-group">
                    <label for="cpf">CPF:</label>
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
                    <label for="tipo_bufet">Tipo de Bufê:</label>
                    <select id="tipo_bufet" name="tipo_bufet" required>
                        <option value="" disabled selected>Selecione uma opção</option>
                        <option value="Basiquinha">Basiquinha</option>
                        <option value="Completinha Com Bebidas">Completinha Com Bebidas</option>
                        <option value="Completinha Sem Bebidas">Completinha Sem Bebidas</option>
                        <option value="Feiju Personalizada">Feiju Personalizada</option>
                    </select>
                </div>
                <div id="descricao_bufet_container" style="display: none;">
                    <div class="form-group">
                        <label for="descricao_bufet">Descrição do Bufê:</label>
                        <textarea id="descricao_bufet" name="descricao_bufet" placeholder="Descreva sua Feiju Personalizada"></textarea>
                    </div>
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
                    <label for="valor_bufet">Valor do Bufet:</label>
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
        document.getElementById('tipo_bufet').addEventListener('change', function() {
            var descricaoContainer = document.getElementById('descricao_bufet_container');
            descricaoContainer.style.display = this.value === 'Feiju Personalizada' ? 'block' : 'none';
        });
    </script>
</body>
</html> 