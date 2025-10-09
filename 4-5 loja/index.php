<?php
session_start();
require_once 'model/dashboard.php';

try {
    $dashboardModel = new DashboardModel();
    
    // Buscar todos os produtos do banco
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
    <!-- Header -->
    <header class="header">
        <nav class="navbar">
            <div class="logo">
                🎲 Mundo dos Jogos
            </div>
            <ul class="nav-links">
                <li><a href="#home">Início</a></li>
                <li><a href="#produtos">Produtos</a></li>
                <li><a href="#categorias">Categorias</a></li>
                <li><a href="#contato">Contato</a></li>
                <li>
                    <div class="search-box">
                        <input type="text" id="searchInput" placeholder="Buscar jogos..." 
                               style="padding: 0.5rem; border-radius: 20px; border: 1px solid #ccc; width: 200px;">
                    </div>
                </li>
                <li class="cart-icon" id="cartToggle">
                    🛒 <span class="cart-count" id="cartCount">0</span>
                </li>
            </ul>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="container">
        <!-- Hero Section -->
        <section class="hero" id="home">
            <div class="hero-content">
                <h1>🎲 Mundo dos Jogos</h1>
                <p>Descubra jogos incríveis para todas as idades e ocasiões. De clássicos atemporais aos lançamentos mais modernos!</p>
                <div style="display: flex; gap: 1rem; justify-content: center; margin-top: 2rem; flex-wrap: wrap;">
                    <a href="#produtos" class="btn btn-gold">Explorar Jogos</a>
                    <a href="#ofertas" class="btn">Ofertas da Semana</a>
                </div>
            </div>
        </section>

        <!-- Estatísticas -->
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

        <!-- Categorias -->
        <section id="categorias">
            <h2 style="text-align: center; margin-bottom: 2rem; font-size: 2.5rem; color: #1F2937;">
                Nossas Categorias
            </h2>
            <div class="categories">
                <button class="category-btn active" data-category="todos">Todos os Jogos</button>
                <?php foreach ($categorias as $categoria): ?>
                <button class="category-btn" data-category="<?php echo strtolower($categoria['NOME_CATEGORIA']); ?>">
                    <?php echo $categoria['NOME_CATEGORIA']; ?>
                </button>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Todos os Produtos -->
        <section id="produtos">
            <h2 style="text-align: center; margin-bottom: 3rem; font-size: 2.5rem; color: #1F2937;">
                Nossa Coleção Completa
            </h2>
            
            <?php if (isset($erro)): ?>
                <div style="text-align: center; color: red; padding: 2rem;">
                    <?php echo $erro; ?>
                </div>
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
                            <div class="product-overlay">
                                <button class="btn-quick-view" data-product-id="<?php echo $produto['ID']; ?>">
                                    Ver Detalhes
                                </button>
                            </div>
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
                                <button class="btn-add-cart" data-product-id="<?php echo $produto['ID']; ?>">
                                    🛒 Adicionar
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="grid-column: 1 / -1; text-align: center; padding: 3rem;">
                        <h3 style="color: #666; margin-bottom: 1rem;">Nenhum jogo cadastrado ainda</h3>
                        <p style="color: #999;">Visite o painel administrativo para cadastrar produtos</p>
                        <a href="admin.php" class="btn" style="margin-top: 1rem;">📊 Ir para o Dashboard</a>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <!-- Resto do seu código permanece igual -->
        <!-- Por que comprar conosco -->
        <section style="background: white; padding: 4rem 2rem; border-radius: 15px; margin: 4rem 0; text-align: center;">
            <h2 style="color: #1F2937; margin-bottom: 2rem; font-size: 2.2rem;">Por que escolher a Mundo dos Jogos?</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; margin-top: 3rem;">
                <div>
                    <div style="font-size: 3rem; margin-bottom: 1rem;">🎯</div>
                    <h3 style="color: #8B5CF6;">Jogos Selecionados</h3>
                    <p>Curadoria especializada com os melhores jogos do mercado</p>
                </div>
                <div>
                    <div style="font-size: 3rem; margin-bottom: 1rem;">🚀</div>
                    <h3 style="color: #8B5CF6;">Entrega Rápida</h3>
                    <p>Entregamos em todo o Brasil em até 5 dias úteis</p>
                </div>
                <div>
                    <div style="font-size: 3rem; margin-bottom: 1rem;">🛡️</div>
                    <h3 style="color: #8B5CF6;">Garantia Total</h3>
                    <p>30 dias para troca e devolução</p>
                </div>
                <div>
                    <div style="font-size: 3rem; margin-bottom: 1rem;">💎</div>
                    <h3 style="color: #8B5CF6;">Qualidade Garantida</h3>
                    <p>Produtos originais e de alta qualidade</p>
                </div>
            </div>
        </section>

        <!-- Newsletter -->
        <section style="background: linear-gradient(135deg, #8B5CF6, #6366F1); color: white; padding: 3rem; border-radius: 15px; text-align: center;">
            <h2 style="margin-bottom: 1rem; color: #F59E0B;">📧 Fique por Dentro</h2>
            <p style="margin-bottom: 2rem; opacity: 0.9;">Receba ofertas exclusivas e novidades do mundo dos jogos</p>
            <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; max-width: 500px; margin: 0 auto;">
                <input type="email" placeholder="Seu melhor e-mail" 
                       style="padding: 1rem; border: none; border-radius: 8px; flex: 1; min-width: 200px;">
                <button class="btn" style="background: #F59E0B; color: #1F2937;">Assinar</button>
            </div>
        </section>
    </main>

    <!-- Footer (mantém igual) -->
    <footer style="background: #1F2937; color: white; padding: 3rem 0; margin-top: 4rem;">
        <!-- ... seu footer atual ... -->
    </footer>

    <!-- Carrinho Sidebar (mantém igual) -->
    <div class="cart-sidebar" id="cartSidebar">
        <!-- ... seu carrinho atual ... -->
    </div>

    <!-- Overlay -->
    <div class="overlay" id="overlay"></div>

    <script src="js/script.js"></script>
</body>
</html>