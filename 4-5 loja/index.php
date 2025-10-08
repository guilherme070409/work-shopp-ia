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
                <h3 style="color: #8B5CF6;">+200</h3>
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
                <button class="category-btn" data-category="classicos">Clássicos</button>
                <button class="category-btn" data-category="estrategia">Estratégia</button>
                <button class="category-btn" data-category="festa">Festa</button>
                <button class="category-btn" data-category="familia">Família</button>
                <button class="category-btn" data-category="cooperativo">Cooperativo</button>
                <button class="category-btn" data-category="misterio">Mistério</button>
                <button class="category-btn" data-category="cartas">Cartas</button>
            </div>
        </section>


        <!-- Todos os Produtos -->
        <section id="produtos">
            <h2 style="text-align: center; margin-bottom: 3rem; font-size: 2.5rem; color: #1F2937;">
                Nossa Coleção Completa
            </h2>
            <div class="products-grid" id="productsGrid">
                <!-- Produtos serão renderizados via JavaScript -->
            </div>
        </section>

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

    <!-- Footer -->
    <footer style="background: #1F2937; color: white; padding: 3rem 0; margin-top: 4rem;">
        <div style="max-width: 1200px; margin: 0 auto; padding: 0 2rem;">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem;">
                <div>
                    <h3 style="color: #F59E0B; margin-bottom: 1rem;">🎲 Mundo dos Jogos</h3>
                    <p>Sua loja especializada em jogos de tabuleiro para todas as idades e ocasiões.</p>
                </div>
                <div>
                    <h4 style="color: #F59E0B; margin-bottom: 1rem;">Categorias</h4>
                    <ul style="list-style: none;">
                        <li><a href="#" style="color: white; text-decoration: none;">Jogos Clássicos</a></li>
                        <li><a href="#" style="color: white; text-decoration: none;">Jogos de Estratégia</a></li>
                        <li><a href="#" style="color: white; text-decoration: none;">Jogos de Festa</a></li>
                        <li><a href="#" style="color: white; text-decoration: none;">Jogos Familiares</a></li>
                    </ul>
                </div>
                <div>
                    <h4 style="color: #F59E0B; margin-bottom: 1rem;">Ajuda</h4>
                    <ul style="list-style: none;">
                        <li><a href="#" style="color: white; text-decoration: none;">Entrega</a></li>
                        <li><a href="#" style="color: white; text-decoration: none;">Trocas</a></li>
                        <li><a href="#" style="color: white; text-decoration: none;">Pagamento</a></li>
                        <li><a href="#" style="color: white; text-decoration: none;">Contato</a></li>
                    </ul>
                </div>
                <div>
                    <h4 style="color: #F59E0B; margin-bottom: 1rem;">Contato</h4>
                    <p>📞 (11) 9999-9999</p>
                    <p>✉️ contato@mundodosjogos.com.br</p>
                    <p>🏢 São Paulo - SP</p>
                </div>
            </div>
            <div style="text-align: center; margin-top: 3rem; padding-top: 2rem; border-top: 1px solid #374151;">
                <p>&copy; 2024 Mundo dos Jogos. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>

    <!-- Carrinho Sidebar -->
    <div class="cart-sidebar" id="cartSidebar">
        <div class="cart-header">
            <h3 style="color: #1F2937;">🛒 Seu Carrinho</h3>
            <button class="close-cart" id="closeCart">×</button>
        </div>
        <div class="cart-items" id="cartItems">
            <!-- Itens do carrinho serão renderizados aqui -->
        </div>
        <div class="cart-total" id="cartTotal">Total: R$ 0,00</div>
        <div style="display: flex; gap: 1rem;">
            <button class="btn" style="flex: 1;" onclick="toggleCart()">Continuar Comprando</button>
            <button class="btn" style="flex: 1; background: #F59E0B; color: #1F2937;" onclick="finalizarCompra()">
                💳 Finalizar Compra
            </button>
        </div>
        <div style="text-align: center; margin-top: 1rem; font-size: 0.8rem; color: #666;">
            🚚 Frete grátis para compras acima de R$ 150
        </div>
    </div>

    <!-- Overlay -->
    <div class="overlay" id="overlay"></div>

    <script src="js/script.js"></script>
     <script src="js/api.js"></script>
</body>
</html>