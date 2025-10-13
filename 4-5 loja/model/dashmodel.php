<?php
require_once 'conexao.php';

class DashboardModel {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
        
        if ($this->conn === null) {
            throw new Exception("Falha na conexão com o banco de dados");
        }
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
              VALUES (:nome, :preco, :descricao, :pesoa, :idade, :tempo, :img, :categoria)";
    
    $stmt = $this->conn->prepare($query);
    return $stmt->execute([
        ':nome' => $dados['nome'],
        ':preco' => $dados['preco'],
        ':descricao' => $dados['descricao'],
        ':pesoa' => $dados['pesoa'],
        ':idade' => $dados['idade'],
        ':tempo' => $dados['tempo'],
        ':img' => $dados['imagem'], // Agora usando :img
        ':categoria' => $dados['categoria']
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

public function getProdutosParaLoja() {
    $query = "SELECT 
                p.ID as id,
                p.NOME as nome,
                p.PRECO as preco,
                p.DESCRICAO as descricao,
                p.IMG as imagem,  // Certifique-se que está como 'imagem' aqui
                p.PESOA as pesoa,
                p.IDADE as idade,
                p.TEMPO as tempo,
                c.NOME_CATEGORIA as categoria
              FROM produto p 
              LEFT JOIN categoria c ON p.FK_CATEGORIA = c.ID 
              ORDER BY p.ID DESC";
    
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}
?>