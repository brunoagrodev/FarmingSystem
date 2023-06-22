<?php
// Encerra a sessão atual do usuário
session_destroy();

// Redireciona o usuário de volta para a página de login (index.html)
header("location:../index.html");
?>