// Dados dos produtos - Loja de Jogos de Tabuleiro
const produtos = [
    // 🎲 Jogos Clássicos
    {
        id: 1,
        nome: "Banco Imobiliário Special Edition",
        preco: 89.90,
        precoOriginal: 109.90,
        categoria: "classicos",
        descricao: "Edição especial do clássico jogo de compra e venda de propriedades.",
        imagem: "img/tabuleiro_profisional.webp",
        jogadores: "2-6",
        idade: "8+",
        duracao: "60-120min",
        destaque: true
    },
    {
        id: 2,
        nome: "War Estratégia Global",
        preco: 129.90,
        categoria: "estrategia", 
        descricao: "Conquiste territórios e domine o mundo com táticas militares.",
        imagem: "img/relogio_digital.webp",
        jogadores: "2-6",
        idade: "10+",
        duracao: "90-180min",
        destaque: true
    },
    {
        id: 3,
        nome: "Detetive Investigação",
        preco: 69.90,
        categoria: "misterio",
        descricao: "Resolva mistérios e descubra o culpado antes dos outros detetives.",
        imagem: "img/relogio_digital.webp",
        jogadores: "3-6",
        idade: "8+", 
        duracao: "45-60min",
        destaque: false
    },

    // 🃏 Jogos de Cartas
    {
        id: 4,
        nome: "Uno Flip Edition",
        preco: 39.90,
        categoria: "cartas",
        descricao: "Versão emocionante do Uno com cartas de dois lados e ações especiais.",
        imagem: "img/relogio_digital.webp",
        jogadores: "2-10",
        idade: "7+",
        duracao: "15-30min",
        destaque: true
    },
    {
        id: 5, 
        nome: "Cards Against Humanity",
        preco: 79.90,
        categoria: "cartas",
        descricao: "Jogo de cartas hilário e politicamente incorreto para noites com amigos.",
        imagem: "img/relogio_digital.webp",
        jogadores: "4-20+",
        idade: "17+",
        duracao: "30-90min", 
        destaque: false
    },

    // 🧩 Jogos Cooperativos
    {
        id: 6,
        nome: "Pandemic: Salve o Mundo",
        preco: 149.90,
        categoria: "cooperativo",
        descricao: "Trabalhem juntos para curar doenças e salvar a humanidade.",
        imagem: "img/relogio_digital.webp",
        jogadores: "2-4",
        idade: "8+",
        duracao: "45-60min",
        destaque: true
    },
    {
        id: 7,
        nome: "The Mind - Desafio Mental", 
        preco: 59.90,
        categoria: "cooperativo",
        descricao: "Jogo cooperativo onde a comunicação é proibida! Leia mentes.",
        imagem: "img/relogio_digital.webp",
        jogadores: "2-4",
        idade: "8+",
        duracao: "20min",
        destaque: false
    },

    // 🎯 Jogos de Festa
    {
        id: 8,
        nome: "Codenames - Desafio em Duplas",
        preco: 89.90,
        categoria: "festa", 
        descricao: "Jogo de palavras onde espiões tentam se comunicar secretamente.",
        imagem: "img/relogio_digital.webp",
        jogadores: "4-8+",
        idade: "10+",
        duracao: "15min",
        destaque: true
    },
    {
        id: 9,
        nome: "Dixit - Imaginação e Criatividade",
        preco: 119.90,
        categoria: "festa",
        descricao: "Jogo de imaginação com cartas ilustradas lindamente.",
        imagem: "img/relogio_digital.webp", 
        jogadores: "3-6",
        idade: "8+",
        duracao: "30min",
        destaque: false
    },

    // 🏰 Jogos de Estratégia
    {
        id: 10,
        nome: "Catan - Colonizadores",
        preco: 159.90,
        categoria: "estrategia",
        descricao: "Construa estradas, cidades e negocie recursos em uma ilha deserta.",
        imagem: "img/relogio_digital.webp",
        jogadores: "3-4", 
        idade: "10+",
        duracao: "60-120min",
        destaque: true
    },
    {
        id: 11,
        nome: "Ticket to Ride - Aventura Ferroviária",
        preco: 139.90,
        categoria: "estrategia",
        descricao: "Construa rotas ferroviárias conectando cidades pelos EUA.",
        imagem: "img/relogio_digital.webp",
        jogadores: "2-5",
        idade: "8+",
        duracao: "30-60min",
        destaque: false
    },

    // 👨‍👩‍👧‍👦 Jogos Familiares
    {
        id: 12,
        nome: "Jogo da Vida - Edição Atualizada",
        preco: 99.90,
        categoria: "familia",
        descricao: "Viva uma vida inteira cheia de escolhas e aventuras.",
        imagem: "img/relogio_digital.webp",
        jogadores: "2-6",
        idade: "8+", 
        duracao: "60min",
        destaque: true
    }
];

