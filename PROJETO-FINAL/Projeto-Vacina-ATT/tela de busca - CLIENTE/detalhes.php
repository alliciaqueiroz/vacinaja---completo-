<?php
session_start();
// Configuração do banco de dados
$host = 'localhost';
$user = 'gustavo';
$password = 'Gustavo7327';
$dbname = 'vacineja';

// Conexão com o banco de dados
$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Query para buscar apenas os detalhes da vacina
    $sql = "
        SELECT Vacina.id, Vacina.nome, Vacina.descricao, Vacina.idade_recomendada, Vacina.data_aplicacao, 
               Vacina.url_imagem
        FROM Vacina
        WHERE Vacina.id = ?
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $vacina = $result->fetch_assoc();
        
        // Retorna os dados da vacina em formato JSON
        echo json_encode([
            'url_imagem' => $vacina['url_imagem'],
            'nome' => $vacina['nome'],
            'descricao' => $vacina['descricao'],
            'idade_recomendada' => $vacina['idade_recomendada'],
            'data_aplicacao' => $vacina['data_aplicacao']
        ]);
    } else {
        echo json_encode(['error' => 'Vacina não encontrada']);
    }

    $stmt->close();
}

$conn->close();
?>
