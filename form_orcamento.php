<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulário de Orçamento - Caju Catering</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
            background-color: #f5f5f5;
        }
        .form-container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1 {
            color: #23413a;
            margin-bottom: 30px;
            text-align: center;
        }
        .form-section {
            margin-bottom: 30px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 4px;
        }
        .form-section h2 {
            color: #23413a;
            margin-bottom: 20px;
            font-size: 1.2em;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #333;
            font-weight: bold;
        }
        input[type="text"],
        input[type="number"],
        input[type="date"],
        input[type="time"],
        textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        input[type="checkbox"] {
            margin-right: 8px;
            transform: scale(1.2);
        }
        .menu-items {
            margin-bottom: 10px;
        }
        .add-item {
            background: #23413a;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 5px;
        }
        .submit-btn {
            background: #23413a;
            color: white;
            border: none;
            padding: 15px 30px;
            font-size: 18px;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            margin-top: 20px;
        }
        .submit-btn:hover {
            background: #1a2f2a;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Orçamento Caju Catering</h1>
        <form action="exportar_orcamento.php" method="POST">
            <!-- Informações do Evento -->
            <div class="form-section">
                <h2>Informações do Evento</h2>
                <div class="form-group">
                    <label for="evento_nome">Nome do Evento:</label>
                    <input type="text" id="evento_nome" name="evento_nome" required>
                </div>
                <div class="form-group">
                    <label for="evento_data">Data:</label>
                    <input type="date" id="evento_data" name="evento_data" required>
                </div>
                <div class="form-group">
                    <label for="quantidade_pessoas">Quantidade de Pessoas:</label>
                    <input type="number" id="quantidade_pessoas" name="quantidade_pessoas" required>
                </div>
                <div class="form-group">
                    <label for="evento_inicio">Horário de Início:</label>
                    <input type="time" id="evento_inicio" name="evento_inicio" required>
                </div>
                <div class="form-group">
                    <label for="evento_local">Local:</label>
                    <input type="text" id="evento_local" name="evento_local" required>
                </div>
            </div>

            <!-- Cardápio -->
            <div class="form-section">
                <h2>Cardápio</h2>
                
                <!-- Entradinhas -->
                <div class="form-group">
                    <label>Entradinhas Volantes:</label>
                    <div id="entradinhas-container" class="menu-items">
                        <input type="text" name="entradinhas[]" placeholder="Adicione uma entrada">
                    </div>
                    <button type="button" class="add-item" onclick="addMenuItem('entradinhas-container', 'entradinhas[]')">+ Adicionar Entrada</button>
                </div>

                <!-- Antepastos -->
                <div class="form-group">
                    <label>Antepastos:</label>
                    <div id="antepastos-container" class="menu-items">
                        <input type="text" name="antepastos[]" placeholder="Adicione um antepasto">
                    </div>
                    <button type="button" class="add-item" onclick="addMenuItem('antepastos-container', 'antepastos[]')">+ Adicionar Antepasto</button>
                </div>

                <!-- Almoço -->
                <div class="form-group">
                    <label>Almoço:</label>
                    <div id="almoco-container" class="menu-items">
                        <input type="text" name="almoco[]" placeholder="Adicione um item do almoço">
                    </div>
                    <button type="button" class="add-item" onclick="addMenuItem('almoco-container', 'almoco[]')">+ Adicionar Item</button>
                </div>
            </div>

            <!-- Serviços Adicionais -->
            <div class="form-section">
                <h2>Serviços Adicionais</h2>
                
                <!-- Garçom -->
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="garcom" value="1"> 
                        Garçom para servir bebidas
                    </label>
                </div>

                <!-- Ornamentação -->
                <div class="form-group">
                    <label>Ornamentação:</label>
                    <div id="ornamentacao-container" class="menu-items">
                        <input type="text" name="ornamentacao[]" placeholder="Adicione um item de ornamentação">
                    </div>
                    <button type="button" class="add-item" onclick="addMenuItem('ornamentacao-container', 'ornamentacao[]')">+ Adicionar Item</button>
                </div>

                <!-- Louças -->
                <div class="form-group">
                    <label for="loucas">Louças e material:</label>
                    <input type="text" id="loucas" name="loucas" placeholder="Ex: pratos, talheres e prataria">
                </div>

                <!-- Equipe -->
                <div class="form-group">
                    <label for="equipe">Equipe:</label>
                    <input type="text" id="equipe" name="equipe" placeholder="Ex: Uma copeira + dois garçons">
                </div>
            </div>

            <!-- Valores -->
            <div class="form-section">
                <h2>Valores</h2>
                <div class="form-group">
                    <label for="valor_por_pessoa">Valor por Pessoa:</label>
                    <input type="text" id="valor_por_pessoa" name="valor_por_pessoa" required>
                </div>
                <div class="form-group">
                    <label for="deslocamento">Deslocamento:</label>
                    <input type="text" id="deslocamento" name="deslocamento" value="a combinar" required>
                </div>
            </div>

            <!-- Forma de Pagamento -->
            <div class="form-section">
                <h2>Forma de Pagamento</h2>
                <div class="form-group">
                    <label for="forma_pagamento">Forma de Pagamento:</label>
                    <input type="text" id="forma_pagamento" name="forma_pagamento" value="Pagamento via pix" required>
                </div>
                <div class="form-group">
                    <label>Condições de Pagamento:</label>
                    <div id="condicoes-container" class="menu-items">
                        <input type="text" name="condicoes[]" value="50% no fechamento">
                        <input type="text" name="condicoes[]" value="50% até o dia do evento">
                    </div>
                    <button type="button" class="add-item" onclick="addMenuItem('condicoes-container', 'condicoes[]')">+ Adicionar Condição</button>
                </div>
                <div class="form-group">
                    <label for="observacao">Observação:</label>
                    <input type="text" id="observacao" name="observacao" value="Podendo nos adequar as condições da empresa">
                </div>
            </div>

            <button type="submit" class="submit-btn">Gerar Orçamento PDF</button>
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
    </script>
</body>
</html> 