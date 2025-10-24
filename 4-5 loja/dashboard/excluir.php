<?php
session_start();
require_once '../model/dashmodel.php';

if (isset($_GET['id'])) {
    try {
        $id = $_GET['id'];
        $dashboardModel = new DashboardModel();
        
        // Buscar produto para excluir a imagem
        $produto = $dashboardModel->getProdutoPorId($id);
        
        // Usando o método do model para excluir
        if ($dashboardModel->excluirProduto($id)) {
            // Excluir a imagem se existir
            if (!empty($produto['IMG'])) {
                $imagem_path = '../lojinha/' . ltrim($produto['IMG'], '/');
                if (file_exists($imagem_path)) {
                    unlink($imagem_path);
                }
            }
            header("Location: produtos.php?sucesso=2");
        } else {
            header("Location: produtos.php?erro=1");
        }
        
    } catch (Exception $e) {
        header("Location: produtos.php?erro=1");
    }
    exit();
} else {
    header("Location: produtos.php");
    exit();
}
?>