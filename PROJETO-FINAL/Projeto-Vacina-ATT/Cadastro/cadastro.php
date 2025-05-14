<?php
// Iniciando a sessão (caso precise redirecionar após o cadastro)
session_start();

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

// Função para validar e inserir o novo administrador
function cadastrarAdministrador($nome, $email, $cpf, $senha, $telefone, $id_posto) {
    global $conn; // Referência para a conexão do banco de dados

    // Verificar se o e-mail já existe no banco
    $stmt = $conn->prepare("SELECT id FROM Administrador WHERE email = ?");
    $stmt->bind_param("s", $email); // 's' indica que o parâmetro é uma string
    $stmt->execute();
    $result = $stmt->get_result();

    // Se já existir um administrador com o mesmo e-mail
    if ($result->num_rows > 0) {
        return ['success' => false, 'message' => 'Este e-mail já está cadastrado.'];
    }

    // Criptografar a senha usando bcrypt
    $senhaHash = password_hash($senha, PASSWORD_BCRYPT);

    // Inserir o novo administrador no banco de dados
    $stmt = $conn->prepare("INSERT INTO Administrador (nome, email, cpf, senha, telefone, admin_prefeitura, id_posto) 
                            VALUES (?, ?, ?, ?, ?, FALSE, ?)");
    $stmt->bind_param("ssssss", $nome, $email, $cpf, $senhaHash, $telefone, $id_posto);

    if ($stmt->execute()) {
        return ['success' => true, 'message' => 'Administrador cadastrado com sucesso!'];
    } else {
        return ['success' => false, 'message' => 'Erro ao cadastrar administrador.'];
    }
}

// Processar o formulário quando enviado via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recebendo os dados do formulário
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $cpf = $_POST['cpf'];
    $senha = $_POST['senha'];
    $telefone = $_POST['telefone'];
    $id_posto = $_POST['id_posto'];

    // Validando se todos os campos foram preenchidos
    if (empty($nome) || empty($email) || empty($cpf) || empty($senha) || empty($telefone)) {
        echo "Todos os campos são obrigatórios.";
        exit;
    }

    // Chamar a função para cadastrar o administrador
    $result = cadastrarAdministrador($nome, $email, $cpf, $senha, $telefone, $id_posto);

    // Exibir a mensagem de sucesso ou erro
    if ($result['success']) {
        
        
        header("Location: index.php");
        exit;
    } else {
        echo $result['message']; // Exibir mensagem de erro
    }
}

// Fechar a conexão
$conn->close();
?>
