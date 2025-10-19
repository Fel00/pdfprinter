<?php
// Página principal do Inventário com submenu
?>
<?php include 'header.php'; ?>
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
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
        <h1>Inventário</h1>
        <div class="menu-buttons">
            <a href="inventario_catalogo.php" class="menu-btn">Catálogo</a>
            <a href="inventario_baixa.php" class="menu-btn">Baixa</a>
            <a href="index.php" class="menu-btn test">Voltar ao Menu Principal</a>
        </div>
    </div>
</body>
</html>
