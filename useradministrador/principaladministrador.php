<?php
// Método para iniciar a sessão
global $conn;
session_start();

// Avalia se a sessão tem valores, foi definida, caso contrário redireciona o usuário para a página de login
if (!isset($_SESSION["emailUsuario"]) and !isset($_SESSION["idUsuarioLogin"])) {
    header("Location: ../index.html");
    die();
} else {
    $emailUsuario = $_SESSION["emailUsuario"];
    $idUsuarioLogin = $_SESSION["idUsuarioLogin"];
}

// Inclui o arquivo de conexão com o banco de dados
include('../controller/conexaoDataBaseV2.php');

// Seleciona os usuários e suas respectivas fotos
$sql = "SELECT usuarios.id, usuarios.nome, usuarios.datanascimento, usuarios.funcao, usuarios.cpf, fotos.nome_arquivo AS foto
        FROM usuarios
        JOIN fotos ON usuarios.foto = fotos.id";

// Consultar banco de dados
$query = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>...::: Administrador :::... </title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <!-- Custom styles for this template -->
    <link href="https://getbootstrap.com/docs/4.0/examples/starter-template/starter-template.css" rel="stylesheet">

</head>
<body>
<?php include("../useradministrador/include/navbarAdministrador.html"); ?>

<main role="main" class="container" style="margin-top: 40px;">
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <h2>Lista de Usuários</h2>
                <table id="mytable" class="table table-bordred table-striped">
                    <thead>
                    <th scope="col" class="text-center">Nome</th>
                    <th scope="col" class="text-center">Idade</th>
                    <th scope="col" class="text-center">Função</th>
                    <th scope="col" class="text-center">CPF</th>
                    <th scope="col" class="text-center">Foto</th>
                    <th scope="col" class="text-center">Informações</th>
                    <th scope="col" class="text-center"></th>
                    </thead>
                    <tbody>
                    <?php
                    while ($dado = mysqli_fetch_array($query)) {
                        // Calcula a idade baseada na data de nascimento
                        $dataNascimento = new DateTime($dado['datanascimento']);
                        $hoje = new DateTime();
                        $intervalo = $hoje->diff($dataNascimento);
                        $idade = $intervalo->y;

                        // Requisição da foto enviada no formulário
                        $arquivoFoto = 'uploads/' . $dado['foto'];

                        // Inserção dos dados cadastrados na tabela de usuários
                        echo "<tr>";
                        echo "<td class=" . "text-left" . ">" . $dado['nome'] . "</td>";
                        echo "<td class='text-center'>" . $idade . "</td>";
                        echo "<td class=" . "text-center" . ">" . $dado['funcao'] . "</td>";
                        echo "<td class=" . "text-center" . ">" . $dado['cpf'] . "</td>";
                        echo "<td class='text-center'><img src='uploads/" . $dado['foto'] . "' alt='Foto' width='40' height='35' style='border-radius: 50%;'></td>";
                        echo "<td class=" . "text-center" . ">" . '<a href="updateUsuario.php?id=' . $dado['id'] . '  " class="btn btn-primary btn-md" role="button" aria-pressed="true">Atualizar</a>' . "</td>";
                        echo "<td class=" . "text-center" . ">" . '<a href="deletarUsuarioBack.php?id=' . $dado['id'] . '  " class="btn btn-danger btn-md" role="button" aria-pressed="true">Deletar</a>' . "</td>";
                        echo "</tr>";
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<!-- Bootstrap e JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"
        integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"
        integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
        crossorigin="anonymous"></script>

</body>
</html>
