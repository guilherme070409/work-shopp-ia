<?php
require_once 'conexao.php';

class DashboardModel {
    private $conn;

    public function __construct() {
        // Sua conexão - adapte conforme seu arquivo conexao.php
        // Se sua classe de conexão se chama diferente, ajuste aqui
        $database = new Database(); // ou o nome da sua classe de conexão
        $this->conn = $database->getConnection();
    }

    public function getTotalProdutos() {
        $query = "SELECT COUNT(*) as total FROM produto";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function getTotalCategorias() {
        $query = "SELECT COUNT(*) as total FROM categoria";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function getProdutosRecentes() {
        $query = "SELECT p.*, c.NOME_CATEGORIA 
                  FROM produto p 
                  LEFT JOIN categoria c ON p.FK_CATEGORIA = c.ID 
                  ORDER BY p.ID DESC 
                  LIMIT 10";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProdutosPorCategoria() {
        $query = "SELECT c.NOME_CATEGORIA, COUNT(p.ID) as total
                  FROM categoria c
                  LEFT JOIN produto p ON c.ID = p.FK_CATEGORIA
                  GROUP BY c.ID, c.NOME_CATEGORIA";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

public function getCategorias() {
    $query = "SELECT * FROM categoria ORDER BY NOME_CATEGORIA";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
public function cadastrarProduto($dados) {
    $query = "INSERT INTO produto (NOME, PRECO, DESCRICAO, PESOA, IDADE, TEMPO, IMG, FK_CATEGORIA) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $this->conn->prepare($query);
    return $stmt->execute([
        $dados['nome'],
        $dados['preco'], 
        $dados['descricao'],
        $dados['pesoa'],  // Agora é PESOA (quantidade de jogadores)
        $dados['idade'],
        $dados['tempo'],
        $dados['imagem'],
        $dados['categoria']
    ]);
}
public function getTodosProdutos() {
    $query = "SELECT p.*, c.NOME_CATEGORIA 
              FROM produto p 
              LEFT JOIN categoria c ON p.FK_CATEGORIA = c.ID 
              ORDER BY p.ID DESC";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}
?>