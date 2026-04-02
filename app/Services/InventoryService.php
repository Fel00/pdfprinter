<?php

namespace App\Services;

/**
 * Serviço para gerenciamento do inventário
 */
class InventoryService
{
    private $dataDir;
    private $imagesDir;
    private $jsonFile;

    public function __construct()
    {
        $this->dataDir = __DIR__ . '/../../dados';
        $this->imagesDir = $this->dataDir . '/imagens';
        $this->jsonFile = $this->dataDir . '/inventario.json';

        $this->ensureDirectories();
    }

    /**
     * Garante que os diretórios necessários existam
     */
    private function ensureDirectories(): void
    {
        if (!is_dir($this->dataDir)) {
            mkdir($this->dataDir, 0755, true);
        }
        if (!is_dir($this->imagesDir)) {
            mkdir($this->imagesDir, 0755, true);
        }
        if (!file_exists($this->jsonFile)) {
            file_put_contents($this->jsonFile, json_encode([]));
        }
    }

    /**
     * Obtém todos os itens do inventário
     *
     * @return array Lista de itens
     */
    public function getAllItems(): array
    {
        $items = json_decode(file_get_contents($this->jsonFile), true);
        return is_array($items) ? $items : [];
    }

    /**
     * Obtém um item específico pelo ID
     *
     * @param string $id ID do item
     * @return array|null Item encontrado ou null
     */
    public function getItem(string $id): ?array
    {
        $items = $this->getAllItems();
        foreach ($items as $item) {
            if ($item['id'] === $id) {
                return $item;
            }
        }
        return null;
    }

    /**
     * Obtém múltiplos itens por ID
     *
     * @param array $ids Array de IDs
     * @return array Mapa de ID => item
     */
    public function getItemsByIds(array $ids): array
    {
        $items = $this->getAllItems();
        $map = [];
        foreach ($items as $item) {
            if (in_array($item['id'], $ids)) {
                $map[$item['id']] = $item;
            }
        }
        return $map;
    }

