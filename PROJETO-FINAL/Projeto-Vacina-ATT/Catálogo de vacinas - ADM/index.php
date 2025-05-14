<?php
session_start();
if(!isset($_SESSION['email'])){
    header('Location: ../Login/index.html');
}
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

// Função para buscar vacinas e postos
function buscarVacinas($conn, $pesquisa = '') {
    $sql = "
        SELECT Vacina.id, Vacina.nome, Vacina.idade_recomendada, Vacina.url_imagem, 
               Posto.nome AS posto_nome, Posto.horario_abertura, Posto.horario_fechamento, 
               Endereco.bairro, Endereco.rua, Endereco.numero
        FROM Vacina
        JOIN Posto ON Vacina.id_posto = Posto.id
        JOIN Endereco ON Posto.id_endereco = Endereco.id
        WHERE Vacina.nome LIKE ? OR Posto.nome LIKE ?
    ";

    $stmt = $conn->prepare($sql);
    $pesquisaParam = '%' . $pesquisa . '%';
    $stmt->bind_param('ss', $pesquisaParam, $pesquisaParam);
    $stmt->execute();
    return $stmt->get_result();
}

// Função para excluir a vacina
function excluirVacina($conn, $id) {
    // Verifique se a vacina existe antes de excluir
    $sql = "DELETE FROM Vacina WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    return $stmt->execute();
}

// Verificar se a requisição AJAX foi feita para excluir a vacina
if (isset($_POST['excluir_id'])) {
    $id = $_POST['excluir_id'];
    if (excluirVacina($conn, $id)) {
        echo 'Vacina removida com sucesso!';
    } else {
        echo 'Erro ao remover a vacina.';
    }
    exit; // Para evitar que o código abaixo seja executado após a requisição AJAX
}

// Obter pesquisa do usuário
$pesquisa = isset($_GET['pesquisa']) ? trim($_GET['pesquisa']) : '';
$vacinas = buscarVacinas($conn, $pesquisa);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VacinaJá</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="shortcut icon" type="imagex/png" href="plus-svgrepo-com1.svg">
</head>
<body>
    <div class="top-bar">
        <form class="search-box" method="GET" action="">
            <input 
                type="text" 
                name="pesquisa" 
                placeholder="O que você procura?" 
                class="search-input" 
                value="<?php echo htmlspecialchars($pesquisa); ?>">
        </form>

        <div class="water-drop-svgrepo-com-3" onclick="logout()">
            <img src="./logout-box-line.svg" alt="Logout" width="40px" height="40px" style=" margin-top:40px;">
        </div>  
               
    </div>

    <a href="../Cadastro de Vacinas - ADM/index.php" class="add-vaccine-btn">
        Adicionar nova vacina
    </a>  
    
    
    <div class="container" id="vacinasContainer">
        <?php while ($vacina = $vacinas->fetch_assoc()): ?>
            <div class="box" id="vacina<?php echo $vacina['id']; ?>">
                <div class="top-space" id="topSpace<?php echo $vacina['id']; ?>">
                    <img src="<?php echo $vacina['url_imagem']; ?>" alt="Imagem da vacina" class="vaccine-image" id="vaccineImage<?php echo $vacina['id']; ?>">
                </div>
                <div class="vaccine-info">
                    <div class="vaccine-name" id="vaccineName<?php echo $vacina['id']; ?>">
                        <?php echo htmlspecialchars($vacina['nome']); ?>
                    </div>
                    <div class="vaccine-age" id="vaccineAge<?php echo $vacina['id']; ?>">
                        Idade recomendada: <?php echo htmlspecialchars($vacina['idade_recomendada']); ?>
                    </div>
                    <div class="vaccine-posto" id="vaccinePosto<?php echo $vacina['id']; ?>">
                       Posto: <?php echo htmlspecialchars($vacina['posto_nome']); ?>
                    </div>
                    <div class="vaccine-address" id="vaccineAddress<?php echo $vacina['id']; ?>">
                        Endereço do posto: <?php echo  htmlspecialchars($vacina['rua']) . ", " . htmlspecialchars($vacina['numero']) . " - " . htmlspecialchars($vacina['bairro']);    ?>
                    </div>
                    <div class="vaccine-hours" id="vaccineHours<?php echo $vacina['id']; ?>">
                        Horário de funcionamento: <?php echo htmlspecialchars($vacina['horario_abertura']) . " às " . htmlspecialchars($vacina['horario_fechamento']); ?>
                    </div>

                    <div class="button-container">
                        <a href="../Alteração de Vacinas - ADM/index.php?id=<?php echo $vacina['id']; ?>" class="edit">Editar</a>
                        <a href="javascript:void(0);" class="remove" onclick="excluirVacina(<?php echo $vacina['id']; ?>)">Remover</a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
    
    <script src="script.js"></script>

    <script>

        function logout() {
            // Envia a requisição para destruir a sessão no back-end (PHP)
            window.location.href = "logout.php"; // Redireciona para o arquivo PHP que vai destruir a sessão
        }

        // Função para excluir a vacina via AJAX
        function excluirVacina(id) {
            // Criação do objeto XMLHttpRequest
            var xhr = new XMLHttpRequest();
            
            // Configuração da requisição POST para o script de exclusão
            xhr.open('POST', '', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            // Envio da requisição com o ID da vacina a ser removida
            xhr.send('excluir_id=' + id);

            // Processar a resposta do servidor
            xhr.onload = function() {
                if (xhr.status === 200) {
                    // Se a exclusão foi bem-sucedida, removemos a vacina da tela
                    var resposta = xhr.responseText;
                    if(resposta === 'Vacina removida com sucesso!') {
                        document.getElementById('vacina' + id).remove(); // Remove o elemento do DOM
                    } else {
                        alert('Erro ao remover a vacina.');
                    }
                }
            };
        }
    </script>
</body>
</html>
