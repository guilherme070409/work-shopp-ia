// Dados dos produtos
const produtos = [
    { id: 1, nome: "Banco Imobiliário Special Edition", preco: 89.90, precoOriginal: 109.90, categoria: "classicos", descricao: "Edição especial do clássico jogo.", imagem: "img/Banco_imobiliario.webp", jogadores: "2-6", idade: "8+", duracao: "60-120min", destaque: true },
    { id: 2, nome: "War Estratégia Global", preco: 129.90, categoria: "estrategia", descricao: "Conquiste territórios e domine o mundo.", imagem: "img/war.webp", jogadores: "2-6", idade: "10+", duracao: "90-180min", destaque: true },
    { id: 3, nome: "Detetive Investigação", preco: 69.90, categoria: "misterio", descricao: "Resolva mistérios e descubra o culpado.", imagem: "img/detetive.jpg", jogadores: "3-6", idade: "8+", duracao: "45-60min", destaque: false },
    { id: 4, nome: "Uno Flip Edition", preco: 39.90, categoria: "cartas", descricao: "Versão emocionante do Uno.", imagem: "img/Uno_Flip.jpg", jogadores: "2-10", idade: "7+", duracao: "15-30min", destaque: true },
    { id: 5, nome: "Cards Against Humanity", preco: 79.90, categoria: "cartas", descricao: "Jogo de cartas hilário.", imagem: "img/cards_against.jpg", jogadores: "4-20+", idade: "17+", duracao: "30-90min", destaque: false },
    { id: 6, nome: "Pandemic: Salve o Mundo", preco: 149.90, categoria: "cooperativo", descricao: "Trabalhem juntos para curar doenças.", imagem: "img/pandemic.webp", jogadores: "2-4", idade: "8+", duracao: "45-60min", destaque: true },
    { id: 7, nome: "The Mind - Desafio Mental", preco: 59.90, categoria: "cooperativo", descricao: "Jogo cooperativo onde a comunicação é proibida!", imagem: "img/the_mind.jpg", jogadores: "2-4", idade: "8+", duracao: "20min", destaque: false },
    { id: 8, nome: "Codenames - Desafio em Duplas", preco: 89.90, categoria: "festa", descricao: "Jogo de palavras secreto.", imagem: "img/condame.webp", jogadores: "4-8+", idade: "10+", duracao: "15min", destaque: true },
    { id: 9, nome: "Dixit - Imaginação", preco: 119.90, categoria: "festa", descricao: "Jogo de imaginação criativa.", imagem: "img/dixit.jpg", jogadores: "3-6", idade: "8+", duracao: "30min", destaque: false },
    { id: 10, nome: "Catan - Colonizadores", preco: 159.90, categoria: "estrategia", descricao: "Construa cidades e negocie recursos.", imagem: "img/catan.png", jogadores: "3-4", idade: "10+", duracao: "60-120min", destaque: true },
    { id: 11, nome: "Ticket to Ride", preco: 139.90, categoria: "estrategia", descricao: "Construa rotas ferroviárias.", imagem: "img/ticket.jpg", jogadores: "2-5", idade: "8+", duracao: "30-60min", destaque: false },
    { id: 12, nome: "Jogo da Vida", preco: 99.90, categoria: "familia", descricao: "Viva uma vida cheia de aventuras.", imagem: "img/jogo_da_vida.jpg", jogadores: "2-6", idade: "8+", duracao: "60min", destaque: true }
];

// Carrinho e elementos DOM
let carrinho = JSON.parse(localStorage.getItem('carrinho')) || [];
const elements = {
    productsGrid: document.getElementById('productsGrid'),
    cartItems: document.getElementById('cartItems'),
    cartTotal: document.getElementById('cartTotal'),
    cartCount: document.getElementById('cartCount'),
    cartSidebar: document.getElementById('cartSidebar'),
    overlay: document.getElementById('overlay')
};

// Inicialização
document.addEventListener('DOMContentLoaded', () => {
    renderProdutos();
    updateCart();
    initEventListeners();
});