// Carrinho de compras
let carrinho = JSON.parse(localStorage.getItem('carrinho-mundo-jogos')) || [];

// Elementos DOM
const productsGrid = document.getElementById('productsGrid');
const cartItems = document.getElementById('cartItems');
const cartTotal = document.getElementById('cartTotal');
const cartCount = document.getElementById('cartCount');
const cartSidebar = document.getElementById('cartSidebar');
const overlay = document.getElementById('overlay');
const categoryButtons = document.querySelectorAll('.category-btn');
const searchInput = document.getElementById('searchInput');

// Inicialização
document.addEventListener('DOMContentLoaded', () => {
    renderProdutos();
    renderDestaques();
    updateCart();
    
    // Event Listeners
    document.getElementById('cartToggle').addEventListener('click', toggleCart);
    document.getElementById('closeCart').addEventListener('click', toggleCart);
    overlay.addEventListener('click', toggleCart);
    
    // Filtros de categoria
    categoryButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const categoria = btn.dataset.category;
            categoryButtons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            filtrarProdutos(categoria);
        });
    });

    // Busca em tempo real
    searchInput.addEventListener('input', (e) => {
        buscarProdutos(e.target.value);
    });

    // Tecla ESC fecha carrinho
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && cartSidebar.classList.contains('active')) {
            toggleCart();
        }
    });

    // Smooth scroll para links internos
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});

// Função para obter emoji de fallback
function getProductEmoji(produto) {
    const emojiMap = {
        'tabuleiro_profisional': '🏠',
        'relogio_digital': '⏰',
        'war-estrategia': '🌎',
        'detetive': '🕵️',
        'uno-flip': '🎴',
        'cards-against-humanity': '😈',
        'pandemic': '🦠',
        'the-mind': '🧠',
        'codenames': '🕶️',
        'dixit': '🌈',
        'catan': '🏝️',
        'ticket-to-ride': '🚂',
        'jogo-da-vida': '🎯'
    };
    
    const fileName = produto.imagem.split('/').pop().split('.')[0];
    return emojiMap[fileName] || '🎲';
}

// Renderizar produtos
function renderProdutos(produtosParaRender = produtos) {
    productsGrid.innerHTML = produtosParaRender.map(produto => `
        <div class="product-card" data-category="${produto.categoria}">
            ${produto.destaque ? '<div class="featured-badge">⭐ Destaque</div>' : ''}
            ${produto.precoOriginal ? `<div class="product-badge">-${Math.round((1 - produto.preco/produto.precoOriginal) * 100)}%</div>` : ''}
            
            <div class="product-image">
                <img src="${produto.imagem}" alt="${produto.nome}" 
                     onerror="this.style.display='none'; this.parentElement.innerHTML='${getProductEmoji(produto)}'; this.parentElement.style.fontSize='4rem'; this.parentElement.style.display='flex'; this.parentElement.style.alignItems='center'; this.parentElement.style.justifyContent='center';">
            </div>
            
            <h3 class="product-title">${produto.nome}</h3>
            
            <div class="product-price">
                R$ ${produto.preco.toFixed(2)}
                ${produto.precoOriginal ? `<span class="product-old-price">R$ ${produto.precoOriginal.toFixed(2)}</span>` : ''}
            </div>
            
            <p class="product-description">${produto.descricao}</p>
            
            <div style="font-size: 0.8rem; color: #666; margin-bottom: 1rem; text-align: left;">
                <strong>👥 ${produto.jogadores} jogadores</strong><br>
                <strong>🎂 ${produto.idade} anos</strong><br>
                <strong>⏱️ ${produto.duracao}</strong>
            </div>
            
            <button class="btn" onclick="adicionarAoCarrinho(${produto.id})">
                🛒 Adicionar ao Carrinho
            </button>
        </div>
    `).join('');
}

