<?php
// Arquivo para exibir imagens na loja
require_once '../model/conexao.php';

header('Content-Type: image/jpeg');

try {
    $database = new Database();
    $conn = $database->getConnection();
    
    $id = $_GET['id'] ?? null;
    
    if (!$id) {
        // Imagem padrão se não houver ID
        $defaultImage = imagecreate(200, 200);
        $bgColor = imagecolorallocate($defaultImage, 240, 240, 240);
        $textColor = imagecolorallocate($defaultImage, 100, 100, 100);
        imagestring($defaultImage, 5, 50, 90, 'Sem Imagem', $textColor);
        imagejpeg($defaultImage);
        imagedestroy($defaultImage);
        exit;
    }
    
    $query = "SELECT IMG FROM produto WHERE ID = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result && $result['IMG']) {
        echo $result['IMG'];
    } else {
        // Imagem padrão se não houver imagem no banco
        $defaultImage = imagecreate(200, 200);
        $bgColor = imagecolorallocate($defaultImage, 240, 240, 240);
        $textColor = imagecolorallocate($defaultImage, 100, 100, 100);
        imagestring($defaultImage, 5, 50, 90, 'Sem Imagem', $textColor);
        imagejpeg($defaultImage);
        imagedestroy($defaultImage);
    }
    
} catch (Exception $e) {
    // Imagem de erro
    $errorImage = imagecreate(200, 200);
    $bgColor = imagecolorallocate($errorImage, 255, 200, 200);
    $textColor = imagecolorallocate($errorImage, 200, 50, 50);
    imagestring($errorImage, 3, 30, 90, 'Erro ao carregar', $textColor);
    imagejpeg($errorImage);
    imagedestroy($errorImage);
}
?>
