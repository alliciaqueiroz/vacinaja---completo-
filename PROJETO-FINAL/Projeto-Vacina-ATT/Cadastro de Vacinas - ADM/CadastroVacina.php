<?php
session_start();
$host = 'localhost';
$user = 'gustavo';
$password = 'Gustavo7327';
$dbname = 'vacineja'; 

// Cria a conexão com o banco de dados
$conn = new mysqli($host, $user, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $data = $_POST['data'];  // Formato YYYY-MM-DD
    $hora = $_POST['hora'];  // Formato HH:MM

    // Combina a data e a hora para criar um formato DATETIME
    $dataHora = $data . ' ' . $hora;

    $id_posto = $_SESSION['id_posto'];
    //echo $id_posto . "postoooooo";
    $idade_recomendada = $_POST['idade'];
    
    // Inicializa a variável para armazenar a URL da imagem
    $url_imagem = '';

    // Verifica se o arquivo foi carregado sem erros
    if (isset($_FILES['arquivo']) && $_FILES['arquivo']['error'] == 0) {
        // Defina o diretório de destino para o upload
        $diretorioDestino = '../uploads/';

        // Crie o diretório de destino se ele não existir
        if (!is_dir($diretorioDestino)) {
            mkdir($diretorioDestino, 0777, true);
        }

        // Obtenha o nome do arquivo e o caminho de destino
        $nomeArquivo = $_FILES['arquivo']['name'];
        $caminhoDestino = $diretorioDestino . $nomeArquivo;

        // Verifique se o arquivo já existe
        if (file_exists($caminhoDestino)) {
            echo "Erro: O arquivo já existe.";
        } else {
            // Move o arquivo do diretório temporário para o destino final
            if (move_uploaded_file($_FILES['arquivo']['tmp_name'], $caminhoDestino)) {
                //echo "Arquivo enviado com sucesso!";
                // Define a URL da imagem (caminho relativo ao servidor)
                $url_imagem = $caminhoDestino;
            } else {
                echo "Erro ao mover o arquivo.";
            }
        }
    } else {
        echo "Erro ao enviar o arquivo. Código de erro: " . $_FILES['arquivo']['error'];
    }

    // Verifica se todos os campos foram preenchidos
    if (!empty($nome) && !empty($descricao) && !empty($dataHora) && !empty($id_posto) && !empty($idade_recomendada) && !empty($url_imagem)) {

        // Prepara a consulta SQL para inserção no banco de dados
        $sql = "INSERT INTO Vacina (nome, descricao, data_aplicacao, id_posto, idade_recomendada, url_imagem) 
                VALUES (?, ?, ?, ?, ?, ?)";

        // Prepara a declaração
        if ($stmt = $conn->prepare($sql)) {
            // Vincula os parâmetros
            $stmt->bind_param("sssiss", $nome, $descricao, $dataHora, $id_posto, $idade_recomendada, $url_imagem);

            // Executa a consulta
            $stmt->execute();
                
                
        } 

            // Fecha a declaração
            $stmt->close();

            header("Location: ../Catálogo de vacinas - ADM/index.php");
        } else {
            echo "Erro na preparação da consulta: " . $conn->error;
        }
    } else {
        echo "Por favor, preencha todos os campos corretamente.";
    }


// Fecha a conexão
$conn->close();
?>
