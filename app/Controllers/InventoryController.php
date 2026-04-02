<?php

namespace App\Controllers;

use App\Helpers\SecurityHelper;
use App\Services\InventoryService;
use App\Services\PDFGenerator;

/**
 * Controller para gestão do inventário
 */
class InventoryController extends BaseController
{
    private $inventoryService;
    private $pdfGenerator;

    public function __construct()
    {
        parent::__construct();
        $this->inventoryService = new InventoryService();
        $this->pdfGenerator = new PDFGenerator();
    }

    /**
     * Exibe menu do inventário
     */
    public function index(): void
    {
        $this->render('inventory/index', [
            'title' => 'Inventário'
        ]);
    }

    /**
     * Exibe catálogo de itens
     */
    public function catalog(): void
    {
        $items = $this->inventoryService->getAllItems();

        $this->render('inventory/catalog', [
            'title' => 'Catálogo de Materiais',
            'items' => $items
        ]);
    }

    /**
     * Processa ações AJAX do catálogo
     */
    public function catalogAction(): void
    {
        if (!SecurityHelper::isPost()) {
            $this->json(['error' => 'Método não permitido'], 405);
        }

        $action = SecurityHelper::postRaw('action', '');

        switch ($action) {
            case 'update_quantidade':
                $id = SecurityHelper::postRaw('id', '');
                $quantidade = (int) SecurityHelper::postRaw('quantidade', 0);
                $result = $this->inventoryService->updateQuantity($id, $quantidade);
                $this->json($result);
                break;

            case 'delete_item':
                $id = SecurityHelper::postRaw('id', '');
                $result = $this->inventoryService->deleteItem($id);
                $this->json($result);
                break;

            default:
                // Adicionar novo item
                if ($this->isPost() && empty($action)) {
                    $this->handleAddItem();
                }
        }
    }

    /**
     * Exibe formulário de baixa de inventário
     */
    public function checkout(): void
    {
        $items = $this->inventoryService->getAllItems();

        $this->render('inventory/checkout', [
            'title' => 'Baixa de Inventário',
            'items' => $items
        ]);
    }

    /**
     * Gera PDF de checklist de inventário
     */
    public function generateChecklist(): void
    {
        if (!$this->isPost()) {
            $this->redirect('inventory/checkout');
        }

        $cliente = SecurityHelper::post('cliente', '');
        $data = SecurityHelper::post('data', '');
        $dataFormatada = date('d/m/Y', strtotime($data));
        $local = SecurityHelper::post('local', '');
        $equipe = SecurityHelper::postRaw('equipe', '');
        $fardamento = SecurityHelper::postRaw('fardamento', '');
        $cardapio = SecurityHelper::postRaw('cardapio', '');

        // Processa materiais selecionados
        $materiais = [];
        $materialIds = SecurityHelper::postRaw('material_id', []);
        $materialQuantidades = SecurityHelper::postRaw('material_quantidade', []);

        if (is_array($materialIds)) {
            $catalogoMap = $this->inventoryService->getItemsByIds($materialIds);

            foreach ($materialIds as $i => $materialId) {
                if (isset($catalogoMap[$materialId])) {
                    $materiais[] = [
                        'nome' => $catalogoMap[$materialId]['nome'],
                        'quantidade' => $materialQuantidades[$i] ?? 0
                    ];
                }
            }
        }

        // Carrega CSS
        $cssPath = __DIR__ . '/../../public/css/pdf_inventory_style.css';

        // Gera o HTML
        $html = $this->render('inventory/pdf_checklist', [
            'cliente' => $cliente,
            'data' => $dataFormatada,
            'local' => $local,
            'equipe' => $equipe,
            'fardamento' => $fardamento,
            'cardapio' => $cardapio,
            'materiais' => $materiais
        ], true);

        // Gera PDF
        $this->pdfGenerator->loadCss($cssPath);
        $this->pdfGenerator->writeHtml($html);

        // Cabeçalho e rodapé
        $headerHtml = '<div style="text-align: right; font-style: italic;"\u003e
            Data do Evento: ' . SecurityHelper::sanitize($dataFormatada) . '<br\u003e
            Cliente: ' . SecurityHelper::sanitize($cliente) . '
        </div\u003e';

        $footerHtml = '<div style="text-align: center; font-size: 10pt;"\u003e
            Página {PAGENO} de {nb}
        </div\u003e';

        $this->pdfGenerator
            ->setHeader($headerHtml)
            ->setFooter($footerHtml);

        $filename = 'Checklist_' . preg_replace('/[^a-zA-Z0-9]/', '_', $cliente) . '.pdf';

        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="' . $filename . '"');
        header('Cache-Control: private, max-age=0, must-revalidate');
        header('Pragma: public');

        $this->pdfGenerator->inline($filename);
    }

    /**
     * Handler para adicionar item (POST normal)
     */
    private function handleAddItem(): void
    {
        $nome = SecurityHelper::post('nome', '');
        $descricao = SecurityHelper::post('descricao', '');
        $quantidade = (int) SecurityHelper::post('quantidade', 0);
        $imagem = $_FILES['imagem'] ?? null;

        $result = $this->inventoryService->addItem($nome, $descricao, $quantidade, $imagem);

        if ($result['success']) {
            $this->redirect('inventory/catalog?success=1');
        } else {
            $this->redirect('inventory/catalog?error=' . urlencode($result['message']));
        }
    }
}
