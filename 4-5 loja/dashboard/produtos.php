<?php
session_start();
require_once '../model/dashmodel.php';

$mensagem = '';
$erro = '';

try {
    $dashboardModel = new DashboardModel();

    // Processar formulário de cadastro
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nome = $_POST['nome'] ?? '';
        $preco = $_POST['preco'] ?? '';
        $descricao = $_POST['descricao'] ?? '';
        $pesoa = $_POST['pesoa'] ?? '';
        $idade = $_POST['idade'] ?? '';
        $tempo = $_POST['tempo'] ?? '';
        $categoria = $_POST['categoria'] ?? '';

      
// Upload da imagem diretamente no banco
$imagem = null;
if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === 0) {
    $tipos_permitidos = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (in_array($_FILES['imagem']['type'], $tipos_permitidos)) {
        $imagem = file_get_contents($_FILES['imagem']['tmp_name']); // lê o conteúdo da imagem
    } else {
        $erro = "Tipo de imagem não permitido!";
    }
}


        if (!$erro) {
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

            $sucesso = $dashboardModel->cadastrarProduto($dadosProduto);

            if ($sucesso) {
                header("Location: produtos.php?sucesso=1");
                exit();
            } else {
                $erro = "Erro ao cadastrar produto!";
            }
        }
    }

    // Mensagem de exclusão
    if (isset($_GET['sucesso']) && $_GET['sucesso'] == 2) {
        $mensagem = "Produto excluído com sucesso!";
    }

    // Mensagem de cadastro
    if (isset($_GET['sucesso']) && $_GET['sucesso'] == 1) {
        $mensagem = "Produto cadastrado com sucesso!";
    }

    // Mensagem de edição
    if (isset($_GET['sucesso']) && $_GET['sucesso'] == 3) {
        $mensagem = "Produto atualizado com sucesso!";
    }

    // Buscar produtos e categorias
    $produtos = $dashboardModel->getProdutosRecentes();
    $categorias = $dashboardModel->getCategorias();
    $db_status = "Conectado";

} catch (Exception $e) {
    $db_status = "Erro: " . $e->getMessage();
    $produtos = [];
    $categorias = [];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Produtos - Admin</title>
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo">
                <h2>Admin Panel</h2>
                <small>Banco: <?php echo $db_status; ?></small>
            </div>
            <nav class="sidebar-nav">
                <ul>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li class="active"><a href="produtos.php">Produtos</a></li>
                    <li><a href="categorias.php">Categorias</a></li>
                    <li><a href="../lojinha/index.php" target="_blank">Ver Loja</a></li>
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
                    <button class="btn-primary" onclick="abrirModal()">Novo Produto</button>
                </div>
            </header>

            <?php if ($mensagem): ?>
                <div class="alert alert-success"><?php echo $mensagem; ?></div>
            <?php endif; ?>

            <?php if ($erro): ?>
                <div class="alert alert-error"><?php echo $erro; ?></div>
            <?php endif; ?>

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
                                        <?php
                                            if (!empty($produto['IMG'])) {
                                                echo "<img src='imagem.php?id={$produto['ID']}' alt='{$produto['NOME']}' class='product-thumb'>";
                                            } else {
                                                echo "<div class='no-image'>Sem imagem</div>";
                                            }
                                        ?>
                                    </td>
                                    <td><?php echo $produto['NOME']; ?></td>
                                    <td>R$ <?php echo $produto['PRECO']; ?></td>
                                    <td><?php echo $produto['PESOA']; ?></td>
                                    <td><?php echo $produto['IDADE']; ?></td>
                                    <td><?php echo $produto['TEMPO']; ?></td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn-small btn-edit" onclick="editarProduto(<?php echo $produto['ID']; ?>)">Editar</button>
                                            <button class="btn-small btn-delete" onclick="excluirProduto(<?php echo $produto['ID']; ?>)">Excluir</button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="8" style="text-align:center;">Nenhum produto cadastrado</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="modalProduto" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Cadastrar Novo Produto</h3>
                <button class="close-modal" onclick="fecharModal()">×</button>
            </div>
            <form method="POST" enctype="multipart/form-data" class="product-form">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="nome">Nome *</label>
                        <input type="text" id="nome" name="nome" required>
                    </div>
                    <div class="form-group">
                        <label for="preco">Preço *</label>
                        <input type="number" id="preco" name="preco" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="categoria">Categoria *</label>
                        <select id="categoria" name="categoria" required>
                            <option value="">Selecione</option>
                            <?php foreach ($categorias as $cat): ?>
                                <option value="<?php echo $cat['ID']; ?>"><?php echo $cat['NOME_CATEGORIA']; ?></option>
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
                        <label for="pesoa">Jogadores</label>
                        <input type="text" id="pesoa" name="pesoa" placeholder="Ex: 2-4 jogadores">
                    </div>
                    <div class="form-group full-width">
                        <label for="descricao">Descrição</label>
                        <textarea id="descricao" name="descricao" rows="4" placeholder="Descreva o produto..."></textarea>
                    </div>
                    <div class="form-group full-width">
                        <label for="imagem">Imagem</label>
                        <input type="file" id="imagem" name="imagem" accept="image/*">
                    </div>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn-cancel" onclick="fecharModal()">Cancelar</button>
                    <button type="submit" class="btn-primary">Salvar</button>
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
    window.location.href = 'editar.php?id=' + id;
}

function excluirProduto(id) {
    if (confirm('Tem certeza que deseja excluir este produto?')) {
        window.location.href = 'excluir.php?id=' + id;
    }
}

window.onclick = function(e) {
    const modal = document.getElementById('modalProduto');
    if (e.target === modal) fecharModal();
}

document.getElementById('searchProdutos')?.addEventListener('input', e => {
    const termo = e.target.value.toLowerCase();
    document.querySelectorAll('.data-table tbody tr').forEach(linha => {
        linha.style.display = linha.textContent.toLowerCase().includes(termo) ? '' : 'none';
    });
});

// Logout
document.getElementById('logout')?.addEventListener('click', function(e) {
    e.preventDefault();
    if (confirm('Deseja sair do sistema?')) {
        window.location.href = 'logout.php';
    }
});
</script>
</body>
</html>