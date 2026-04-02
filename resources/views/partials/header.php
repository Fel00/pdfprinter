<?php
// Pega o nome do arquivo atual para destacar o item ativo no menu
$current_page = basename($_SERVER['PHP_SELF']);
$current_uri = trim($_SERVER['REQUEST_URI'], '/');

// Define a estrutura de navegação
$menu = [
    'Home' => [
        'url' => '/',
        'icon' => '🏠'
    ],
    'Orçamentos' => [
        'url' => '#',
        'icon' => '📋',
        'submenu' => [
            'Gerar Orçamento - Caju' => '/budget'
        ]
    ],
    'Contratos' => [
        'url' => '#',
        'icon' => '📝',
        'submenu' => [
            'Gerar Contrato - Caju' => '/contract/caju',
            'Gerar Contrato - Feiju' => '/contract/feiju'
        ]
    ],
    'Inventário' => [
        'url' => '/inventory',
        'icon' => '📦',
        'submenu' => [
            'Catálogo' => '/inventory/catalog',
            'Baixa' => '/inventory/checkout'
        ]
    ]
];

// Função para verificar se o item ou qualquer subitem está ativo
function is_active($url, $current_uri, $menu_item) {
    if (trim($url, '/') === $current_uri) return true;
    if (isset($menu_item['submenu'])) {
        foreach ($menu_item['submenu'] as $suburl) {
            if (trim($suburl, '/') === $current_uri) return true;
        }
    }
    return false;
}
?>

<nav class="navbar">
    <div class="nav-container">
        <button class="menu-toggle" onclick="toggleMenu()">☰</button>
        <ul class="nav-menu" id="navMenu">
            <?php foreach ($menu as $title => $item): ?>
                <li class="nav-item">
                    <a href="<?= $item['url'] ?>"
                       class="nav-link <?= is_active($item['url'], $current_uri, $item) ? 'active' : '' ?>">
                        <?= $item['icon'] ?> <?= $title ?>
                    </a>
                    <?php if (isset($item['submenu'])): ?>
                        <ul class="submenu">
                            <?php foreach ($item['submenu'] as $subtitle => $suburl): ?>
                                <a href="<?= $suburl ?>"
                                   class="<?= trim($suburl, '/') === $current_uri ? 'active' : '' ?>">
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
