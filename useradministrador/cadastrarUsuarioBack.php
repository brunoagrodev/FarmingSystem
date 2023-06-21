<?php
// Método para iniciar a sessão
session_start();

// Avalia se a sessão tem valores, foi definida, caso não, redireciona o usuário para o login
if (!isset($_SESSION["emailUsuario"]) && !isset($_SESSION["idUsuarioLogin"])) {
    header("Location: ../index.html");
    die();
} else {
    $emailUsuario = $_SESSION["emailUsuario"];
    $idUsuarioLogin = $_SESSION["idUsuarioLogin"];
}

// Variáveis para receber as informações do formulário online
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

// Move o arquivo enviado para o diretório de destino
move_uploaded_file($caminhoTemporario, $caminhoDestino);

// Incluir arquivo de conexão com o banco de dados
include('../controller/conexaoDataBaseV2.php');

// Inserir a foto na tabela "fotos"
$sqlFoto = "INSERT INTO fotos (nome_arquivo, caminho)
            VALUES ('$nomeArquivo', '$caminhoDestino')";

// Consultar banco de dados
$queryFoto = mysqli_query($conn, $sqlFoto);
$idFoto = mysqli_insert_id($conn);

// Comando SQL para inserir as informações no banco de dados na tabela "usuarios"
$sqlUsuario = "INSERT INTO usuarios (nome, email, senha, datanascimento, funcao, telefone, cpf, foto)
               VALUES ('$nome', '$email', '$senha', '$datanascimento', '$funcao', '$telefone', '$cpf', '$idFoto')";

// Consultar banco de dados
$queryUsuario = mysqli_query($conn, $sqlUsuario);

// Se o cadastro na tabela "usuarios" for efetivado com sucesso, insere também os dados na tabela "cadastroSistema"
if ($queryUsuario) {
    $sqlCadastro = "INSERT INTO cadastroSistema (nome, datanascimento, email, senha, cpf, telefone, tipo, permissao)
                    VALUES ('$nome', '$datanascimento', '$email', '$senha', '$cpf', '$telefone', 0, 0)";
    $queryCadastro = mysqli_query($conn, $sqlCadastro);

    if ($queryCadastro) {
        // Se o cadastro na tabela "cadastroSistema" for bem-sucedido, o sistema volta para a página principal
        header("Location: ../useradministrador/principaladministrador.php");
    } else {
        // Se houver um erro ao cadastrar na tabela "cadastroSistema", exibe uma mensagem de erro
        echo "Erro ao cadastrar na tabela cadastroSistema.";
        exit();
    }
} else {
    // Se houver um erro ao cadastrar na tabela "usuarios", exibe uma mensagem de erro
    echo "Erro ao cadastrar na tabela usuarios.";
    exit();
}
?>
