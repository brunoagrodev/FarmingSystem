<?php
// Método para iniciar a sessão
session_start();

// Avalia se a sessão tem valores, foi definida, caso contrário redireciona o usuário para a página de login
if (!isset($_SESSION["emailUsuario"]) && !isset($_SESSION["idUsuarioLogin"])) {
    header("Location: ../index.html");
    die();
} else {
    $emailUsuario = $_SESSION["emailUsuario"];
    $idUsuarioLogin = $_SESSION["idUsuarioLogin"];
}

// Inclui o arquivo de conexão com o banco de dados
include('../controller/conexaoDataBaseV2.php');

// Verifica se o parâmetro "idUsuario" está presente no formulário
if (isset($_POST['idUsuario'])) {
    $idUsuario = $_POST['idUsuario'];

    if (isset($_POST['compareceu'])) {
        // Insere a frequência do usuário como "Sim"
        $frequencia = 'Sim';
    } elseif (isset($_POST['naoCompareceu'])) {
        // Insere a frequência do usuário como "Não"
        $frequencia = 'Não';
    }

    // Insere o registro de frequência na tabela "historico"
    $sqlInsert = "INSERT INTO historico (id_usuario, data, frequencia) VALUES ('$idUsuario', CURDATE(), '$frequencia')";
    $resultInsert = mysqli_query($conn, $sqlInsert);

    if ($resultInsert) {
        //Ao clicar no botão Sim ou Não, continua na página de frequência
        header("Location: ./frequenciaUsuario.php?id=$idUsuario");
        exit();
    } else {
        echo "Erro ao inserir registro de frequência: " . mysqli_error($conn);
    }
}
?>
