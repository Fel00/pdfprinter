<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Caju Catering - Sistema</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background-color: #f5f5f5;
            padding: 20px;
        }
        .menu-container {
            background-color: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 600px;
            width: 100%;
        }
        h1 {
            color: #23413a;
            margin-bottom: 30px;
            font-size: 2em;
        }
        .menu-buttons {
            display: grid;
            gap: 20px;
            margin-top: 30px;
        }
        .menu-btn {
            background: #23413a;
            color: white;
            border: none;
            padding: 20px;
            font-size: 1.2em;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .menu-btn:hover {
            background: #1a2f2a;
        }
        .menu-btn.test {
            background: #ffb347;
            color: #23413a;
        }
        .menu-btn.test:hover {
            background: #ffa321;
        }
    </style>
</head>
<body>
    <div class="menu-container">
        <h1>Caju Catering</h1>
        <div class="menu-buttons">
            <a href="form_orcamento.php" class="menu-btn">Gerar Orçamento - Caju</a>
            <a href="form_contrato_caju.php" class="menu-btn">Gerar Contrato - Caju</a>
            <a href="form_contrato.php" class="menu-btn">Gerar Contrato - Feiju</a>
        </div>
    </div>
</body>
</html>