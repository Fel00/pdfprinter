<?php
/**
 * Redireciona para a pasta public/
 * Este arquivo é usado para compatibilidade com XAMPP
 * onde o DocumentRoot pode não estar configurado para a pasta public/
 */

// Redireciona para o public
header('Location: public/');
exit;
