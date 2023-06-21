<?php
// Método para iniciar a sessão
session_start();

// Avalia se a sessão tem valores, foi definida, caso contrário redireciona o usuário para a página de login
if (!isset($_SESSION["emailUsuario"]) and !isset($_SESSION["idUsuarioLogin"])) {
    header("Location: ../index.html");
    die();
} else {
    $emailUsuario = $_SESSION["emailUsuario"];
    $idUsuarioLogin = $_SESSION["idUsuarioLogin"];
}

$id = $_GET['id'];

include('../controller/conexaoDataBaseV2.php');

$sql = "SELECT * FROM `usuarios` WHERE id = $id";

// Consultar banco de dados
$query = mysqli_query($conn, $sql);

// Loop para recuperar os dados do usuário encontrado na consulta SQL
while ($dado = mysqli_fetch_array($query)) {
    $nome = $dado['nome'];
    $email = $dado['email'];
    $senha = $dado['senha'];
    $datanascimento = $dado['datanascimento'];
    $funcao = $dado['funcao'];
    $cpf = $dado['cpf'];
    $telefone = $dado['telefone'];
    $foto = $dado['foto'];
}
?>


<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exibir/Atualizar Usuário</title>

    <style>
        .was-validated {
            width: 50%;
            position: relative;
            left: 25%;
        }

        .buttonregister {
            margin-top: 20px;
        }
    </style>

    <!--Bootstrap CSS-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <!-- Custom styles for this template -->
    <link href="https://getbootstrap.com/docs/4.0/examples/starter-template/starter-template.css" rel="stylesheet">

</head>
<body>

<?php include("../useradministrador/include/navbarAdministrador.html"); ?>

<main role="main" class="container">

    <form id="formUsuarioAtualizar" class="was-validated" action="updateUsuarioBack.php" method="POST"
          enctype="multipart/form-data">

        <input type="hidden" name="id" value="<?php echo $id; ?>">

        <label>Nome do Usuário:</label>
        <div class="md-3 pb-1">
            <div class="form-outline">
                <input type="text" class="form-control is-valid" id="nome" placeholder="Campo obrigatorio"
                       name="nome" value="<?php echo $nome; ?>" required>
                <div class="invalid-feedback">Digite o nome do usuário:</div>
            </div>
        </div>

        <label>Email:</label>
        <div class="md-3 pb-1">
            <div class="form-outline">
                <input type="email" class="form-control is-valid" id="email" placeholder="Campo obrigatorio"
                       name="email" value="<?php echo $email; ?>" required>
                <div class="invalid-feedback">Digite o seu email.</div>
            </div>
        </div>

        <label>Senha:</label>
        <div class="md-3 pb-1">
            <div class="form-outline">
                <input type="password" class="form-control is-valid" id="senha" placeholder="Campo obrigatorio"
                       name="senha" value="<?php echo $senha; ?>" required>
                <div class="invalid-feedback">Digite a sua senha.</div>
            </div>
        </div>

        <label>Data de Nascimento:</label>
        <div class="md-3 pb-1">
            <div class="form-outline">
                <input type="date" class="form-control is-valid" id="datanascimento" placeholder="Campo obrigatorio"
                       name="datanascimento" value="<?php echo $datanascimento; ?>" required>
                <div class="invalid-feedback">Digite a data que você nasceu.</div>
            </div>
        </div>

        <label>Função:</label>
        <div class="pb-3">
            <select class="form-control is-valid" id="funcao" class="form-select"
                    aria-label="Default select example" name="funcao" required>
                <option value="<?php echo $funcao ?>"><?php echo $funcao; ?></option>
                <option value="Docente">Docente</option>
                <option value="Dicente">Discente</option>
            </select>
        </div>

        <label>CPF:</label>
        <div class="md-3 pb-1">
            <div class="form-outline">
                <input type="text" class="form-control is-valid" id="cpf" placeholder="Campo obrigatorio"
                       name="cpf" value="<?php echo $cpf; ?>" required>
                <div class="invalid-feedback">Digite o seu CPF.</div>
            </div>
        </div>

        <label>Telefone:</label>
        <div class="md-3 pb-1">
            <div class="form-outline">
                <input type="number" class="form-control is-valid" id="telefone"
                       placeholder="Campo obrigatorio" name="telefone"  value="<?php echo $telefone; ?>"required>
            </div>
        </div>

        <label>Foto:</label>
        <div class="md-3 pb-1">
            <div class="form-outline">
                <input type="file" class="form-control is-valid" id="foto"
                       name="foto" value="<?php echo $foto; ?>">
                <div class="invalid-feedback">Selecione uma foto.</div>
            </div>
        </div>

        <div class="buttonregister">
            <div class="text-center pb-4">
                <button id="atualizarUsuarioBtn" class="btn btn-primary btn-lg text-center" onclick="return validarFormulario()" type="submit">Atualizar
                    Usuário
                </button>
            </div>
        </div>

    </form>
</main>


<!--Bootstrap e JS-->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"
        integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"
        integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
        crossorigin="anonymous"></script>

<script src="../js/script.js"></script>


</body>
</html>
