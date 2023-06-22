<?php
session_start();

// Verifica se as variáveis de sessão "emailUsuario" e "idUsuarioLogin" estão definidas
if (!isset($_SESSION["emailUsuario"]) && !isset($_SESSION["idUsuarioLogin"])) {
    // Se as variáveis de sessão não estiverem definidas, redireciona para a página inicial (index.html)
    header("Location: index.html");
    die();
} else {
    // Se as variáveis de sessão estiverem definidas, atribui seus valores a variáveis locais
    $emailUsuario = $_SESSION["emailUsuario"];
    $idUsuarioLogin = $_SESSION["idUsuarioLogin"];
}

// Recebe os valores do formulário através do método POST
$nome = $_POST['nome'];
$datanascimento = date('Y-m-d', strtotime($_POST['datanascimento']));
$email = $_POST['email'];
$senha = $_POST['pass'];
$cpf = $_POST['cpf'];
$telefone = $_POST['telephone'];

// Inclui o arquivo de conexão com o banco de dados
include('controller/conexaoDataBaseV2.php');

// Insere os dados na tabela cadastroSistema
$sqlCadastro = "INSERT INTO cadastroSistema (nome, datanascimento, email, senha, cpf, telefone, tipo, permissao)
                VALUES ('$nome', '$datanascimento', '$email', '$senha', '$cpf', '$telefone', 1, 1)";
$queryCadastro = mysqli_query($conn, $sqlCadastro);

if ($queryCadastro) {
    // Se o cadastro na tabela cadastroSistema for bem-sucedido, redireciona para a página inicial (index.html)

    // Adiciona o comando INSERT INTO para a tabela usuarios
    $idCadastro = mysqli_insert_id($conn);
    $sqlUsuarios = "INSERT INTO usuarios (id, nome, email, senha, datanascimento, funcao, cpf, telefone, foto) 
                    VALUES ('$idCadastro', '$nome', '$email', '$senha', '$datanascimento', 'Admin', '$cpf', '$telefone', NULL)";
    $queryUsuarios = mysqli_query($conn, $sqlUsuarios);

    if ($queryUsuarios) {
        header("Location: index.html");
    } else {
        // Se houver um erro ao cadastrar na tabela usuarios, exibe uma mensagem de erro
        echo "Erro ao cadastrar na tabela usuarios";
        exit();
    }
} else {
    // Se houver um erro ao cadastrar na tabela cadastroSistema, exibe uma mensagem de erro
    echo "Cadastro não efetivado-erro";
    exit();
}
?>
