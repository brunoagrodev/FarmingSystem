<?php
//dados do banco de dados para fazer a conexão com o sistema
$servername = "179.188.16.2";
$username = "frutasbrasil";
$password = "dZ8S35cnCi#5!q";
$my_db = "frutasbrasil";
$port = "3306";

//Conexao com o banco de dados
$conn = new mysqli($servername, $username, $password,$my_db, $port);

//Teste conexao
if($conn->connect_error){
    die("Conexão Falho !");
}else{
    //echo("Deu certo");
}

?>