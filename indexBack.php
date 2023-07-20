<?php

include('controller/conexaoDataBaseV2.php');

//Metodo para iniciar a sessao
session_start();

//Recebe elementos POST
$emailA = $_POST['email'];
$pass = $_POST['pass'];


//Seleção no banco de dados tabela loginSistema
$sqlA = "SELECT * FROM `cadastroSistema` WHERE `email` = '$emailA' AND `senha` = '$pass' ";
$queryA = mysqli_query($conn, $sqlA);

$permissaoAcesso = 0;
$tipoUsuario = 0;

//Processamento das respostas
while($dados = mysqli_fetch_assoc($queryA)){
    $idUsuarioLogin=$dados["id"];
    $emailBx=$dados["email"]; //usuario tem somente email nao é o nome --> Tab. loginSistemas
    $tipoUsuario=$dados['tipo'];
    $permissaoAcesso = $dados['permissao'];
}

switch(true){
    case ($tipoUsuario == 1): //Configura a sessao Administrador
        $_SESSION["emailUsuario"] = $emailBx;
        $_SESSION["idUsuarioLogin"] = $idUsuarioLogin;
        //$_SESSION["administrador"] = selecionaIdAdministrador($emailA);
        //cadastraLogAcesso($emailBx); //Cadastro da hora do login
        header("Location: useradministrador/principaladministrador.php?email=$emailBx");
        break;

    default:
        // $script="<script>alert('Login/Senha Errados !');</script>";
        //echo $script;
        header("Location: index.html");
        break;
}


?>