// Renderizar produtos em destaque
function renderDestaques() {
    const produtosDestaque = produtos.filter(produto => produto.destaque);
    const destaquesSection = document.querySelector('#ofertas');
    
    if (produtosDestaque.length > 0) {
        destaquesSection.innerHTML += `
            <div class="products-grid" style="margin-top: 2rem;">
                ${produtosDestaque.map(produto => `
                    <div class="product-card" data-category="${produto.categoria}">
                        ${produto.precoOriginal ? `<div class="product-badge">-${Math.round((1 - produto.preco/produto.precoOriginal) * 100)}%</div>` : ''}
                        
                        <div class="product-image">
                            <img src="${produto.imagem}" alt="${produto.nome}" 
                                 onerror="this.style.display='none'; this.parentElement.innerHTML='${getProductEmoji(produto)}'; this.parentElement.style.fontSize='4rem'; this.parentElement.style.display='flex'; this.parentElement.style.alignItems='center'; this.parentElement.style.justifyContent='center';">
                        </div>
                        
                        <h3 class="product-title">${produto.nome}</h3>
                        
                        <div class="product-price">
                            R$ ${produto.preco.toFixed(2)}
                            ${produto.precoOriginal ? `<span class="product-old-price">R$ ${produto.precoOriginal.toFixed(2)}</span>` : ''}
                        </div>
                        
                        <p class="product-description">${produto.descricao}</p>
                        
                        <div style="font-size: 0.8rem; color: #666; margin-bottom: 1rem; text-align: left;">
                            <strong>👥 ${produto.jogadores} jogadores</strong><br>
                            <strong>🎂 ${produto.idade} anos</strong><br>
                            <strong>⏱️ ${produto.duracao}</strong>
                        </div>
                        
                        <button class="btn" onclick="adicionarAoCarrinho(${produto.id})">
                            🛒 Adicionar ao Carrinho
                        </button>
                    </div>
                `).join('')}
            </div>
        `;
    }
}

// Filtrar produtos por categoria
function filtrarProdutos(categoria) {
    if (categoria === 'todos') {
        renderProdutos();
    } else {
        const produtosFiltrados = produtos.filter(produto => produto.categoria === categoria);
        renderProdutos(produtosFiltrados);
    }
}

// Buscar produtos
function buscarProdutos(termo) {
    if (termo.length === 0) {
        renderProdutos();
        return;
    }
    
    const termoLower = termo.toLowerCase();
    const produtosFiltrados = produtos.filter(produto => 
        produto.nome.toLowerCase().includes(termoLower) ||
        produto.descricao.toLowerCase().includes(termoLower) ||
        produto.categoria.toLowerCase().includes(termoLower)
    );
    
    if (produtosFiltrados.length === 0) {
        productsGrid.innerHTML = `
            <div style="grid-column: 1 / -1; text-align: center; padding: 3rem;">
                <div style="font-size: 4rem; margin-bottom: 1rem;">🔍</div>
                <h3 style="color: #6B7280; margin-bottom: 1rem;">Nenhum jogo encontrado</h3>
                <p style="color: #9CA3AF;">Tente buscar por "estratégia", "festa" ou "família"</p>
            </div>
        `;
    } else {
        renderProdutos(produtosFiltrados);
    }
}

// Funções do Carrinho
function adicionarAoCarrinho(id) {
    const produto = produtos.find(p => p.id === id);
    const itemNoCarrinho = carrinho.find(item => item.id === id);
    
    if (itemNoCarrinho) {
        itemNoCarrinho.quantidade++;
    } else {
        carrinho.push({
            ...produto,
            quantidade: 1
        });
    }
    
    updateCart();
    showNotification(`🎲 ${produto.nome} adicionado ao carrinho!`);
}

function removerDoCarrinho(id) {
    carrinho = carrinho.filter(item => item.id !== id);
    updateCart();
    showNotification('Item removido do carrinho', 'warning');
}

function alterarQuantidade(id, change) {
    const item = carrinho.find(item => item.id === id);
    if (item) {
        item.quantidade += change;
        if (item.quantidade <= 0) {
            removerDoCarrinho(id);
        } else {
            updateCart();
        }
    }
}

