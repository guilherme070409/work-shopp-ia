// Dados dos produtos (serão extraídos do HTML gerado pelo PHP)
let produtos = [];
let carrinho = JSON.parse(localStorage.getItem('carrinho')) || [];

// Elementos DOM
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
    extrairProdutosDoHTML();
    updateCart();
    initEventListeners();
});

// Extrair produtos do HTML gerado pelo PHP
function extrairProdutosDoHTML() {
    const productCards = document.querySelectorAll('.product-card');
    produtos = [];
    
    productCards.forEach(card => {
        const productId = parseInt(card.querySelector('.btn-add-cart').getAttribute('data-product-id'));
        const productName = card.querySelector('.product-title').textContent;
        const productPrice = parseFloat(card.querySelector('.product-price').textContent.replace('R$', '').replace(',', '.').trim());
        const productImage = card.querySelector('.product-image img') ? card.querySelector('.product-image img').src : '';
        const productCategory = card.getAttribute('data-category');
        
        produtos.push({
            ID: productId,
            NOME: productName,
            PRECO: productPrice,
            IMG: productImage,
            CATEGORIA: productCategory
        });
    });
    
    console.log('Produtos extraídos do HTML:', produtos.length);
}

// Event Listeners
function initEventListeners() {
    const cartToggle = document.getElementById('cartToggle');
    const closeCart = document.getElementById('closeCart');
    
    if (cartToggle) cartToggle.addEventListener('click', toggleCart);
    if (closeCart) closeCart.addEventListener('click', toggleCart);
    if (elements.overlay) elements.overlay.addEventListener('click', toggleCart);
    
    // Filtros por categoria
    document.querySelectorAll('.category-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.category-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            filtrarProdutos(btn.dataset.category);
        });
    });

    // Busca
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            buscarProdutos(e.target.value);
        });
    }

    // Botões de adicionar ao carrinho
    document.querySelectorAll('.btn-add-cart').forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = parseInt(this.getAttribute('data-product-id'));
            adicionarAoCarrinho(productId);
        });
    });
}

// Filtros e busca
function filtrarProdutos(categoria) {
    const allCards = document.querySelectorAll('.product-card');
    
    allCards.forEach(card => {
        if (categoria === 'todos' || card.getAttribute('data-category') === categoria) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

function buscarProdutos(termo) {
    const allCards = document.querySelectorAll('.product-card');
    
    allCards.forEach(card => {
        const productName = card.querySelector('.product-title').textContent.toLowerCase();
        const productDescription = card.querySelector('.product-description').textContent.toLowerCase();
        
        if (!termo || productName.includes(termo.toLowerCase()) || productDescription.includes(termo.toLowerCase())) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

// Carrinho
function adicionarAoCarrinho(id) {
    const produto = produtos.find(p => p.ID === id);
    if (!produto) {
        showNotification('Produto não encontrado!', 'error');
        return;
    }

    const item = carrinho.find(item => item.ID === id);
    
    if (item) {
        item.quantidade++;
    } else {
        carrinho.push({
            ID: produto.ID,
            nome: produto.NOME,
            preco: produto.PRECO,
            imagem: produto.IMG,
            quantidade: 1
        });
    }
    
    updateCart();
    showNotification(`${produto.NOME} adicionado ao carrinho!`);
}

function removerDoCarrinho(id) {
    carrinho = carrinho.filter(item => item.ID !== id);
    updateCart();
}

function alterarQuantidade(id, change) {
    const item = carrinho.find(item => item.ID === id);
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
    
    // Contador do carrinho
    if (elements.cartCount) {
        elements.cartCount.textContent = carrinho.reduce((total, item) => total + item.quantidade, 0);
    }
    
    // Itens do carrinho
    if (elements.cartItems) {
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
                        ${item.imagem ? 
                            `<img src="${item.imagem}" alt="${item.nome}" onerror="this.style.display='none'; this.parentElement.innerHTML='🎲';">` :
                            '🎲'
                        }
                    </div>
                    <div class="cart-item-details">
                        <div class="cart-item-title">${item.nome}</div>
                        <div class="cart-item-price">R$ ${item.preco.toFixed(2)}</div>
                    </div>
                    <div class="cart-item-quantity">
                        <button class="quantity-btn" onclick="alterarQuantidade(${item.ID}, -1)">-</button>
                        <span class="quantity-number">${item.quantidade}</span>
                        <button class="quantity-btn" onclick="alterarQuantidade(${item.ID}, 1)">+</button>
                    </div>
                    <button class="quantity-btn remove-btn" onclick="removerDoCarrinho(${item.ID})">🗑️</button>
                </div>
            `).join('');
        }
    }
    
    // Total do carrinho
    if (elements.cartTotal) {
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
}

// UI
function toggleCart() {
    if (elements.cartSidebar && elements.overlay) {
        elements.cartSidebar.classList.toggle('active');
        elements.overlay.classList.toggle('active');
        document.body.style.overflow = elements.cartSidebar.classList.contains('active') ? 'hidden' : '';
    }
}

function finalizarCompra() {
    if (!carrinho.length) {
        showNotification('Carrinho vazio!', 'error');
        return;
    }
    
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

// CSS para animações
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    
    @keyframes slideOut {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
    
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
    
    .empty-cart {
        text-align: center;
        padding: 3rem;
        color: #6B7280;
    }
    
    .empty-cart div:first-child {
        font-size: 4rem;
        margin-bottom: 1rem;
    }
`;
document.head.appendChild(style);