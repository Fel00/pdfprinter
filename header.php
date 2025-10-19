<?php
// Pega o nome do arquivo atual para destacar o item ativo no menu
$current_page = basename($_SERVER['PHP_SELF']);

// Define a estrutura de navegação
$menu = [
    'Home' => [
        'url' => 'index.php',
        'icon' => '🏠'
    ],
    'Orçamentos' => [
        'url' => 'form_orcamento.php',
        'icon' => '📋',
        'submenu' => [
            'Gerar Orçamento - Caju' => 'form_orcamento.php'
        ]
    ],
    'Contratos' => [
        'url' => '#',
        'icon' => '📝',
        'submenu' => [
            'Gerar Contrato - Caju' => 'form_contrato_caju.php',
            'Gerar Contrato - Feiju' => 'form_contrato.php'
        ]
    ],
    'Inventário' => [
        'url' => 'inventario.php',
        'icon' => '📦',
        'submenu' => [
            'Catálogo' => 'inventario_catalogo.php',
            'Baixa' => 'inventario_baixa.php'
        ]
    ]
];

// Função para verificar se o item ou qualquer subitem está ativo
function is_active($page, $current_page, $menu_item) {
    if ($page === $current_page) return true;
    if (isset($menu_item['submenu'])) {
        return in_array($current_page, $menu_item['submenu']);
    }
    return false;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Caju Catering - Sistema</title>
    <style>
        :root {
            --primary-color: #23413a;
            --secondary-color: #ffb347;
            --bg-color: #f5f5f5;
            --text-color: #333;
            --hover-color: #1a2f2a;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: var(--bg-color);
            padding-top: 60px; /* Espaço para a navbar fixa */
            min-height: 100vh;
        }

        .navbar {
            background: var(--primary-color);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .nav-menu {
            display: flex;
            list-style: none;
        }

        .nav-item {
            position: relative;
        }

        .nav-link {
            color: white;
            text-decoration: none;
            padding: 20px 15px;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: background-color 0.3s;
        }

        .nav-link:hover,
        .nav-item:hover > .nav-link {
            background: var(--hover-color);
        }

        .nav-link.active {
            background: var(--secondary-color);
            color: var(--primary-color);
        }

        .submenu {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background: var(--primary-color);
            min-width: 200px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        .nav-item:hover .submenu {
            display: block;
        }

        .submenu a {
            color: white;
            padding: 12px 15px;
            text-decoration: none;
            display: block;
            transition: background-color 0.3s;
        }

        .submenu a:hover {
            background: var(--hover-color);
        }

        .submenu a.active {
            background: var(--secondary-color);
            color: var(--primary-color);
        }

        /* Menu mobile */
        .menu-toggle {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 24px;
            padding: 15px;
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .menu-toggle {
                display: block;
            }

            .nav-menu {
                display: none;
                flex-direction: column;
                width: 100%;
            }

            .nav-menu.active {
                display: flex;
            }

            .submenu {
                position: static;
                width: 100%;
                box-shadow: none;
                background: var(--hover-color);
            }

            .nav-item {
                width: 100%;
            }

            .nav-link {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <button class="menu-toggle" onclick="toggleMenu()">☰</button>
            <ul class="nav-menu" id="navMenu">
                <?php foreach ($menu as $title => $item): ?>
                    <li class="nav-item">
                        <a href="<?= $item['url'] ?>" 
                           class="nav-link <?= is_active($item['url'], $current_page, $item) ? 'active' : '' ?>">
                            <?= $item['icon'] ?> <?= $title ?>
                        </a>
                        <?php if (isset($item['submenu'])): ?>
                            <ul class="submenu">
                                <?php foreach ($item['submenu'] as $subtitle => $suburl): ?>
                                    <a href="<?= $suburl ?>" 
                                       class="<?= $current_page === $suburl ? 'active' : '' ?>">
                                        <?= $subtitle ?>
                                    </a>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </nav>

    <script>
        function toggleMenu() {
            document.getElementById('navMenu').classList.toggle('active');
        }
    </script>
</body>
</html>