// Event Listeners
function initEventListeners() {
    document.getElementById('cartToggle').addEventListener('click', toggleCart);
    document.getElementById('closeCart').addEventListener('click', toggleCart);
    elements.overlay.addEventListener('click', toggleCart);
    
    document.querySelectorAll('.category-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.category-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            filtrarProdutos(btn.dataset.category);
        });
    });

    document.getElementById('searchInput').addEventListener('input', (e) => {
        buscarProdutos(e.target.value);
    });
}

// Renderização de produtos
function renderProdutos(produtosParaRender = produtos) {
    elements.productsGrid.innerHTML = produtosParaRender.map(produto => `
        <div class="product-card" data-category="${produto.categoria}">
            ${produto.destaque ? '<div class="featured-badge">⭐</div>' : ''}
            ${produto.precoOriginal ? `<div class="product-badge">-${Math.round((1 - produto.preco/produto.precoOriginal) * 100)}%</div>` : ''}
            
            <div class="product-image">
                <img src="${produto.imagem}" alt="${produto.nome}" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZjNmNGY2Ii8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtc2l6ZT0iMjAiIGZpbGw9IiM5Y2EzYWYiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIj7imYvinaTwn5GgPC90ZXh0Pjwvc3ZnPg=='">
            </div>
            
            <div class="product-content">
                <h3>${produto.nome}</h3>
                <div class="product-price">
                    R$ ${produto.preco.toFixed(2)}
                    ${produto.precoOriginal ? `<span class="old-price">R$ ${produto.precoOriginal.toFixed(2)}</span>` : ''}
                </div>
                <p>${produto.descricao}</p>
                <div class="product-info">
                    <span>👥 ${produto.jogadores}</span>
                    <span>🎂 ${produto.idade}</span>
                    <span>⏱️ ${produto.duracao}</span>
                </div>
                <button class="btn" onclick="adicionarAoCarrinho(${produto.id})">🛒 Add Carrinho</button>
            </div>
        </div>
    `).join('');
}

// Filtros e busca
function filtrarProdutos(categoria) {
    renderProdutos(categoria === 'todos' ? produtos : produtos.filter(p => p.categoria === categoria));
}

function buscarProdutos(termo) {
    if (!termo) return renderProdutos();
    const filtrados = produtos.filter(p => 
        p.nome.toLowerCase().includes(termo.toLowerCase()) ||
        p.descricao.toLowerCase().includes(termo.toLowerCase())
    );
    elements.productsGrid.innerHTML = filtrados.length ? renderProdutos(filtrados) : `
        <div class="no-products">
            <div>🔍</div>
            <h3>Nenhum jogo encontrado</h3>
            <p>Tente outros termos</p>
        </div>
    `;
}

// Carrinho - CORRIGIDO
function adicionarAoCarrinho(id) {
    const produto = produtos.find(p => p.id === id);
    const item = carrinho.find(item => item.id === id);
    
    if (item) {
        item.quantidade++;
    } else {
        carrinho.push({
            ...produto,
            quantidade: 1
        });
    }
    
    updateCart();
    showNotification(`${produto.nome} adicionado!`);
}

