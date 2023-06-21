<?php
//Metodo para iniciar a sessao
session_start();
//Avalia se a sessao tem valores, foi definida, caso nao retorna o user para o login
if (!isset($_SESSION["emailUsuario"]) and !isset($_SESSION["idUsuarioLogin"])) {
    header("Location: ../index.html");
    die();
} else {
    $emailUsuario = $_SESSION["emailUsuario"];
    $idUsuarioLogin = $_SESSION["idUsuarioLogin"];
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Usuário</title>

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

    <form id="formUsuarioCadastro" class="was-validated" action="cadastrarUsuarioBack.php" method="POST"
          enctype="multipart/form-data">
        <label>Nome do Usuário:</label>
        <div class="md-3 pb-1">
            <div class="form-outline">
                <input type="text" class="form-control is-valid" id="validationTextarea" placeholder="Campo obrigatorio"
                       name="nome" required>
            </div>
        </div>

        <label>Digite seu E-mail:</label>
        <div class="md-3 pb-1">
            <div class="form-outline">
                <input type="email" class="form-control is-valid" id="email" placeholder="Campo obrigatório"
                       name="email" required>
            </div>
        </div>

        <label>Digite uma senha:</label>
        <div class="md-3 pb-1">
            <div class="form-outline">
                <input type="password" class="form-control is-valid" id="senha" placeholder="Campo obrigatório"
                       name="senha" required>
            </div>
        </div>

        <label>Data de Nascimento:</label>
        <div class="md-3 pb-1">
            <div class="form-outline">
                <input type="date" class="form-control is-valid" id="datanascimento" placeholder="Campo obrigatório"
                       name="datanascimento" required>
            </div>
        </div>

        <label>Função:</label>
        <div class="pb-3">
            <select class="form-control is-valid" id="validationTextarea" class="form-select"
                    aria-label="Default select example" name="funcao" required>
                <option value="Docente">Docente</option>
                <option value="Dicente">Discente</option>
            </select>
        </div>

        <label>CPF:</label>
        <div class="md-3 pb-1">
            <div class="form-outline">
                <input type="number" class="form-control is-valid" id="cpf"
                       placeholder="Campo obrigatorio" name="cpf" required>
            </div>
        </div>

        <label>Telefone:</label>
        <div class="md-3 pb-1">
            <div class="form-outline">
                <input type="number" class="form-control is-valid" id="telefone"
                       placeholder="Campo obrigatorio" name="telefone" required>
            </div>
        </div>

        <label>Foto:</label>
        <div class="md-3 pb-1">
            <div class="form-outline">
                <input type="file" class="form-control is-valid" id="validationTextarea"
                       name="foto" required>
            </div>
        </div>

        <div class="buttonregister">
            <div class="text-center pb-4">
                <button id="cadastrarUsuarioBtn" class="btn btn-primary btn-lg text-center"  onclick="return validarFormulario()" type="submit">Cadastrar
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
        crossorigin="anonymous">
</script>

<script src="../js/script.js"></script>


</body>
</html>