    /**
     * Adiciona um novo item ao inventário
     *
     * @param string $nome Nome do item
     * @param string $descricao Descrição
     * @param int $quantidade Quantidade disponível
     * @param array|null $imagem Dados do arquivo de imagem ($_FILES)
     * @return array Resultado da operação ['success' => bool, 'message' => string, 'item' => array|null]
     */
    public function addItem(string $nome, string $descricao, int $quantidade, ?array $imagem = null): array
    {
        if ($nome === '') {
            return ['success' => false, 'message' => 'Nome é obrigatório.'];
        }

        if ($quantidade < 0) {
            return ['success' => false, 'message' => 'Quantidade inválida.'];
        }

        $items = $this->getAllItems();

        // Verifica duplicado
        foreach ($items as $item) {
            if (mb_strtolower($item['nome']) === mb_strtolower($nome)) {
                return ['success' => false, 'message' => 'Já existe um item com esse nome no catálogo.'];
            }
        }

        // Processa imagem
        $imageName = '';
        if ($imagem && $imagem['error'] === UPLOAD_ERR_OK) {
            $imageResult = $this->processImage($imagem);
            if (!$imageResult['success']) {
                return $imageResult;
            }
            $imageName = $imageResult['filename'];
        }

        $newItem = [
            'id' => uniqid('item_', true),
            'nome' => $nome,
            'descricao' => $descricao,
            'quantidade' => $quantidade,
            'imagem' => $imageName,
            'created_at' => date('c')
        ];

        $items[] = $newItem;

        if (file_put_contents($this->jsonFile, json_encode($items, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
            return ['success' => true, 'message' => 'Item adicionado com sucesso.', 'item' => $newItem];
        }

        // Remove imagem se falhou ao salvar
        if ($imageName && file_exists($this->imagesDir . '/' . $imageName)) {
            unlink($this->imagesDir . '/' . $imageName);
        }

        return ['success' => false, 'message' => 'Falha ao salvar os dados.'];
    }

    /**
     * Processa o upload de uma imagem
     *
     * @param array $imagem Dados do arquivo ($_FILES)
     * @return array Resultado da operação
     */
    private function processImage(array $imagem): array
    {
        if ($imagem['size'] > 2 * 1024 * 1024) {
            return ['success' => false, 'message' => 'Imagem maior que 2MB.'];
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $imagem['tmp_name']);
        finfo_close($finfo);

        $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif'];

        if (!array_key_exists($mime, $allowed)) {
            return ['success' => false, 'message' => 'Tipo de arquivo inválido. Envie JPG, PNG ou GIF.'];
        }

        $ext = $allowed[$mime];
        $base = preg_replace('/[^a-z0-9\-_\.]/i', '_', pathinfo($imagem['name'], PATHINFO_FILENAME));
        $uniq = $base . '_' . time();
        $destPath = $this->imagesDir . '/' . $uniq . '.' . $ext;

        // Comprime a imagem
        if ($mime === 'image/jpeg') {
            $img = @imagecreatefromjpeg($imagem['tmp_name']);
            if ($img) {
                imagejpeg($img, $destPath, 80);
                imagedestroy($img);
            } else {
                move_uploaded_file($imagem['tmp_name'], $destPath);
            }
        } elseif ($mime === 'image/png') {
            $img = @imagecreatefrompng($imagem['tmp_name']);
            if ($img) {
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
                move_uploaded_file($imagem['tmp_name'], $destPath);
            }
        } else {
            move_uploaded_file($imagem['tmp_name'], $destPath);
        }

        return ['success' => true, 'filename' => basename($destPath)];
    }

    /**
     * Atualiza a quantidade de um item
     *
     * @param string $id ID do item
     * @param int $quantidade Nova quantidade
     * @return array Resultado da operação
     */
    public function updateQuantity(string $id, int $quantidade): array
    {
        if ($quantidade < 0) {
            return ['success' => false, 'message' => 'Quantidade inválida.'];
        }

        $items = $this->getAllItems();
        $found = false;

        foreach ($items as &$item) {
            if ($item['id'] === $id) {
                $item['quantidade'] = $quantidade;
                $found = true;
                break;
            }
        }

        if (!$found) {
            return ['success' => false, 'message' => 'Item não encontrado.'];
        }

        if (file_put_contents($this->jsonFile, json_encode($items, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
            return ['success' => true, 'message' => 'Quantidade atualizada.'];
        }

        return ['success' => false, 'message' => 'Erro ao salvar.'];
    }

    /**
     * Remove um item do inventário
     *
     * @param string $id ID do item
     * @return array Resultado da operação
     */
    public function deleteItem(string $id): array
    {
        $items = $this->getAllItems();
        $index = -1;
        $imageToDelete = null;

        foreach ($items as $i => $item) {
            if ($item['id'] === $id) {
                $index = $i;
                $imageToDelete = !empty($item['imagem']) ? $item['imagem'] : null;
                break;
            }
        }

        if ($index === -1) {
            return ['success' => false, 'message' => 'Item não encontrado.'];
        }

        array_splice($items, $index, 1);

        if (file_put_contents($this->jsonFile, json_encode($items, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
            // Remove a imagem associada
            if ($imageToDelete && file_exists($this->imagesDir . '/' . $imageToDelete)) {
                unlink($this->imagesDir . '/' . $imageToDelete);
            }
            return ['success' => true, 'message' => 'Item excluído.'];
        }

        return ['success' => false, 'message' => 'Erro ao excluir.'];
    }

    /**
     * Obtém o caminho do diretório de imagens
     *
     * @return string
     */
    public function getImagesDir(): string
    {
        return $this->imagesDir;
    }

    /**
     * Verifica se uma imagem existe
     *
     * @param string $imageName Nome do arquivo
     * @return bool
     */
    public function imageExists(string $imageName): bool
    {
        return file_exists($this->imagesDir . '/' . $imageName);
    }
}
