<?php
//dados do banco de dados para fazer a conexão com o sistema
$servername = "";
$username = "";
$password = "";
$my_db = "";
$port = "";

//Conexao com o banco de dados
$conn = new mysqli($servername, $username, $password,$my_db, $port);

//Teste conexao
if($conn->connect_error){
    die("Conexão Falho !");
}else{
    //echo("Deu certo");
}

?>
