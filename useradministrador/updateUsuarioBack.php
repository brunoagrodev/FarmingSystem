<?php
// Método para iniciar a sessão
session_start();
// Avalia se a sessão tem valores, foi definida, caso não, redireciona o usuário para o login
if (!isset($_SESSION["emailUsuario"]) and !isset($_SESSION["idUsuarioLogin"])) {
    header("Location: ../index.html");
    die();
} else {
    $emailUsuario = $_SESSION["emailUsuario"];
    $idUsuarioLogin = $_SESSION["idUsuarioLogin"];
}

// Variáveis para receber as informações do formulário online
$idUsuario = $_POST['id'];
$nome = $_POST['nome'];
$email = $_POST['email'];
$senha = $_POST['senha'];
$datanascimento = $_POST['datanascimento'];
$funcao = $_POST['funcao'];
$cpf = $_POST['cpf'];
$telefone = $_POST['telefone'];
$foto = $_FILES['foto'];

$nomeArquivo = $foto['name'];
$caminhoTemporario = $foto['tmp_name'];
$caminhoDestino = 'uploads/' . $nomeArquivo;

move_uploaded_file($caminhoTemporario, $caminhoDestino);

include('../controller/conexaoDataBaseV2.php');

// Verifica se foi fornecida uma nova foto
if (!empty($nomeArquivo)) {
    // Inserir a nova foto na tabela "fotos"
    $sqlFoto = "INSERT INTO fotos (nome_arquivo, caminho)
                VALUES ('$nomeArquivo', '$caminhoDestino')";
    $queryFoto = mysqli_query($conn, $sqlFoto);
    $idFoto = mysqli_insert_id($conn);

    // Atualizar a foto do usuário na tabela "usuarios"
    $sqlUpdate = "UPDATE usuarios 
                  SET nome = '$nome', email = '$email', senha = '$senha', datanascimento = '$datanascimento', funcao = '$funcao', cpf = '$cpf', telefone = '$telefone', foto = '$idFoto'
                  WHERE id = $idUsuario";
} else {
    // Atualizar os dados do usuário, exceto a foto
    $sqlUpdate = "UPDATE usuarios 
                  SET nome = '$nome', email = '$email', senha = '$senha', datanascimento = '$datanascimento', funcao = '$funcao', cpf = '$cpf', telefone = '$telefone'
                  WHERE id = $idUsuario";
}

// Consultar banco de dados
$queryUpdate = mysqli_query($conn, $sqlUpdate);

// Se o update for efetivado com sucesso, o sistema volta para a página principal
if ($queryUpdate == true) {
    // Atualizar também a tabela cadastroSistema
    $sqlCadastro = "UPDATE cadastroSistema 
                    SET nome = '$nome', datanascimento = '$datanascimento', email = '$email', senha = '$senha', cpf = '$cpf', telefone = '$telefone'
                    WHERE id = $idUsuario";
    $queryCadastro = mysqli_query($conn, $sqlCadastro);

    if ($queryCadastro) {
        header("Location: ../useradministrador/principaladministrador.php");
    } else {
        echo "Erro ao atualizar os dados na tabela cadastroSistema.";
        exit();
    }
} else {
    echo "Erro ao atualizar os dados do usuário.";
    exit();
}
?>
