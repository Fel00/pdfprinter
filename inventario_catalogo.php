<?php
// inventario_catalogo.php - formulário para adicionar itens ao catálogo
// Configurações
$dataDir = __DIR__ . '/dados';
$imagesDir = $dataDir . '/imagens';
$jsonFile = $dataDir . '/inventario.json';
if (!is_dir($dataDir))
    mkdir($dataDir, 0755, true);
if (!is_dir($imagesDir))
    mkdir($imagesDir, 0755, true);
if (!file_exists($jsonFile))
    file_put_contents($jsonFile, json_encode([]));

$errors = [];
$success = '';

// Handler para requisições AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');

    $items = json_decode(file_get_contents($jsonFile), true);
    if (!is_array($items))
        $items = [];

    switch ($_POST['action']) {
        case 'update_quantidade':
            if (!isset($_POST['id']) || !isset($_POST['quantidade'])) {
                echo json_encode(['error' => 'Parâmetros inválidos']);
                exit;
            }

            $id = $_POST['id'];
            $quantidade = max(0, intval($_POST['quantidade']));
            $found = false;

            foreach ($items as &$item) {
                if ($item['id'] === $id) {
                    $item['quantidade'] = $quantidade;
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                echo json_encode(['error' => 'Item não encontrado']);
                exit;
            }

            if (file_put_contents($jsonFile, json_encode($items, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['error' => 'Erro ao salvar']);
            }
            exit;

        case 'delete_item':
            if (!isset($_POST['id'])) {
                echo json_encode(['error' => 'ID não fornecido']);
                exit;
            }

            $id = $_POST['id'];
            $index = -1;
            $imagemParaExcluir = null;

            // Encontra o item e sua imagem
            foreach ($items as $i => $item) {
                if ($item['id'] === $id) {
                    $index = $i;
                    $imagemParaExcluir = !empty($item['imagem']) ? $item['imagem'] : null;
                    break;
                }
            }

            if ($index === -1) {
                echo json_encode(['error' => 'Item não encontrado']);
                exit;
            }

            // Remove o item do array
            array_splice($items, $index, 1);

            // Salva o JSON atualizado
            if (file_put_contents($jsonFile, json_encode($items, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
                // Se salvou com sucesso, tenta excluir a imagem
                if ($imagemParaExcluir && file_exists($imagesDir . '/' . $imagemParaExcluir)) {
                    unlink($imagesDir . '/' . $imagemParaExcluir);
                }
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['error' => 'Erro ao excluir']);
            }
            exit;
    }
}

function sanitize_text($s)
{
    return trim(htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'));
}

// Handle POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = isset($_POST['nome']) ? sanitize_text($_POST['nome']) : '';
    $descricao = isset($_POST['descricao']) ? sanitize_text($_POST['descricao']) : '';
    $quantidade = isset($_POST['quantidade']) ? intval($_POST['quantidade']) : 0;

    if ($nome === '')
        $errors[] = 'Nome é obrigatório.';
    if ($quantidade < 0)
        $errors[] = 'Quantidade inválida.';

    // Handle image upload
    $imageName = '';
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] !== UPLOAD_ERR_NO_FILE) {
        $f = $_FILES['imagem'];
        if ($f['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'Erro no upload da imagem.';
        } else {
            // Validate size (2MB max)
            if ($f['size'] > 2 * 1024 * 1024) {
                $errors[] = 'Imagem maior que 2MB.';
            }
            // Validate MIME
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $f['tmp_name']);
            finfo_close($finfo);
            $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif'];
            if (!array_key_exists($mime, $allowed)) {
                $errors[] = 'Tipo de arquivo inválido. Envie JPG, PNG ou GIF.';
            }
            // Move and compress
            if (empty($errors)) {
                $ext = $allowed[$mime];
                $base = preg_replace('/[^a-z0-9\-_\.]/i', '_', pathinfo($f['name'], PATHINFO_FILENAME));
                $uniq = $base . '_' . time();
                $destPath = $imagesDir . '/' . $uniq . '.' . $ext;

                // Try to compress using GD
                if ($mime === 'image/jpeg') {
                    $img = @imagecreatefromjpeg($f['tmp_name']);
                    if ($img) {
                        imagejpeg($img, $destPath, 80);
                        imagedestroy($img);
                    } else {
                        move_uploaded_file($f['tmp_name'], $destPath);
                    }
                } elseif ($mime === 'image/png') {
                    $img = @imagecreatefrompng($f['tmp_name']);
                    if ($img) {
                        // convert to truecolor and preserve alpha
                        $w = imagesx($img);
                        $h = imagesy($img);
                        $tmp = imagecreatetruecolor($w, $h);
                        imagealphablending($tmp, false);
                        imagesavealpha($tmp, true);
                        imagecopy($tmp, $img, 0, 0, 0, 0, $w, $h);
                        imagepng($tmp, $destPath, 6);
                        imagedestroy($tmp);
                        imagedestroy($img);
                    } else {
                        move_uploaded_file($f['tmp_name'], $destPath);
                    }
                } else {
                    // gif or others: move
                    move_uploaded_file($f['tmp_name'], $destPath);
                }

                $imageName = basename($destPath);
            }
        }
    }

    // If no errors, read json and append if not duplicate
    if (empty($errors)) {
        $items = json_decode(file_get_contents($jsonFile), true);
        if (!is_array($items))
            $items = [];

        // Prevent duplicate by name (case-insensitive)
        $exists = false;
        foreach ($items as $it) {
            if (isset($it['nome']) && mb_strtolower($it['nome']) === mb_strtolower($nome)) {
                $exists = true;
                break;
            }
        }
        if ($exists) {
            $errors[] = 'Já existe um item com esse nome no catálogo.';
            // remove uploaded image if any
            if ($imageName && file_exists($imagesDir . '/' . $imageName))
                unlink($imagesDir . '/' . $imageName);
        } else {
            $new = [
                'id' => uniqid('item_', true),
                'nome' => $nome,
                'descricao' => $descricao,
                'quantidade' => $quantidade,
                'imagem' => $imageName,
                'created_at' => date('c')
            ];
            $items[] = $new;
            if (file_put_contents($jsonFile, json_encode($items, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
                $success = 'Item adicionado com sucesso.';
                // clear POST values
                $_POST = [];
            } else {
                $errors[] = 'Falha ao salvar os dados.';
                if ($imageName && file_exists($imagesDir . '/' . $imageName))
                    unlink($imagesDir . '/' . $imageName);
            }
        }
    }
}

?>
<?php include 'header.php'; ?>
<style>
    .form-box {
        max-width: 700px;
        margin: 30px auto;
        background: #fff;
        padding: 20px;
        border-radius: 6px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    label {
        display: block;
        margin: 10px 0 6px;
    }

    input[type=text],
    input[type=number],
    textarea {
        width: 100%;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .submit {
        margin-top: 12px;
        padding: 10px 14px;
        background: #23413a;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .msg-success {
        background: #e6ffed;
        border: 1px solid #8fe0b2;
        padding: 8px;
        color: #064;
        margin-bottom: 10px;
    }

    .msg-error {
        background: #ffe6e6;
        border: 1px solid #f2a0a0;
        padding: 8px;
        color: #600;
        margin-bottom: 10px;
    }

    .thumb {
        max-width: 150px;
        margin-top: 8px;
    }

    /* Novos estilos */
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
        flex: 0 0 150px;
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
</style>
<link rel="stylesheet" href="css/base.css">
<link rel="stylesheet" href="css/forms.css">
</head>

<body>


    <?php if (!empty($errors)): ?>
        <div class="msg-error"><?php echo implode('<br>', array_map('htmlspecialchars', $errors)); ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="msg-success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <div class="form-container">
        <h2>Catálogo - Adicionar Material</h2>
        <form method="post" enctype="multipart/form-data">
            <div class="form-section">

                <label for="nome">Nome</label>
                <input id="nome" name="nome" type="text"
                    value="<?php echo isset($_POST['nome']) ? htmlspecialchars($_POST['nome']) : ''; ?>" required>

                <label for="descricao">Descrição</label>
                <textarea id="descricao" name="descricao"
                    rows="4"><?php echo isset($_POST['descricao']) ? htmlspecialchars($_POST['descricao']) : ''; ?></textarea>

                <label for="quantidade">Quantidade disponível</label>
                <input id="quantidade" name="quantidade" type="number" min="0"
                    value="<?php echo isset($_POST['quantidade']) ? intval($_POST['quantidade']) : 0; ?>">

                <label for="imagem">Imagem (JPG/PNG/GIF, max 2MB)</label>
                <input id="imagem" name="imagem" type="file" accept="image/*">

                <button class="submit" type="submit">Salvar</button>
                <p style="margin-top:12px;"><a href="inventario.php">Voltar ao Inventário</a>
                <p>
            </div>
        </form>
    </div>




    <div class="form-box">
        <h3>Itens do Catálogo</h3>
        <input type="text" class="search-box" placeholder="Pesquisar itens..." id="searchBox">

        <div id="itemsList">
            <?php
            $items = json_decode(file_get_contents($jsonFile), true);
            if (!is_array($items) || count($items) === 0) {
                echo '<p class="no-items">Nenhum item cadastrado.</p>';
            } else {
                echo '<div class="item-list">';
                foreach ($items as $it) {
                    echo '<div class="item-card" data-id="' . htmlspecialchars($it['id']) . '" data-nome="' . htmlspecialchars($it['nome']) . '">';

                    // Imagem
                    echo '<div class="item-image">';
                    if (!empty($it['imagem']) && file_exists($imagesDir . '/' . $it['imagem'])) {
                        echo '<img class="thumb" src="dados/imagens/' . rawurlencode($it['imagem']) . '" alt="' . htmlspecialchars($it['nome']) . '">';
                    }
                    echo '</div>';

                    // Detalhes
                    echo '<div class="item-details">';
                    echo '<h4>' . htmlspecialchars($it['nome']) . '</h4>';

                    // Quantidade com edição inline
                    echo '<div class="quantity-edit">';
                    echo '<label>Quantidade:</label>';
                    echo '<input type="number" min="0" value="' . htmlspecialchars($it['quantidade']) . '" 
                              onchange="updateQuantidade(\'' . htmlspecialchars($it['id']) . '\', this.value)">';
                    echo '</div>';

                    if (!empty($it['descricao'])) {
                        echo '<p>' . nl2br(htmlspecialchars($it['descricao'])) . '</p>';
                    }

                    // Ações
                    echo '<div class="item-actions">';
                    echo '<button class="btn-delete" onclick="excluirItem(\'' . htmlspecialchars($it['id']) . '\')">Excluir</button>';
                    echo '</div>';

                    echo '</div>'; // fim item-details
                    echo '</div>'; // fim item-card
                }
                echo '</div>';
            }
            ?>
        </div>
    </div>

    <!-- Scripts para funcionalidade dinâmica -->
    <script>
        // Pesquisa em tempo real
        document.getElementById('searchBox').addEventListener('input', function (e) {
            const searchTerm = e.target.value.toLowerCase();
            const items = document.querySelectorAll('.item-card');

            items.forEach(item => {
                const nome = item.getAttribute('data-nome').toLowerCase();
                const matches = nome.includes(searchTerm);

                item.style.display = matches ? '' : 'none';

                // Remove highlight anterior
                item.classList.remove('highlight');

                // Adiciona highlight se há termo de busca e item corresponde
                if (searchTerm && matches) {
                    item.classList.add('highlight');
                }
            });
        });

        // Atualizar quantidade
        function updateQuantidade(id, novaQuantidade) {
            if (novaQuantidade < 0) return;

            fetch('?', {
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

            fetch('?', {
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
</body>

</html>