function updateCart() {
    // Salvar no localStorage
    localStorage.setItem('carrinho-mundo-jogos', JSON.stringify(carrinho));
    
    // Atualizar contador
    const totalItens = carrinho.reduce((total, item) => total + item.quantidade, 0);
    cartCount.textContent = totalItens;
    
    // Atualizar lista do carrinho
    if (carrinho.length === 0) {
        cartItems.innerHTML = `
            <div style="text-align: center; padding: 3rem; color: #6B7280;">
                <div style="font-size: 3rem; margin-bottom: 1rem;">🛒</div>
                <h3 style="margin-bottom: 0.5rem;">Carrinho vazio</h3>
                <p>Adicione alguns jogos incríveis!</p>
            </div>
        `;
    } else {
        cartItems.innerHTML = carrinho.map(item => `
            <div class="cart-item">
                <div class="cart-item-image">
                    <img src="${item.imagem}" alt="${item.nome}" 
                         style="width: 100%; height: 100%; object-fit: cover; border-radius: 6px;"
                         onerror="this.style.display='none'; this.parentElement.innerHTML='${getProductEmoji(item)}'; this.parentElement.style.fontSize='1.5rem'; this.parentElement.style.display='flex'; this.parentElement.style.alignItems='center'; this.parentElement.style.justifyContent='center';">
                </div>
                <div class="cart-item-details">
                    <div class="cart-item-title">${item.nome}</div>
                    <div class="cart-item-price">R$ ${item.preco.toFixed(2)}</div>
                </div>
                <div class="cart-item-quantity">
                    <button class="quantity-btn" onclick="alterarQuantidade(${item.id}, -1)">-</button>
                    <span style="min-width: 20px; text-align: center; font-weight: bold;">${item.quantidade}</span>
                    <button class="quantity-btn" onclick="alterarQuantidade(${item.id}, 1)">+</button>
                </div>
                <button class="quantity-btn" onclick="removerDoCarrinho(${item.id})" 
                        style="background: #EF4444; border-color: #EF4444; color: white;">🗑️</button>
            </div>
        `).join('');
    }
    
    // Atualizar total
    const total = carrinho.reduce((sum, item) => sum + (item.preco * item.quantidade), 0);
    const freteGratis = total >= 150;
    const totalFinal = total + (freteGratis ? 0 : 19.90);
    
    cartTotal.innerHTML = `
        <div style="margin-bottom: 0.5rem;">Subtotal: <strong>R$ ${total.toFixed(2)}</strong></div>
        ${freteGratis ? 
            '<div style="color: #10B981; font-weight: bold;">🚚 Frete grátis!</div>' : 
            `<div style="color: #6B7280; font-size: 0.9rem;">Frete: R$ 19,90</div>`
        }
        <div style="margin-top: 0.5rem; font-size: 1.2rem; border-top: 1px solid #E5E7EB; padding-top: 0.5rem;">
            Total: <strong style="color: #059669;">R$ ${totalFinal.toFixed(2)}</strong>
        </div>
    `;
}

function toggleCart() {
    cartSidebar.classList.toggle('active');
    overlay.classList.toggle('active');
    document.body.style.overflow = cartSidebar.classList.contains('active') ? 'hidden' : '';
}

function finalizarCompra() {
    if (carrinho.length === 0) {
        showNotification('Seu carrinho está vazio!', 'error');
        return;
    }
    
    const total = carrinho.reduce((sum, item) => sum + (item.preco * item.quantidade), 0);
    const frete = total >= 150 ? 0 : 19.90;
    const totalFinal = total + frete;
    
    // Simulação de finalização
    showNotification(`🎉 Compra finalizada! Total: R$ ${totalFinal.toFixed(2)}`, 'success');
    
    // Limpar carrinho após 2 segundos
    setTimeout(() => {
        carrinho = [];
        updateCart();
        toggleCart();
    }, 2000);
}

function showNotification(mensagem, tipo = 'success') {
    // Remover notificações anteriores
    const notificacoesAntigas = document.querySelectorAll('.custom-notification');
    notificacoesAntigas.forEach(notif => notif.remove());
    
    const notification = document.createElement('div');
    const bgColor = tipo === 'success' ? '#10B981' : 
                   tipo === 'error' ? '#EF4444' : 
                   tipo === 'warning' ? '#F59E0B' : '#059669';
    
    notification.className = 'custom-notification';
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${bgColor};
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 10px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        z-index: 1001;
        animation: slideInRight 0.3s ease;
        font-weight: 500;
        max-width: 300px;
        border-left: 4px solid rgba(255,255,255,0.3);
    `;
    notification.textContent = mensagem;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOutRight 0.3s ease';
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}

// Adicionar animações CSS dinamicamente
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    
    @keyframes slideOutRight {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
    
    .product-card {
        animation: slideInUp 0.5s ease-out;
    }
    
    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 8px;
        transition: transform 0.3s ease;
    }
    
    .product-card:hover .product-image img {
        transform: scale(1.05);
    }
    
    /* Estilos para o carrinho */
    .cart-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        border-bottom: 1px solid #E5E7EB;
    }
    
    .cart-item-image {
        width: 60px;
        height: 60px;
        border-radius: 8px;
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .cart-item-details {
        flex: 1;
    }
    
    .cart-item-title {
        font-weight: bold;
        margin-bottom: 0.25rem;
    }
    
    .cart-item-price {
        color: #059669;
        font-weight: bold;
    }
    
    .cart-item-quantity {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .quantity-btn {
        width: 30px;
        height: 30px;
        border: 1px solid #D1D5DB;
        background: white;
        border-radius: 6px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .quantity-btn:hover {
        background: #F3F4F6;
    }
`;
document.head.appendChild(style);

// Carregar produtos com delay para melhor experiência
setTimeout(() => {
    renderProdutos();
}, 100);