function removerDoCarrinho(id) {
    carrinho = carrinho.filter(item => item.id !== id);
    updateCart();
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
    localStorage.setItem('carrinho', JSON.stringify(carrinho));
    
    // Contador
    elements.cartCount.textContent = carrinho.reduce((total, item) => total + item.quantidade, 0);
    
    // Itens do carrinho - CORRIGIDO
    if (carrinho.length === 0) {
        elements.cartItems.innerHTML = `
            <div class="empty-cart">
                <div>🛒</div>
                <h3>Carrinho vazio</h3>
                <p>Adicione alguns jogos incríveis!</p>
            </div>
        `;
    } else {
        elements.cartItems.innerHTML = carrinho.map(item => `
            <div class="cart-item">
                <div class="cart-item-image">
                    <img src="${item.imagem}" alt="${item.nome}" 
                         onerror="this.style.display='none'; this.parentElement.innerHTML='🎲'; this.parentElement.style.fontSize='1.5rem'; this.parentElement.style.display='flex'; this.parentElement.style.alignItems='center'; this.parentElement.style.justifyContent='center';">
                </div>
                <div class="cart-item-details">
                    <div class="cart-item-title">${item.nome}</div>
                    <div class="cart-item-price">R$ ${item.preco.toFixed(2)}</div>
                </div>
                <div class="cart-item-quantity">
                    <button class="quantity-btn" onclick="alterarQuantidade(${item.id}, -1)">-</button>
                    <span class="quantity-number">${item.quantidade}</span>
                    <button class="quantity-btn" onclick="alterarQuantidade(${item.id}, 1)">+</button>
                </div>
                <button class="quantity-btn remove-btn" onclick="removerDoCarrinho(${item.id})">🗑️</button>
            </div>
        `).join('');
    }
    
    // Total - CORRIGIDO
    const total = carrinho.reduce((sum, item) => sum + (item.preco * item.quantidade), 0);
    const freteGratis = total >= 150;
    const frete = freteGratis ? 0 : 19.90;
    const totalFinal = total + frete;
    
    elements.cartTotal.innerHTML = `
        <div class="cart-subtotal">
            <span>Subtotal:</span>
            <strong>R$ ${total.toFixed(2)}</strong>
        </div>
        ${freteGratis ? 
            '<div class="free-shipping">🚚 Frete grátis!</div>' : 
            '<div class="shipping-cost">Frete: R$ 19,90</div>'
        }
        <div class="cart-total-final">
            <span>Total:</span>
            <strong class="total-price">R$ ${totalFinal.toFixed(2)}</strong>
        </div>
    `;
}

// UI
function toggleCart() {
    elements.cartSidebar.classList.toggle('active');
    elements.overlay.classList.toggle('active');
    document.body.style.overflow = elements.cartSidebar.classList.contains('active') ? 'hidden' : '';
}

function finalizarCompra() {
    if (!carrinho.length) return showNotification('Carrinho vazio!', 'error');
    
    const total = carrinho.reduce((sum, item) => sum + (item.preco * item.quantidade), 0);
    showNotification(`🎉 Compra finalizada! Total: R$ ${total.toFixed(2)}`, 'success');
    
    setTimeout(() => {
        carrinho = [];
        updateCart();
        toggleCart();
    }, 2000);
}

