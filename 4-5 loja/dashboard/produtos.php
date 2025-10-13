<?php
session_start();
require_once '../model/dashmodel.php';

try {
    $dashboardModel = new DashboardModel();
    
    // Processar formulário de cadastro
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nome = $_POST['nome'];
        $preco = $_POST['preco'];
        $descricao = $_POST['descricao'];
        $pesoa = $_POST['pesoa'] ?? '';
        $idade = $_POST['idade'] ?? '';
        $tempo = $_POST['tempo'] ?? '';
        $categoria = $_POST['categoria'];
        
        // Upload da imagem
        $imagem = '';
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === 0) {
            $pasta_upload = '../lojinha/img/';
            
            // Criar pasta se não existir
            if (!is_dir($pasta_upload)) {
                mkdir($pasta_upload, 0777, true);
            }
            
            $nome_arquivo = uniqid() . '_' . basename($_FILES['imagem']['name']);
            $caminho_completo = $pasta_upload . $nome_arquivo;
            
            if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho_completo)) {
                // Salvar caminho relativo para a lojinha
                $imagem = 'img/' . $nome_arquivo;
                echo "<!-- Debug: Imagem salva em: $imagem -->";
            } else {
                $erro_upload = "Erro ao fazer upload da imagem!";
            }
        }
        
        // Preparar dados para inserção
        $dadosProduto = [
            'nome' => $nome,
            'preco' => $preco,
            'descricao' => $descricao,
            'pesoa' => $pesoa,
            'idade' => $idade,
            'tempo' => $tempo,
            'imagem' => $imagem,
            'categoria' => $categoria
        ];
        
        // Inserir no banco usando o método do model
        $sucesso = $dashboardModel->cadastrarProduto($dadosProduto);

        if ($sucesso) {
            header("Location: produtos.php?sucesso=1");
            exit();
        } else {
            $erro = "Erro ao cadastrar produto!";
        }
    }
    
    // Buscar produtos e categorias
    $produtos = $dashboardModel->getProdutosRecentes();
    $categorias = $dashboardModel->getCategorias();
    $db_status = "✅ Conectado";
    
} catch (Exception $e) {
    $db_status = "❌ Erro: " . $e->getMessage();
    $produtos = [];
    $categorias = [];
}

