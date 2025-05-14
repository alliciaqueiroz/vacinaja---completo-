<?php
// Inicia a sessão
session_start();

// Destrua todas as variáveis de sessão
session_unset();

// Destrua a sessão
session_destroy();

// Redireciona para a página de login
header("Location: ../Login/index.html");
exit();
?>
