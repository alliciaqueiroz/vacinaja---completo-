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

// Obter pesquisa do usuário
$pesquisa = isset($_GET['pesquisa']) ? trim($_GET['pesquisa']) : '';
$vacinas = buscarVacinas($conn, $pesquisa);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vacinajá</title>
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" type="imagex/png" href="plus-svgrepo-com1.svg">
    <!--<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script> -->
</head>
<body>
  
    <!-- Cabeçalho com barra de pesquisa -->
    <div class="header">
        <div class="logo">
            <img src="Group 4.svg" alt="Logo">
        </div>
        <!-- Formulário de pesquisa -->
        <form method="get" class="search-form">
            <input 
                type="text" 
                name="pesquisa" 
                placeholder="O que você procura?" 
                value="<?= htmlspecialchars($pesquisa) ?>">
            <!-- Ícone de lupa como imagem e funcionalidade de enviar o formulário -->
            <button type="submit" class="search-icon-btn">
                <img src="search1.svg" alt="Pesquisar" class="search-icon">
            </button>
        </form>
    </div>

    <!-- Conteúdo Principal -->
    <div class="content">
        <div class="vacina-grid">
            <?php if ($vacinas->num_rows > 0): ?>
                <?php while ($vacina = $vacinas->fetch_assoc()): ?>
                    <div class="vacina-card" onclick="irParaDetalhes(<?= $vacina['id'] ?>)">
                        <div class="vacina-image">
                            <img src="<?= htmlspecialchars($vacina['url_imagem']) ?>" alt="<?= htmlspecialchars($vacina['nome']) ?>">
                        </div>
                        <div class="vacina-info">
                            <h2 class="vacina-name"><?= htmlspecialchars($vacina['nome']) ?></h2>
                            <p class="vacina-info-item">
                                <strong>Idade recomendada:</strong> <?= htmlspecialchars($vacina['idade_recomendada']) ? htmlspecialchars($vacina['idade_recomendada']) : 'Não especificado' ?>
                            </p>
                            <p class="vacina-info-item">
                                <strong>Posto:</strong> <?= htmlspecialchars($vacina['posto_nome']) ?>
                            </p>
                            <p class="vacina-info-item">
                                <strong>Endereço do posto:</strong> <?= htmlspecialchars($vacina['rua']) ?>, <?= htmlspecialchars($vacina['numero']) ?> - <?= htmlspecialchars($vacina['bairro']) ?>
                            </p>
                            <p class="vacina-info-item">
                                <strong>Horário de funcionamento:</strong> <?= htmlspecialchars($vacina['horario_abertura']) ?> às <?= htmlspecialchars($vacina['horario_fechamento']) ?>
                            </p>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="no-results">Nenhuma vacina encontrada para a pesquisa.</p>
            <?php endif; ?>
        </div>
    </div>


    
<!-- Modal de Detalhes da Vacina -->
<div id="vacinaModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="fecharModal()">&times;</span>
        
        <!-- Imagem da Vacina -->
        <img class="modal-image" src="" alt="" style="width: 100%; max-width: 250px; max-height: 250px; margin-bottom: 20px; display: block; margin-left: auto; margin-right: auto;">
        
        <h2 class="modal-nome" style="color: #2a8dff; font-family: 'Arial', sans-serif; text-align: center;"></h2>
        <p class="modal-descricao" style="color: #333; font-family: 'Arial', sans-serif; line-height: 1.5;"></p>
        <p class="modal-idade" style="color: #555; font-family: 'Arial', sans-serif;"></p>
        <p class="modal-data" style="color: #555; font-family: 'Arial', sans-serif;"></p>
    </div>
</div>



<script>

function irParaDetalhes(id) {
    // Faz uma requisição AJAX para obter os detalhes da vacina
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "detalhes.php?id=" + id, true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Se a requisição for bem-sucedida, mostramos o modal com os dados
            var vacinaDetails = JSON.parse(xhr.responseText);
            
            if (vacinaDetails.error) {
                alert(vacinaDetails.error);
            } else {
                mostrarModal(vacinaDetails);
            }
        }
    };
    xhr.send();
}

function mostrarModal(vacinaDetails) {
    var modal = document.getElementById('vacinaModal');
    
    // Preenchendo os detalhes da vacina no modal
    modal.querySelector('.modal-nome').textContent = vacinaDetails.nome;
    modal.querySelector('.modal-descricao').textContent = vacinaDetails.descricao;
    modal.querySelector('.modal-idade').textContent = 'Idade Recomendada: ' + vacinaDetails.idade_recomendada;

    // Separando a data e a hora de "data_aplicacao"
    var dataAplicacao = vacinaDetails.data_aplicacao;  // Exemplo: "2024-11-25 14:30:00"
    
    // A parte da data (YYYY-MM-DD)
    var data = dataAplicacao.split(' ')[0];  // "2024-11-25"
    // A parte do horário (HH:MM:SS)
    var hora = dataAplicacao.split(' ')[1];  // "14:30:00"
    
    // Formatar a data no formato brasileiro (DD/MM/YYYY)
    var partesData = data.split('-');  // Separar "2024-11-25" em ["2024", "11", "25"]
    var dataFormatada = partesData[2] + '/' + partesData[1] + '/' + partesData[0];  // "25/11/2024"
    
    // Exibindo a data formatada no modal
    modal.querySelector('.modal-data').textContent = 'Data de Aplicação: ' + dataFormatada + ' às ' + hora.substr(0,5);
    

    // Adiciona a imagem da vacina
    var imgElement = modal.querySelector('.modal-image');
    imgElement.src = vacinaDetails.url_imagem;
    imgElement.alt = vacinaDetails.nome;

    // Exibe o modal
    modal.style.display = 'block';
}


function fecharModal() {
    // Fecha o modal
    var modal = document.getElementById('vacinaModal');
    modal.style.display = 'none';
}


    </script>
</body>
</html>

<?php
// Fechar conexão com o banco
$conn->close();
?>
