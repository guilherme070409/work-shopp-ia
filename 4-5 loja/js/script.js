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

// Carrinho
function adicionarAoCarrinho(id) {
    const produto = produtos.find(p => p.id === id);
    const item = carrinho.find(item => item.id === id);
    
    if (item) item.quantidade++;
    else carrinho.push({...produto, quantidade: 1});
    
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
        item.quantidade <= 0 ? removerDoCarrinho(id) : updateCart();
    }
}

function updateCart() {
    localStorage.setItem('carrinho', JSON.stringify(carrinho));
    
    // Contador
    elements.cartCount.textContent = carrinho.reduce((total, item) => total + item.quantidade, 0);
    
    // Itens do carrinho
    elements.cartItems.innerHTML = carrinho.length ? carrinho.map(item => `
        <div class="cart-item">
            <img src="${item.imagem}" alt="${item.nome}">
            <div class="cart-details">
                <div>${item.nome}</div>
                <div>R$ ${item.preco.toFixed(2)}</div>
            </div>
            <div class="cart-quantity">
                <button onclick="alterarQuantidade(${item.id}, -1)">-</button>
                <span>${item.quantidade}</span>
                <button onclick="alterarQuantidade(${item.id}, 1)">+</button>
            </div>
            <button class="remove-btn" onclick="removerDoCarrinho(${item.id})">🗑️</button>
        </div>
    `).join('') : `
        <div class="empty-cart">
            <div>🛒</div>
            <h3>Carrinho vazio</h3>
        </div>
    `;
    
    // Total
    const total = carrinho.reduce((sum, item) => sum + (item.preco * item.quantidade), 0);
    const frete = total >= 150 ? 0 : 19.90;
    
    elements.cartTotal.innerHTML = `
        <div>Subtotal: R$ ${total.toFixed(2)}</div>
        <div>${frete ? `Frete: R$ ${frete.toFixed(2)}` : '🚚 Frete grátis!'}</div>
        <div class="total-final">Total: R$ ${(total + frete).toFixed(2)}</div>
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
    showNotification(`Compra finalizada! Total: R$ ${total.toFixed(2)}`, 'success');
    
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
        position: fixed; top: 20px; right: 20px; 
        background: ${tipo === 'success' ? '#10B981' : '#EF4444'}; 
        color: white; padding: 1rem; border-radius: 8px; 
        z-index: 1001; animation: slideIn 0.3s ease;
    `;
    notification.textContent = mensagem;
    document.body.appendChild(notification);
    
    setTimeout(() => notification.remove(), 3000);
}

// API de CEP (opcional)
async function calcularFrete(cep) {
    try {
        const response = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
        const endereco = await response.json();
        if (!endereco.erro) {
            showNotification(`Frete calculado para ${endereco.localidade}-${endereco.uf}`);
        }
    } catch (error) {
        showNotification('Erro ao calcular frete', 'error');
    }
}

// CSS mínimo
const style = document.createElement('style');
style.textContent = `
    .product-card { background: white; border-radius: 12px; padding: 1rem; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
    .product-image { height: 200px; overflow: hidden; border-radius: 8px; }
    .product-image img { width: 100%; height: 100%; object-fit: cover; }
    .product-price { font-size: 1.25rem; font-weight: bold; color: #059669; margin: 0.5rem 0; }
    .old-price { text-decoration: line-through; color: #999; font-size: 0.9rem; margin-left: 0.5rem; }
    .product-info { display: flex; gap: 1rem; margin: 1rem 0; font-size: 0.8rem; color: #666; }
    .cart-item { display: flex; align-items: center; gap: 1rem; padding: 1rem; border-bottom: 1px solid #eee; }
    .cart-details { flex: 1; }
    .cart-quantity { display: flex; align-items: center; gap: 0.5rem; }
    .cart-quantity button { width: 30px; height: 30px; border: 1px solid #ddd; background: white; border-radius: 4px; }
    .remove-btn { background: #ef4444; color: white; border: none; padding: 0.5rem; border-radius: 4px; }
    .notification { animation: slideIn 0.3s ease; }
    @keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
`;
document.head.appendChild(style);