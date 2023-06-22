<?php
// Método para iniciar a sessão
session_start();

// Avalia se a sessão tem valores, foi definida, caso contrário retorna o usuário para a página de login
if (!isset($_SESSION["emailUsuario"]) and !isset($_SESSION["idUsuarioLogin"])) {
    header("Location: ../index.html");
    die();
} else {
    $emailUsuario = $_SESSION["emailUsuario"];
    $idUsuarioLogin = $_SESSION["idUsuarioLogin"];
}

// Variável para receber o ID do usuário a ser deletado, vindo por meio da requisição GET
$id = $_GET['id'];

// Inclui o arquivo de conexão com o banco de dados
include('../controller/conexaoDataBaseV2.php');

// Comando SQL para deletar registros das tabelas "usuarios" e "fotos" onde o ID corresponde ao valor fornecido
$sql = "DELETE usuarios, fotos, cadastroSistema
        FROM usuarios
        JOIN fotos ON usuarios.foto = fotos.id
        JOIN cadastroSistema ON usuarios.id = cadastroSistema.id
        WHERE usuarios.id = $id";

// Consultar banco de dados
$query = mysqli_query($conn, $sql);

// Se o delete for efetivado com sucesso, o sistema volta para a página principal
if ($query == true) {
    header("Location: ../useradministrador/principaladministrador.php");
} else {
    echo "Delete não efetivado-erro";
}
?>
