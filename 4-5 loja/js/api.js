// Dados dos produtos - Loja de Jogos de Tabuleiro
const produtos = [
    // ... (seus produtos anteriores permanecem iguais)
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
document.addEventListener('DOMContentLoaded', async () => {
    renderProdutos();
    updateCart();
    adicionarBuscaCEPAoCarrinho();
    
    // Carregar cotação de dólar
    await mostrarCotacaoDolar();
    
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
});

// === API ÚTIL 1: BUSCAR CEP E CALCULAR FRETE ===
async function buscarCEP(cep) {
    try {
        showNotification('📍 Buscando seu endereço...', 'info');
        
        cep = cep.replace(/\D/g, '');
        if (cep.length !== 8) {
            throw new Error('Digite um CEP válido com 8 números');
        }

        const response = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
        
        if (!response.ok) {
            throw new Error('Erro ao conectar com o serviço de CEP');
        }

        const endereco = await response.json();
        
        if (endereco.erro) {
            throw new Error('CEP não encontrado');
        }

        // Calcular frete baseado no estado
        const subtotal = carrinho.reduce((sum, item) => sum + (item.preco * item.quantidade), 0);
        const freteInfo = calcularFrete(endereco.uf, subtotal);
        
        // Mostrar resultados para o usuário
        mostrarResultadoFrete(endereco, freteInfo);
        
        showNotification(`✅ Entregamos em ${endereco.localidade}-${endereco.uf}`, 'success');
        return endereco;
        
    } catch (error) {
        showNotification(`❌ ${error.message}`, 'error');
        return null;
    }
}

function calcularFrete(estado, valorCompra) {
    const fretes = {
        'SP': { valor: 12.90, prazo: '3-5 dias úteis' },
        'RJ': { valor: 15.90, prazo: '4-6 dias úteis' },
        'MG': { valor: 16.90, prazo: '5-7 dias úteis' },
        'RS': { valor: 18.90, prazo: '6-8 dias úteis' },
        'OUTROS': { valor: 19.90, prazo: '7-10 dias úteis' }
    };
    
    const freteInfo = fretes[estado] || fretes.OUTROS;
    
    // Frete grátis para compras acima de R$ 150
    if (valorCompra >= 150) {
        return { 
            valor: 0, 
            prazo: freteInfo.prazo, 
            freteGratis: true,
            mensagem: '🎉 Parabéns! Você ganhou frete grátis!' 
        };
    }
    
    return { 
        ...freteInfo, 
        freteGratis: false,
        mensagem: `💡 Compre mais R$ ${(150 - valorCompra).toFixed(2)} e ganhe frete grátis!`
    };
}

function mostrarResultadoFrete(endereco, freteInfo) {
    const resultadoElement = document.getElementById('resultadoFrete');
    
    resultadoElement.innerHTML = `
        <div style="background: #F0F9FF; padding: 1rem; border-radius: 8px; border-left: 4px solid #0EA5E9;">
            <div style="display: flex; align-items: start; gap: 0.5rem; margin-bottom: 0.5rem;">
                <span style="font-size: 1.2rem;">📍</span>
                <div>
                    <strong>Entregaremos em:</strong><br>
                    ${endereco.logradouro ? endereco.logradouro + ', ' : ''}${endereco.bairro}<br>
                    ${endereco.localidade} - ${endereco.uf}
                </div>
            </div>
            
            <div style="display: flex; align-items: start; gap: 0.5rem; margin-bottom: 0.5rem;">
                <span style="font-size: 1.2rem;">🚚</span>
                <div>
                    <strong>Frete: ${freteInfo.freteGratis ? 
                        '<span style="color: #059669;">GRÁTIS</span>' : 
                        `R$ ${freteInfo.valor.toFixed(2)}`}
                    </strong><br>
                    <small>Prazo de entrega: ${freteInfo.prazo}</small>
                </div>
            </div>
            
            ${freteInfo.mensagem ? `
                <div style="color: #8B5CF6; font-size: 0.9rem; margin-top: 0.5rem;">
                    ${freteInfo.mensagem}
                </div>
            ` : ''}
        </div>
    `;
}

// === API ÚTIL 2: COTAÇÃO DO DÓLAR ===
async function mostrarCotacaoDolar() {
    try {
        const response = await fetch('https://api.exchangerate-api.com/v4/latest/BRL');
        
        if (!response.ok) {
            throw new Error('Erro ao buscar cotação');
        }
        
        const data = await response.json();
        const taxaDolar = data.rates.USD;
        
        // Atualizar preços em dólar
        atualizarPrecosDolar(taxaDolar);
        
        // Mostrar cotação atual no footer
        const valorDolar = (1 / taxaDolar).toFixed(2);
        atualizarCotacaoFooter(valorDolar);
        
        return taxaDolar;
        
    } catch (error) {
        console.error('Erro na cotação:', error);
        // Usar valor fallback
        atualizarPrecosDolar(0.18);
        return 0.18;
    }
}

function atualizarPrecosDolar(taxaDolar) {
    document.querySelectorAll('.product-card').forEach(card => {
        const precoReal = card.querySelector('.product-price');
        const precoTexto = precoReal.textContent;
        const match = precoTexto.match(/R\$\s*(\d+[.,]\d+)/);
        
        if (match) {
            const valorReal = parseFloat(match[1].replace(',', '.'));
            const valorDolar = (valorReal * taxaDolar).toFixed(2);
            
            // Adicionar ou atualizar preço em dólar
            let precoDolarElement = card.querySelector('.preco-dolar');
            if (!precoDolarElement) {
                precoDolarElement = document.createElement('small');
                precoDolarElement.className = 'preco-dolar';
                precoDolarElement.style.cssText = 'color: #6B7280; font-size: 0.8rem; display: block; margin-top: 0.25rem;';
                precoReal.appendChild(precoDolarElement);
            }
            
            precoDolarElement.textContent = `≈ $${valorDolar} USD`;
        }
    });
}

function atualizarCotacaoFooter(valorDolar) {
    const footer = document.querySelector('footer');
    if (footer) {
        let cotacaoElement = document.getElementById('cotacao-dolar');
        if (!cotacaoElement) {
            cotacaoElement = document.createElement('div');
            cotacaoElement.id = 'cotacao-dolar';
            cotacaoElement.style.cssText = 'text-align: center; padding: 0.5rem; background: #374151; color: white; font-size: 0.9rem;';
            footer.parentNode.insertBefore(cotacaoElement, footer);
        }
        
        cotacaoElement.innerHTML = `💱 Cotação do dólar: R$ ${valorDolar} | Atualizado em ${new Date().toLocaleDateString('pt-BR')}`;
    }
}

// === INTEGRAR NO CARRINHO ===
function adicionarBuscaCEPAoCarrinho() {
    const cartFooter = document.querySelector('.cart-footer');
    if (cartFooter) {
        cartFooter.insertAdjacentHTML('beforebegin', `
            <div style="padding: 1rem; border-bottom: 1px solid #E5E7EB;">
                <h4 style="margin-bottom: 0.5rem; color: #1F2937;">📍 Calcular Frete e Prazo</h4>
                <div style="display: flex; gap: 0.5rem; margin-bottom: 0.5rem;">
                    <input type="text" id="cepInput" placeholder="Digite seu CEP (ex: 01311-000)" 
                           style="padding: 0.5rem; border: 1px solid #D1D5DB; border-radius: 6px; flex: 1;"
                           maxlength="9">
                    <button onclick="calcularFreteCEP()" class="btn" 
                            style="background: #8B5CF6; color: white; white-space: nowrap;">
                        Calcular Frete
                    </button>
                </div>
                <div id="resultadoFrete"></div>
            </div>
        `);
        
        // Formatar CEP automaticamente (XXXXX-XXX)
        const cepInput = document.getElementById('cepInput');
        cepInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 5) {
                value = value.substring(0,5) + '-' + value.substring(5,8);
            }
            e.target.value = value;
        });
        
        // Permitir Enter para calcular
        cepInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                calcularFreteCEP();
            }
        });
    }
}

async function calcularFreteCEP() {
    const cepInput = document.getElementById('cepInput');
    const cep = cepInput.value;
    
    if (!cep) {
        showNotification('❌ Por favor, digite um CEP', 'error');
        return;
    }
    
    await buscarCEP(cep);
}

// === FUNÇÕES EXISTENTES (mantenha as suas) ===
function renderProdutos(produtosParaRender = produtos) {
    // ... (seu código atual de renderização)
}

function updateCart() {
    // ... (seu código atual do carrinho)
}

function toggleCart() {
    // ... (seu código atual)
}

function finalizarCompra() {
    // ... (seu código atual)
}

function showNotification(mensagem, tipo = 'success') {
    // ... (seu código atual de notificações)
}

// ... (outras funções do seu código)