// Verificar se veio redirecionamento de sucesso
if (isset($_GET['sucesso']) && $_GET['sucesso'] == 1) {
    $mensagem = "✅ Produto cadastrado com sucesso!";
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Produtos - Admin</title>
    <link rel="stylesheet" href="css/admin.css">
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        .modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 0;
            border-radius: 8px;
            width: 90%;
            max-width: 600px;
            max-height: 80vh;
            overflow-y: auto;
        }
        .modal-header {
            padding: 20px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .close-modal {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
        }
        .product-form {
            padding: 20px;
        }
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .full-width {
            grid-column: 1 / -1;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input, select, textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .form-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 20px;
        }
        .btn-primary {
            background: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-cancel {
            background: #6c757d;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .alert {
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
        }
        .alert-error {
            background: #f8d7da;
            color: #721c24;
        }
        .product-thumb {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 4px;
        }
        .no-image {
            width: 50px;
            height: 50px;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
        }
        .action-buttons {
            display: flex;
            gap: 5px;
        }
        .btn-small {
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-edit {
            background: #28a745;
            color: white;
        }
        .btn-delete {
            background: #dc3545;
            color: white;
        }
    </style>
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
                    <li><a href="dashboard.php">📊 Dashboard</a></li>
                    <li class="active"><a href="produtos.php">🎯 Produtos</a></li>
                    <li><a href="categorias.php">📁 Categorias</a></li>
                    <li><a href="../lojinha/index.php" target="_blank">🏠 Ver Loja</a></li>
                    <li><a href="#" id="logout">🚪 Sair</a></li>
                </ul>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <header class="dashboard-header">
                <div class="header-left">
                    <h1>Gerenciar Produtos</h1>
                    <p>Cadastre e gerencie os produtos da loja</p>
                </div>
                <div class="header-right">
                    <button class="btn-primary" onclick="abrirModal()">➕ Novo Produto</button>
                </div>
            </header>

            <!-- Mensagem de sucesso -->
            <?php if (isset($mensagem)): ?>
            <div class="alert alert-success">
                <?php echo $mensagem; ?>
            </div>
            <?php endif; ?>

            <!-- Mensagem de erro -->
            <?php if (isset($erro)): ?>
            <div class="alert alert-error">
                <?php echo $erro; ?>
            </div>
            <?php endif; ?>

            <!-- Lista de Produtos -->
            <div class="content-card">
                <div class="card-header">
                    <h3>Produtos Cadastrados</h3>
                    <div class="search-box">
                        <input type="text" id="searchProdutos" placeholder="Buscar produtos...">
                    </div>
                </div>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Imagem</th>
                                <th>Nome</th>
                                <th>Preço</th>
                                <th>Jogadores</th>
                                <th>Idade</th>
                                <th>Tempo</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($produtos)): ?>
                                <?php foreach ($produtos as $produto): ?>
                                <tr>
                                    <td>#<?php echo $produto['ID']; ?></td>
                                    <td>
                                        <?php if (!empty($produto['IMG'])): ?>
                                            <img src="../lojinha/<?php echo $produto['IMG']; ?>" alt="<?php echo $produto['NOME']; ?>" class="product-thumb" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div class="no-image" style="display: none;">📷</div>
                                        <?php else: ?>
                                            <div class="no-image">📷</div>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo $produto['NOME']; ?></td>
                                    <td>R$ <?php echo number_format($produto['PRECO'], 2, ',', '.'); ?></td>
                                    <td><?php echo $produto['PESOA']; ?></td>
                                    <td><?php echo $produto['IDADE']; ?></td>
                                    <td><?php echo $produto['TEMPO']; ?></td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn-small btn-edit" onclick="editarProduto(<?php echo $produto['ID']; ?>)">✏️</button>
                                            <button class="btn-small btn-delete" onclick="excluirProduto(<?php echo $produto['ID']; ?>)">🗑️</button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" style="text-align: center;">Nenhum produto cadastrado</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Cadastro -->
    <div id="modalProduto" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>➕ Cadastrar Novo Produto</h3>
                <button class="close-modal" onclick="fecharModal()">×</button>
            </div>
            <form method="POST" enctype="multipart/form-data" class="product-form">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="nome">Nome do Produto *</label>
                        <input type="text" id="nome" name="nome" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="preco">Preço (R$) *</label>
                        <input type="number" id="preco" name="preco" step="0.01" min="0" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="categoria">Categoria *</label>
                        <select id="categoria" name="categoria" required>
                            <option value="">Selecione uma categoria</option>
                            <?php foreach ($categorias as $categoria): ?>
                            <option value="<?php echo $categoria['ID']; ?>">
                                <?php echo $categoria['NOME_CATEGORIA']; ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="idade">Idade Recomendada</label>
                        <input type="text" id="idade" name="idade" placeholder="Ex: 8+">
                    </div>
                    
                    <div class="form-group">
                        <label for="tempo">Tempo de Jogo</label>
                        <input type="text" id="tempo" name="tempo" placeholder="Ex: 30-60 min">
                    </div>
                    
                    <div class="form-group">
                        <label for="pesoa">Quantidade de Jogadores</label>
                        <input type="text" id="pesoa" name="pesoa" placeholder="Ex: 2-4 jogadores">
                    </div>
                    
                    <div class="form-group full-width">
                        <label for="descricao">Descrição</label>
                        <textarea id="descricao" name="descricao" rows="4" placeholder="Descreva o produto..."></textarea>
                    </div>
                    
                    <div class="form-group full-width">
                        <label for="imagem">Imagem do Produto</label>
                        <input type="file" id="imagem" name="imagem" accept="image/*">
                        <small style="color: #666;">Formatos aceitos: JPG, PNG, GIF</small>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn-cancel" onclick="fecharModal()">Cancelar</button>
                    <button type="submit" class="btn-primary">💾 Salvar Produto</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function abrirModal() {
            document.getElementById('modalProduto').style.display = 'block';
        }
        
        function fecharModal() {
            document.getElementById('modalProduto').style.display = 'none';
        }
        
        function editarProduto(id) {
            alert('Editar produto ID: ' + id);
            // Implementar edição
        }
        
        function excluirProduto(id) {
            if (confirm('Tem certeza que deseja excluir este produto?')) {
                alert('Excluir produto ID: ' + id);
                // Implementar exclusão
            }
        }
        
        // Fechar modal clicando fora
        window.onclick = function(event) {
            const modal = document.getElementById('modalProduto');
            if (event.target === modal) {
                fecharModal();
            }
        }

        // Busca em tempo real
        document.getElementById('searchProdutos')?.addEventListener('input', function(e) {
            const termo = e.target.value.toLowerCase();
            const linhas = document.querySelectorAll('.data-table tbody tr');
            
            linhas.forEach(linha => {
                const texto = linha.textContent.toLowerCase();
                if (texto.includes(termo)) {
                    linha.style.display = '';
                } else {
                    linha.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>