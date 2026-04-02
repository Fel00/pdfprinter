<?php

namespace App\Controllers;

/**
 * Controller para a página inicial
 */
class HomeController extends BaseController
{
    /**
     * Exibe o menu principal
     */
    public function index(): void
    {
        $this->render('home/index', [
            'title' => 'Caju Catering'
        ]);
    }
}