function showNotification(mensagem, tipo = 'success') {
    const notification = document.createElement('div');
    notification.className = 'notification';
    notification.style.cssText = `
        position: fixed; 
        top: 20px; 
        right: 20px; 
        background: ${tipo === 'success' ? '#10B981' : '#EF4444'}; 
        color: white; 
        padding: 1rem 1.5rem; 
        border-radius: 8px; 
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        z-index: 1001; 
        animation: slideIn 0.3s ease;
        font-weight: 500;
    `;
    notification.textContent = mensagem;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// CSS para o carrinho - ADICIONAR
const cartStyle = document.createElement('style');
cartStyle.textContent = `
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
        flex-shrink: 0;
        overflow: hidden;
    }
    
    .cart-item-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .cart-item-details {
        flex: 1;
        min-width: 0;
    }
    
    .cart-item-title {
        font-weight: bold;
        margin-bottom: 0.25rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .cart-item-price {
        color: #059669;
        font-weight: bold;
    }
    
    .cart-item-quantity {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-shrink: 0;
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
        transition: background-color 0.2s;
    }
    
    .quantity-btn:hover {
        background: #F3F4F6;
    }
    
    .remove-btn {
        background: #EF4444;
        border-color: #EF4444;
        color: white;
    }
    
    .remove-btn:hover {
        background: #DC2626;
    }
    
    .quantity-number {
        min-width: 20px;
        text-align: center;
        font-weight: bold;
    }
    
    .cart-subtotal, .cart-total-final {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
    }
    
    .free-shipping {
        color: #10B981;
        font-weight: bold;
        margin-bottom: 0.5rem;
    }
    
    .shipping-cost {
        color: #6B7280;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }
    
    .total-price {
        color: #059669;
        font-size: 1.2rem;
    }
    
    .empty-cart {
        text-align: center;
        padding: 3rem;
        color: #6B7280;
    }
    
    .empty-cart div:first-child {
        font-size: 4rem;
        margin-bottom: 1rem;
    }
    
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    
    @keyframes slideOut {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
`;
document.head.appendChild(cartStyle);
// ==================== DASHBOARD ====================

// Sistema de Pedidos para Dashboard
let pedidos = JSON.parse(localStorage.getItem('pedidos-mundo-jogos')) || [];

// Funções do Dashboard
function toggleAdminPanel() {
    const dashboard = document.getElementById('dashboard');
    const overlay = document.getElementById('dashboardOverlay');
    
    dashboard.classList.toggle('active');
    overlay.classList.toggle('active');
    document.body.style.overflow = dashboard.classList.contains('active') ? 'hidden' : '';
    
    // Atualizar dashboard quando abrir
    if (dashboard.classList.contains('active')) {
        atualizarDashboard();
    }
}

// Atualizar Dashboard
function atualizarDashboard() {
    atualizarEstatisticas();
    atualizarTopProdutos();
    atualizarVendasPorCategoria();
    atualizarPedidosRecentes();
}

function atualizarEstatisticas() {
    const totalVendas = pedidos.reduce((total, pedido) => total + pedido.total, 0);
    const totalPedidos = pedidos.length;
    const totalProdutos = produtos.length;

    document.getElementById('totalVendas').textContent = `R$ ${totalVendas.toFixed(2)}`;
    document.getElementById('totalPedidos').textContent = totalPedidos;
    document.getElementById('totalProdutos').textContent = totalProdutos;
    document.getElementById('produtosEstoque').textContent = totalProdutos;
}

function atualizarTopProdutos() {
    const vendasAgrupadas = {};
    
    pedidos.forEach(pedido => {
        pedido.itens.forEach(item => {
            if (!vendasAgrupadas[item.id]) {
                vendasAgrupadas[item.id] = {
                    ...item,
                    quantidadeTotal: 0
                };
            }
            vendasAgrupadas[item.id].quantidadeTotal += item.quantidade;
        });
    });

    const topProdutos = Object.values(vendasAgrupadas)
        .sort((a, b) => b.quantidadeTotal - a.quantidadeTotal)
        .slice(0, 5);

    const topProductsContainer = document.getElementById('topProducts');
    
    if (topProdutos.length === 0) {
        topProductsContainer.innerHTML = '<p style="color: #6B7280; text-align: center;">Nenhuma venda registrada ainda</p>';
    } else {
        topProductsContainer.innerHTML = topProdutos.map(produto => `
            <div class="top-product-item">
                <div class="top-product-image">
                    <img src="${produto.imagem}" alt="${produto.nome}" 
                         onerror="this.style.display='none'; this.parentElement.innerHTML='🎲'; this.parentElement.style.fontSize='1.2rem'; this.parentElement.style.display='flex'; this.parentElement.style.alignItems='center'; this.parentElement.style.justifyContent='center';">
                </div>
                <div class="top-product-info">
                    <div class="top-product-name">${produto.nome}</div>
                    <div class="top-product-sales">${produto.quantidadeTotal} vendas</div>
                </div>
            </div>
        `).join('');
    }
}

function atualizarVendasPorCategoria() {
    const vendasCategoria = {};
    
    pedidos.forEach(pedido => {
        pedido.itens.forEach(item => {
            const categoria = produtos.find(p => p.id === item.id)?.categoria || 'outros';
            if (!vendasCategoria[categoria]) {
                vendasCategoria[categoria] = 0;
            }
            vendasCategoria[categoria] += item.preco * item.quantidade;
        });
    });

    const totalVendas = Object.values(vendasCategoria).reduce((a, b) => a + b, 0);
    const categorySalesContainer = document.getElementById('salesByCategory');
    
    if (totalVendas === 0) {
        categorySalesContainer.innerHTML = '<p style="color: #6B7280; text-align: center;">Nenhuma venda por categoria</p>';
    } else {
        categorySalesContainer.innerHTML = Object.entries(vendasCategoria)
            .sort(([,a], [,b]) => b - a)
            .map(([categoria, valor]) => {
                const percentual = totalVendas > 0 ? (valor / totalVendas) * 100 : 0;
                return `
                    <div class="category-item">
                        <span class="category-name">${categoria}</span>
                        <div class="category-bar">
                            <div class="category-fill" style="width: ${percentual}%"></div>
                        </div>
                        <span class="category-value">R$ ${valor.toFixed(2)}</span>
                    </div>
                `;
            }).join('');
    }
}

function atualizarPedidosRecentes() {
    const pedidosRecentes = pedidos.slice(-5).reverse();
    const recentOrdersContainer = document.getElementById('recentOrders');
    
    if (pedidosRecentes.length === 0) {
        recentOrdersContainer.innerHTML = '<p style="color: #6B7280; text-align: center;">Nenhum pedido recente</p>';
    } else {
        recentOrdersContainer.innerHTML = pedidosRecentes.map(pedido => `
            <div class="order-item">
                <div class="order-info">
                    <h4>Pedido #${pedido.numero}</h4>
                    <p>${pedido.data} • ${pedido.itens.length} itens</p>
                </div>
                <div class="order-total">R$ ${pedido.total.toFixed(2)}</div>
            </div>
        `).join('');
    }
}

// Funções de Controle Admin
function exportarDados() {
    const dados = {
        pedidos: pedidos,
        produtos: produtos,
        dataExportacao: new Date().toISOString()
    };
    
    const blob = new Blob([JSON.stringify(dados, null, 2)], { type: 'application/json' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `dados-mundo-jogos-${new Date().toISOString().split('T')[0]}.json`;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
    
    showNotification('📤 Dados exportados com sucesso!', 'success');
}

function limparDados() {
    if (confirm('Tem certeza que deseja limpar todos os dados? Esta ação não pode ser desfeita.')) {
        pedidos = [];
        localStorage.removeItem('pedidos-mundo-jogos');
        atualizarDashboard();
        showNotification('🗑️ Dados limpos com sucesso!', 'success');
    }
}

function gerarRelatorio() {
    const totalVendas = pedidos.reduce((total, pedido) => total + pedido.total, 0);
    const totalPedidos = pedidos.length;
    
    const relatorio = `
RELATÓRIO MUNDO DOS JOGOS
========================
Data: ${new Date().toLocaleDateString('pt-BR')}

📊 ESTATÍSTICAS GERAIS:
• Total em Vendas: R$ ${totalVendas.toFixed(2)}
• Pedidos Realizados: ${totalPedidos}
• Produtos no Catálogo: ${produtos.length}

📈 PRÓXIMOS PASSOS:
• Analisar categorias com melhor desempenho
• Reabastecer estoque dos produtos mais vendidos
• Promover produtos com menor movimento
    `;
    
    const blob = new Blob([relatorio], { type: 'text/plain' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `relatorio-mundo-jogos-${new Date().toISOString().split('T')[0]}.txt`;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
    
    showNotification('📊 Relatório gerado com sucesso!', 'success');
}

// MODIFICAR a função finalizarCompra existente para registrar pedidos
function finalizarCompra() {
    if (!carrinho.length) return showNotification('Carrinho vazio!', 'error');
    
    const total = carrinho.reduce((sum, item) => sum + (item.preco * item.quantidade), 0);
    const frete = total >= 150 ? 0 : 19.90;
    const totalFinal = total + frete;
    
    // Criar pedido para o dashboard
    const pedido = {
        numero: 'PED' + Date.now(),
        data: new Date().toLocaleDateString('pt-BR'),
        itens: [...carrinho],
        subtotal: total,
        frete: frete,
        total: totalFinal,
        status: 'concluído'
    };
    
    // Adicionar aos pedidos do dashboard
    pedidos.push(pedido);
    localStorage.setItem('pedidos-mundo-jogos', JSON.stringify(pedidos));
    
    showNotification(`🎉 Compra finalizada! Pedido #${pedido.numero}`, 'success');
    
    // Limpar carrinho
    setTimeout(() => {
        carrinho = [];
        updateCart();
        toggleCart();
    }, 2000);
}

// Event listener para o overlay do dashboard
document.getElementById('dashboardOverlay').addEventListener('click', toggleAdminPanel);