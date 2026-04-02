<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Caju Catering' ?></title>
    <link rel="stylesheet" href="/css/base.css">
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/menu.css">
    <link rel="stylesheet" href="/css/forms.css">
    <link rel="stylesheet" href="/css/inventory.css">
    <?php if (isset($extraCss)): ?>
        <?php foreach ((array) $extraCss as $css): ?>
            <link rel="stylesheet" href="/css/<?=$css?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>
    <?php include __DIR__ . '/../partials/header.php'; ?>

    <main>
        <?= $content ?? '' ?>
    </main>

    <?php if (isset($extraJs)): ?>
        <?php foreach ((array) $extraJs as $js): ?>
            <script src="/js/<?=$js?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
