<?php
$host = 'localhost';
$user = 'gustavo';
$password = 'Gustavo7327';
$dbname = 'vacineja';

// Criando conexão com o banco de dados
$conn = new mysqli($servername, $user, $password, $dbname);

// Verificando a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Iniciando a sessão para armazenar os dados de login
session_start();

// Função para verificar o login do administrador
function verificarLogin($email, $senha) {
    global $conn; // Referência para a conexão do banco de dados

    // Prevenção contra SQL Injection usando prepared statements
    $stmt = $conn->prepare("SELECT id, email, senha, admin_prefeitura, id_posto FROM Administrador WHERE email = ?");
    $stmt->bind_param("s", $email); // 's' indica que o parâmetro é uma string

    // Executando a consulta
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificando se o e-mail existe
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verificando se a senha corresponde (comparação segura usando password_verify)
        if (password_verify($senha, $row['senha'])) {
            // Login bem-sucedido
            // Salvar os dados do usuário na sessão, sem erro, independente de admin_prefeitura
            $_SESSION['id'] = $row['id'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['admin_prefeitura'] = $row['admin_prefeitura'];
            $_SESSION['id_posto'] = $row['id_posto']; // Armazenar admin_prefeitura para controle de permissões

            return ['success' => true];
        } else {
            // Senha incorreta
            return ['success' => false, 'message' => 'Senha incorreta.'];
        }
    } else {
        // E-mail não encontrado
        return ['success' => false, 'message' => 'E-mail não encontrado.'];
    }
}

// Exemplo de uso:
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recebendo os dados do formulário
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Verificando o login
    $loginResult = verificarLogin($email, $senha);

    if ($loginResult['success']) {

        // Redirecionar para a página de dashboard ou painel
        header("Location: ../Catálogo de vacinas - ADM/index.php");

    } else {
        // Exibindo a mensagem de erro
        echo "Erro: " . $loginResult['message'];
    }
}

// Fechando a conexão
$conn->close();
?>
