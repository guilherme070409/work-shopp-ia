<?php
session_start();
require_once 'model/dashboard.php';

try {
    $dashboardModel = new DashboardModel();
    $total_produtos = $dashboardModel->getTotalProdutos();
    $total_categorias = $dashboardModel->getTotalCategorias();
    $produtos_recentes = $dashboardModel->getProdutosRecentes();
    $produtos_por_categoria = $dashboardModel->getProdutosPorCategoria();
    
    $db_status = "✅ Conectado";
    
} catch (Exception $e) {
    $db_status = "❌ Erro: " . $e->getMessage();
    $total_produtos = 0;
    $total_categorias = 0;
    $produtos_recentes = [];
    $produtos_por_categoria = [];
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Admin</title>
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo">
                <h2>🎲 Admin Panel</h2>
                <small>Banco: <?php echo $db_status; ?></small>
            </div>
            <nav class="sidebar-nav">
                <ul>
                    <li class="active"><a href="admin.php">📊 Dashboard</a></li>
                    <li><a href="produtos.php">🎯 Produtos</a></li>
                    <li><a href="categorias.php">📁 Categorias</a></li>
                    <li><a href="index.php" target="_blank">🏠 Ver Loja</a></li>
                    <li><a href="#" id="logout">🚪 Sair</a></li>
                </ul>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <header class="dashboard-header">
                <div class="header-left">
                    <h1>Dashboard</h1>
                    <p>Bem-vindo de volta, Admin!</p>
                    <small style="color: #28a745;">📊 Dados em tempo real do banco</small>
                </div>
            </header>

            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon" style="background: #4CAF50;">
                        📦
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $total_produtos; ?></h3>
                        <p>Total de Produtos</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background: #2196F3;">
                        📁
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $total_categorias; ?></h3>
                        <p>Categorias</p>
                    </div>
                </div>
            </div>

            <!-- Produtos Recentes -->
            <div class="content-card">
                <div class="card-header">
                    <h3>Produtos Recentes</h3>
                </div>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Preço</th>
                                <th>Categoria</th>
                                <th>Idade</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($produtos_recentes)): ?>
                                <?php foreach ($produtos_recentes as $produto): ?>
                                <tr>
                                    <td>#<?php echo $produto['ID']; ?></td>
                                    <td>
                                        <div class="product-cell">
                                            <?php if(!empty($produto['IMG'])): ?>
                                            <img src="img/<?php echo $produto['IMG']; ?>" alt="<?php echo $produto['NOME']; ?>">
                                            <?php endif; ?>
                                            <span><?php echo $produto['NOME']; ?></span>
                                        </div>
                                    </td>
                                    <td>R$ <?php echo $produto['PRECO']; ?></td>
                                    <td><?php echo $produto['NOME_CATEGORIA'] ?? 'Sem categoria'; ?></td>
                                    <td><?php echo $produto['IDADE']; ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" style="text-align: center;">Nenhum produto cadastrado</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Produtos por Categoria -->
            <div class="content-card">
                <div class="card-header">
                    <h3>Produtos por Categoria</h3>
                </div>
                <div class="categories-stats">
                    <?php if(!empty($produtos_por_categoria)): ?>
                        <?php foreach ($produtos_por_categoria as $categoria): ?>
                        <div class="category-stat">
                            <h4><?php echo $categoria['NOME_CATEGORIA']; ?></h4>
                            <p><?php echo $categoria['total']; ?> produtos</p>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Nenhuma categoria cadastrada</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="js/dashboard.js"></script>
</body>
</html>