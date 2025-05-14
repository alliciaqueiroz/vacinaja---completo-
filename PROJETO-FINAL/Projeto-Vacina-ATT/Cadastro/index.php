<?php
session_start();
if(isset($_SESSION['admin_prefeitura']) && $_SESSION['admin_prefeitura'] === 0){
    header('Location: ../Catálogo de vacinas - ADM/index.php');
    exit;
}

// Dados de conexão com o banco de dados
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

// Consulta SQL para buscar todos os postos
$sql = "SELECT id, nome FROM Posto";
$result = $conn->query($sql);

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
    <svg class="background-svg" xmlns="http://www.w3.org/2000/svg" width="792" height="412" viewBox="0 0 792 412" fill="none">
        <path d="M494.599 71.0521C943.78 140.442 879.898 250.112 326.905 410.373C24.9534 424.776 251.042 322.322 0 71.0521C0 -30.4356 201.831 7.13574 310.434 7.13574C419.036 7.13574 494.599 -30.4356 494.599 71.0521Z"  stroke="#B5E2ED" fill="url(#paint0_linear_111_4)"/>
        <defs>
            <linearGradient id="paint0_linear_111_4" x1="395.79" y1="0.335876" x2="395.79" y2="411.697" gradientUnits="userSpaceOnUse">
                <stop stop-color="#B1EEFF"/>
                <stop offset="1" stop-color="#8ED0E2"/>
            </linearGradient>
        </defs>
    </svg>

    <svg class="star-svg" xmlns="http://www.w3.org/2000/svg" width="177" height="177" viewBox="0 0 177 177" fill="none">
        <path d="M70.1156 0.380221L109.725 56.5755L176.998 38.4107L134.206 91.306L173.815 147.501L107.758 123.997L64.9656 176.892L66.9327 109.471L0.875629 85.9667L68.1485 67.8019L70.1156 0.380221Z" fill="#9AD4E4"/>
    </svg>

    <svg class="triangulo-svg" xmlns="http://www.w3.org/2000/svg" width="173" height="156" viewBox="0 0 173 156" fill="none">
        <path d="M26.8 0.525914L172.82 76.9965L0.894164 155.958L26.8 0.525914Z" fill="#9BD3E2"/>
      </svg>

    <div class="caixinha_de_cadastro">
        <h1>Cadastro</h1>
        <form class="form-container" action="cadastro.php" method="post">
            <label for="nome">Nome completo:</label>
            <input type="text" id="nome" name="nome" maxlength="80" required/>

            <label for="cpf">CPF:</label>
            <input type="text" id="cpf" name="cpf" maxlength="14" required/>

            <label for="email">E-mail:</label>
            <input type="text" id="email" name="email" maxlength="256" required/>

            <label for="senha">Senha:</label>
            <div class="senha-container">
                <input type="password" id="senha" name="senha" maxlength="12" required/>
                <svg class="eye-svg" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 30 30" fill="none">
                    <path d="M15.0004 3.75C21.7405 3.75 27.348 8.5997 28.5236 15C27.348 21.4002 21.7405 26.25 15.0004 26.25C8.26015 26.25 2.65269 21.4002 1.47705 15C2.65269 8.5997 8.26015 3.75 15.0004 3.75ZM15.0004 23.75C20.2949 23.75 24.8254 20.065 25.9721 15C24.8254 9.93504 20.2949 6.25 15.0004 6.25C9.70575 6.25 5.17528 9.93504 4.02848 15C5.17528 20.065 9.70575 23.75 15.0004 23.75ZM15.0004 20.625C11.8937 20.625 9.37532 18.1066 9.37532 15C9.37532 11.8934 11.8937 9.375 15.0004 9.375C18.1069 9.375 20.6254 11.8934 20.6254 15C20.6254 18.1066 18.1069 20.625 15.0004 20.625ZM15.0004 18.125C16.7263 18.125 18.1254 16.7259 18.1254 15C18.1254 13.2741 16.7263 11.875 15.0004 11.875C13.2745 11.875 11.8753 13.2741 11.8753 15C11.8753 16.7259 13.2745 18.125 15.0004 18.125Z" fill="#102E46"/>
                </svg>
            </div>
            
            <label for="telefone">Telefone:</label>
            <input type="tel" id="telefone" name="telefone" maxlength="14" required>

            <label for="id_posto">Posto de Saúde</label>
            <select id="id_posto" name="id_posto">
                <option value=""></option>
                <?php
                // Verifica se há postos cadastrados no banco
                if ($result->num_rows > 0) {
                    // Loop para preencher as opções do select com os postos do banco
                    while($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "'>" . $row['nome'] . "</option>";
                    }
                } else {
                    echo "<option value=''>Nenhum posto cadastrado</option>";
                }
                ?>
              </select>

            <button type="submit">Cadastre-se</button>
        </form>
    </div>

<script src="script.js"></script>

</body>
</html>
