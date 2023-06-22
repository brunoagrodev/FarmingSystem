<?php
global $conn;
include('controller/conexaoDataBaseV2.php');

// Método para iniciar a sessão
session_start();

// Recebe elementos POST
$emailA = $_POST['email'];
$pass = $_POST['pass'];

// Seleção no banco de dados tabela loginSistema
$sqlA = "SELECT * FROM `cadastroSistema` WHERE `email` = '$emailA' AND `senha` = '$pass' ";
// Consultar banco de dados
$queryA = mysqli_query($conn, $sqlA);

$tipoUsuario = 0;
$permissaoAcesso = 0;

// Processamento das respostas
while ($dados = mysqli_fetch_assoc($queryA)) {
    $idUsuarioLogin = $dados["id"];
    $emailBx = $dados["email"];
    $tipoUsuario = $dados['tipo'];
    $permissaoAcesso = $dados['permissao'];
}

switch ($tipoUsuario) {
    case 0: // Se o tipo de usuário for igual a 0 (Usuário comum)
        // Configura a sessão Usuário
        $_SESSION["emailUsuario"] = $emailBx;
        $_SESSION["idUsuarioLogin"] = $idUsuarioLogin;
        header("Location: usercomum/principalusuario.php?email=$emailBx");
        break;

    case 1: // Se o tipo de usuário for igual a 1 (Administrador)
        // Configura a sessão Administrador
        $_SESSION["emailUsuario"] = $emailBx;
        $_SESSION["idUsuarioLogin"] = $idUsuarioLogin;
        // Redireciona para a página principal do administrador, passando o email como parâmetro na URL
        header("Location: useradministrador/principaladministrador.php?email=$emailBx");
        exit();
        break;

    default:
        // Redireciona para a página inicial (index.html) se o tipo de usuário não for 0 ou 1
        header("Location: index.html");
        exit();
        break;
}
?>


