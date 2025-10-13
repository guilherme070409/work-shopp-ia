<?php
session_start();
require_once '../model/dashmodel.php';

try {
    $dashboardModel = new DashboardModel();
    $produtos = $dashboardModel->getTodosProdutos();
    $categorias = $dashboardModel->getCategorias();
    
} catch (Exception $e) {
    $produtos = [];
    $categorias = [];
    $erro = "Erro ao carregar produtos: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mundo dos Jogos - Loja de Jogos de Tabuleiro</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header class="header">
        <nav class="navbar">
            <div class="logo">🎲 Mundo dos Jogos</div>
            <ul class="nav-links">
                <li><a href="#home">Início</a></li>
                <li><a href="#produtos">Produtos</a></li>
                <li><a href="#categorias">Categorias</a></li>
                <li><a href="#contato">Contato</a></li>
                <li>
                    <div class="search-box">
                        <input type="text" id="searchInput" placeholder="Buscar jogos...">
                    </div>
                </li>
                <li class="cart-icon" id="cartToggle">
                    🛒 <span class="cart-count" id="cartCount">0</span>
                </li>
            </ul>
        </nav>
    </header>

    <main class="container">
        <section class="hero" id="home">
            <div class="hero-content">
                <h1>🎲 Mundo dos Jogos</h1>
                <p>Descubra jogos incríveis para todas as idades e ocasiões!</p>
                <div style="display: flex; gap: 1rem; justify-content: center; margin-top: 2rem; flex-wrap: wrap;">
                    <a href="#produtos" class="btn btn-gold">Explorar Jogos</a>
                    <a href="../dashboard/produtos.php" class="btn">Área do Admin</a>
                </div>
            </div>
        </section>

        <section style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 2rem; margin: 3rem 0;">
            <div style="text-align: center; padding: 2rem; background: white; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                <div style="font-size: 2.5rem; color: #8B5CF6;">🎯</div>
                <h3 style="color: #8B5CF6;"><?php echo count($produtos); ?></h3>
                <p>Jogos em Catálogo</p>
            </div>
            <div style="text-align: center; padding: 2rem; background: white; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                <div style="font-size: 2.5rem; color: #8B5CF6;">⭐</div>
                <h3 style="color: #8B5CF6;">4.8/5</h3>
                <p>Avaliação dos Clientes</p>
            </div>
            <div style="text-align: center; padding: 2rem; background: white; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                <div style="font-size: 2.5rem; color: #8B5CF6;">🚚</div>
                <h3 style="color: #8B5CF6;">Frete Grátis</h3>
                <p>Acima de R$ 150</p>
            </div>
            <div style="text-align: center; padding: 2rem; background: white; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                <div style="font-size: 2.5rem; color: #8B5CF6;">🏆</div>
                <h3 style="color: #8B5CF6;">5 Anos</h3>
                <p>de Experiência</p>
            </div>
        </section>

        <section id="categorias">
            <h2 style="text-align: center; margin-bottom: 2rem; font-size: 2.5rem; color: #1F2937;">Nossas Categorias</h2>
            <div class="categories">
                <button class="category-btn active" data-category="todos">Todos os Jogos</button>
                <?php foreach ($categorias as $categoria): ?>
                <button class="category-btn" data-category="<?php echo strtolower($categoria['NOME_CATEGORIA']); ?>">
                    <?php echo $categoria['NOME_CATEGORIA']; ?>
                </button>
                <?php endforeach; ?>
            </div>
        </section>

        <section id="produtos">
            <h2 style="text-align: center; margin-bottom: 3rem; font-size: 2.5rem; color: #1F2937;">Nossa Coleção Completa</h2>
            
            <?php if (isset($erro)): ?>
                <div style="text-align: center; color: red; padding: 2rem;"><?php echo $erro; ?></div>
            <?php endif; ?>
            
            <div class="products-grid" id="productsGrid">
                <?php if (!empty($produtos)): ?>
                    <?php foreach ($produtos as $produto): ?>
                    <div class="product-card" data-category="<?php echo strtolower($produto['NOME_CATEGORIA'] ?? 'outros'); ?>">
                        <div class="product-image">
                            <?php if (!empty($produto['IMG'])): ?>
                                <img src="<?php echo $produto['IMG']; ?>" alt="<?php echo $produto['NOME']; ?>">
                            <?php else: ?>
                                <div class="no-product-image">🎲</div>
                            <?php endif; ?>
                        </div>
                        <div class="product-info">
                            <h3 class="product-title"><?php echo $produto['NOME']; ?></h3>
                            <p class="product-category"><?php echo $produto['NOME_CATEGORIA'] ?? 'Sem categoria'; ?></p>
                            <div class="product-details">
                                <span class="product-players">👥 <?php echo $produto['PESOA'] ?? 'N/A'; ?></span>
                                <span class="product-age">🎯 <?php echo $produto['IDADE'] ?? 'N/A'; ?></span>
                                <span class="product-time">⏱️ <?php echo $produto['TEMPO'] ?? 'N/A'; ?></span>
                            </div>
                            <p class="product-description">
                                <?php 
                                $descricao = $produto['DESCRICAO'] ?? 'Descrição não disponível';
                                echo strlen($descricao) > 100 ? substr($descricao, 0, 100) . '...' : $descricao;
                                ?>
                            </p>
                            <div class="product-footer">
                                <span class="product-price">R$ <?php echo $produto['PRECO']; ?></span>
                                <button class="btn-add-cart" data-product-id="<?php echo $produto['ID']; ?>">🛒 Adicionar</button>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="grid-column: 1 / -1; text-align: center; padding: 3rem;">
                        <h3 style="color: #666; margin-bottom: 1rem;">Nenhum jogo cadastrado ainda</h3>
                        <p style="color: #999;">Visite o painel administrativo para cadastrar produtos</p>
                        <a href="../dashboard/produtos.php" class="btn" style="margin-top: 1rem;">📊 Ir para o Dashboard</a>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <div class="cart-sidebar" id="cartSidebar">
        <div class="cart-header">
            <h3>🛒 Seu Carrinho</h3>
            <button class="close-cart" id="closeCart">×</button>
        </div>
        <div class="cart-items" id="cartItems"></div>
        <div class="cart-total" id="cartTotal">Total: R$ 0,00</div>
        <div style="display: flex; gap: 1rem; padding: 1rem;">
            <button class="btn" style="flex: 1;" onclick="toggleCart()">Continuar Comprando</button>
            <button class="btn" style="flex: 1; background: #F59E0B; color: #1F2937;" onclick="finalizarCompra()">💳 Finalizar Compra</button>
        </div>
    </div>

    <div class="overlay" id="overlay"></div>

    <script src="js/script.js"></script>
</body>
</html>