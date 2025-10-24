<?php
session_start();
require_once '../model/dashmodel.php';

$mensagem = '';
$erro = '';
$produto = null;

try {
    $dashboardModel = new DashboardModel();

    // Buscar dados do produto para edição
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $produto = $dashboardModel->getProdutoPorId($id);
        
        if (!$produto) {
            header("Location: produtos.php?erro=3");
            exit();
        }
    }

    // Processar formulário de edição
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id'] ?? '';
        $nome = $_POST['nome'] ?? '';
        $preco = $_POST['preco'] ?? '';
        $descricao = $_POST['descricao'] ?? '';
        $pesoa = $_POST['pesoa'] ?? '';
        $idade = $_POST['idade'] ?? '';
        $tempo = $_POST['tempo'] ?? '';
        $categoria = $_POST['categoria'] ?? '';

        // Upload da nova imagem (se fornecida)
        $imagem = $_POST['imagem_atual'] ?? '';
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === 0) {
            $pasta_upload = '../lojinha/img/';

            if (!is_dir($pasta_upload)) {
                mkdir($pasta_upload, 0777, true);
            }

            $nome_arquivo = uniqid() . '_' . basename($_FILES['imagem']['name']);
            $caminho_completo = $pasta_upload . $nome_arquivo;

            $tipos_permitidos = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (in_array($_FILES['imagem']['type'], $tipos_permitidos)) {
                if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho_completo)) {
                    // Remove a imagem antiga se existir
                    if (!empty($_POST['imagem_atual'])) {
                        $imagem_antiga = '../lojinha/' . ltrim($_POST['imagem_atual'], '/');
                        if (file_exists($imagem_antiga)) {
                            unlink($imagem_antiga);
                        }
                    }
                    $imagem = 'img/' . $nome_arquivo;
                } else {
                    $erro = "Erro ao fazer upload da imagem!";
                }
            } else {
                $erro = "Tipo de imagem não permitido!";
            }
        }

        if (!$erro) {
            $dadosProduto = [
                'id' => $id,
                'nome' => $nome,
                'preco' => $preco,
                'descricao' => $descricao,
                'pesoa' => $pesoa,
                'idade' => $idade,
                'tempo' => $tempo,
                'imagem' => $imagem,
                'categoria' => $categoria
            ];

            $sucesso = $dashboardModel->atualizarProduto($dadosProduto);

            if ($sucesso) {
                header("Location: produtos.php?sucesso=3");
                exit();
            } else {
                $erro = "Erro ao atualizar produto!";
            }
        }
    }

    $categorias = $dashboardModel->getCategorias();
    $db_status = "✅ Conectado";

} catch (Exception $e) {
    $db_status = "❌ Erro: " . $e->getMessage();
    $categorias = [];
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produto - Admin</title>
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
    <div class="dashboard-container">
        <div class="sidebar">
            <div class="logo">
                <h2>🎲 Admin Panel</h2>
                <small>Banco: <?php echo $db_status; ?></small>
            </div>
            <nav class="sidebar-nav">
                <ul>
                    <li><a href="dashboard.php">📊 Dashboard</a></li>
                    <li><a href="produtos.php">🎯 Produtos</a></li>
                    <li><a href="categorias.php">📁 Categorias</a></li>
                    <li><a href="../lojinha/index.php" target="_blank">🏠 Ver Loja</a></li>
                </ul>
            </nav>
        </div>

        <div class="main-content">
            <header class="dashboard-header">
                <div class="header-left">
                    <h1>✏️ Editar Produto</h1>
                    <p>Modifique as informações do produto</p>
                </div>
                <div class="header-right">
                    <button class="btn-secondary" onclick="window.location.href='produtos.php'">← Voltar</button>
                </div>
            </header>

            <?php if ($mensagem): ?>
                <div class="alert alert-success"><?php echo $mensagem; ?></div>
            <?php endif; ?>

            <?php if ($erro): ?>
                <div class="alert alert-error"><?php echo $erro; ?></div>
            <?php endif; ?>

            <?php if ($produto): ?>
            <div class="content-card">
                <form method="POST" enctype="multipart/form-data" class="product-form">
                    <input type="hidden" name="id" value="<?php echo $produto['ID']; ?>">
                    <input type="hidden" name="imagem_atual" value="<?php echo $produto['IMG'] ?? ''; ?>">
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="nome">Nome *</label>
                            <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($produto['NOME']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="preco">Preço *</label>
                            <input type="number" id="preco" name="preco" step="0.01" value="<?php echo $produto['PRECO']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="categoria">Categoria *</label>
                            <select id="categoria" name="categoria" required>
                                <option value="">Selecione</option>
                                <?php foreach ($categorias as $cat): ?>
                                    <option value="<?php echo $cat['ID']; ?>" 
                                        <?php echo (isset($produto['FK_CATEGORIA']) && $cat['ID'] == $produto['FK_CATEGORIA']) ? 'selected' : ''; ?>>
                                        <?php echo $cat['NOME_CATEGORIA']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="idade">Idade Recomendada</label>
                            <input type="text" id="idade" name="idade" value="<?php echo htmlspecialchars($produto['IDADE'] ?? ''); ?>" placeholder="Ex: 8+">
                        </div>
                        <div class="form-group">
                            <label for="tempo">Tempo de Jogo</label>
                            <input type="text" id="tempo" name="tempo" value="<?php echo htmlspecialchars($produto['TEMPO'] ?? ''); ?>" placeholder="Ex: 30-60 min">
                        </div>
                        <div class="form-group">
                            <label for="pesoa">Jogadores</label>
                            <input type="text" id="pesoa" name="pesoa" value="<?php echo htmlspecialchars($produto['PESOA'] ?? ''); ?>" placeholder="Ex: 2-4 jogadores">
                        </div>
                        <div class="form-group full-width">
                            <label for="descricao">Descrição</label>
                            <textarea id="descricao" name="descricao" rows="4" placeholder="Descreva o produto..."><?php echo htmlspecialchars($produto['DESCRICAO'] ?? ''); ?></textarea>
                        </div>
                        <div class="form-group full-width">
                            <label for="imagem">Imagem</label>
                            <?php if (!empty($produto['IMG'])): ?>
                                <div style="margin-bottom: 15px;">
                                    <img src="../lojinha/<?php echo $produto['IMG']; ?>" alt="Imagem atual" style="max-width: 200px; display: block;">
                                    <small>Imagem atual</small>
                                </div>
                            <?php endif; ?>
                            <input type="file" id="imagem" name="imagem" accept="image/*">
                            <small>Deixe em branco para manter a imagem atual</small>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="button" class="btn-cancel" onclick="window.location.href='produtos.php'">Cancelar</button>
                        <button type="submit" class="btn-primary">💾 Atualizar Produto</button>
                    </div>
                </form>
            </div>
            <?php else: ?>
                <div class="alert alert-error">Produto não encontrado!</div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>