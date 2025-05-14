<?php
session_start();
if(!isset($_SESSION['email'])){
    header('Location: ../Login/index.html');
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
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./vars.css">
  <link rel="stylesheet" href="./style.css">
  <title>VacinaJá</title>
  <link rel="shortcut icon" type="imagex/png" href="gota.svg">
  
  <style>
   a,
   button,
   input,
   select,
   h1,
   h2,
   h3,
   h4,
   h5,
   * {
       box-sizing: border-box;
       margin: 0;
       padding: 0;
       border: none;
       text-decoration: none;
       background: none;
   
       -webkit-font-smoothing: antialiased;
   }
   
   menu, ol, ul {
       list-style-type: none;
       margin: 0;
       padding: 0;
   }

   </style>
  <title>Cadastro de Vacinas</title>
</head>
<body>
  <div class="frame-34">
    <div class="frame-14">
      <div class="frame-10">
        <div class="water-drop-svgrepo-com-3">
          <img class="group" src="gota.svg" />
        </div>
      </div>
    </div>
    <form class="frame-59" method="post" action="CadastroVacina.php" enctype="multipart/form-data">
      <div class="arq">
        <label for="arquivo">
          <input type="file" id="arquivo" name="arquivo">
        <div class="frame-15">
          <img src="File.svg" id="imagem"/>   
        </div>
      </label>
      </div>
      <div class="frame-20">
        <div class="frame-58">
          <div class="frame-43">
            <div class="frame-45">
              <div class="tipo-de-vacina">
                Nome da vacina:
              </div>
              <label for="tip-vacina">
                <input type="text" id="tip-vacina" required name="nome" style="color: white; width: 625px; margin-top: 5px;">
              </label>
            
              <div class="rectangle-2"></div>
            </div>
          </div>
          <div class="frame-44">
            <div class="frame-45">
              <div class="idade-recomendada">Idade recomendada:</div>
              <label for="idade">
                <input type="text" id="idade" required name="idade" style="color: white; width: 625px; margin-top: 5px;">
              </label>
              <div class="rectangle-2"></div>
            </div>
          </div>
          
          
          
          <!-- Campo de horário -->
          <div class="frame-45">
            <div class="inserir-hor-rio-dispon-vel">
              Inserir horário disponível:
            </div>
            <label for="hora">
              <input type="time" required id="hora" name="hora" style="color: white; width: 625px; margin-top: 5px;">
            </label>
            <div class="rectangle-2"></div>
          </div>
          
          <!-- Campo de data -->
          <div class="frame-49">
            <div class="data-00-00-0000">Data:</div>
            <label for="data">
              <input type="date" id="data" required name="data" style="color: white; width: 625px;margin-top: 5px;">
            </label>
            <div class="rectangle-2"></div>
          </div>  
          
          <!-- Descrição da vacina -->
          <div class="frame-51">
            <div class="descri-o-de-vacina" for="descricao">Descrição de vacina:</div>
            <textarea id="descricao" name="descricao" rows="7"
            cols="55" style="background-color: white; opacity: 0.6; border-radius: 8px;">
            </textarea>
          </div>
          
          <div class="button-container">
          <a class="voltar" href="../Catálogo de vacinas - ADM/index.php">Voltar</a>
            <div class="frame-50">
              <button class="adicionar" type="submit">Adicionar</button>
            </div>
          </div>
          
        </div>
      </div>
    </form>
  </div>

  <script>
   // Seleciona o input de arquivo e as tags de imagem
const inputArquivo = document.getElementById('arquivo');
const imagem = document.getElementById('imagem');

// Adiciona o evento de mudança no input de arquivo
inputArquivo.addEventListener('change', function(event) {
  const file = event.target.files[0];

  if (file) {
    // Cria uma URL de objeto para o arquivo de imagem
    const reader = new FileReader();

    reader.onload = function(e) {
      // Altera o src da imagem para o arquivo escolhido
      imagem.src = e.target.result;

      // Torna a imagem visível
      imagem.style.visibility = "visible";

      // Remove as dimensões fixas se você usar o estilo acima no CSS
      imagem.style.width = "100%";  // Agora a largura é 100% da div
      imagem.style.height = "100%"; // Agora a altura é 100% da div
      imagem.style.objectFit = "cover"; // Faz com que a imagem cubra a div sem distorcer
    };

    // Lê o arquivo de imagem
    reader.readAsDataURL(file);
  } else {
    console.log("Nenhum arquivo selecionado.");
  }
});
  </script>
</body>
</html>
