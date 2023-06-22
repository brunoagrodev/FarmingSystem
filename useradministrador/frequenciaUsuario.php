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

// Verifica se o parâmetro "id" está presente na URL
if (isset($_GET['id'])) {
    $idUsuario = $_GET['id'];

    // Consulta o banco de dados apenas para o usuário específico
    $sql = "SELECT usuarios.id, usuarios.nome, usuarios.datanascimento, usuarios.funcao, usuarios.cpf, fotos.nome_arquivo AS foto
            FROM usuarios
            JOIN fotos ON usuarios.foto = fotos.id
            WHERE usuarios.id = $idUsuario";

    // Consultar banco de dados
    $query = mysqli_query($conn, $sql);

    // Verifica se o usuário existe no banco de dados
    if (mysqli_num_rows($query) == 0) {
        // Redireciona para a página de lista de usuários se o usuário não for encontrado
        header("Location: ./principaladministrador.php");
        die();
    }

    // Obtém os dados do usuário
    $dado = mysqli_fetch_array($query);

    // Consulta para obter o histórico do usuário
    $sqlHistorico = "SELECT data, frequencia FROM historico WHERE id_usuario = $idUsuario";
    $queryHistorico = mysqli_query($conn, $sqlHistorico);
}
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
                <h2>Frequência do Usuário</h2>
                <table id="mytable" class="table table-bordred table-striped">
                    <thead>
                    <th scope="col" class="text-center">Nome</th>
                    <th scope="col" class="text-center">Compareceu</th>
                    <th scope="col" class="text-center">Não compareceu</th>
                    <th scope="col" class="text-center">Histórico</th>
                    </thead>
                    <tbody>
                    <?php if (!empty($dado)) { ?>
                        <tr>
                            <td class="text-left"><?php echo $dado['nome']; ?></td>
                            <td class="text-center">
                                <form action="frequenciaUsuarioBack.php" method="post">
                                    <input type="hidden" name="idUsuario" value="<?php echo $dado['id']; ?>">
                                    <button type="submit" name="compareceu" class="btn btn-success btn-md">Sim</button>
                                </form>
                            </td>
                            <td class="text-center">
                                <form action="frequenciaUsuarioBack.php" method="post">
                                    <input type="hidden" name="idUsuario" value="<?php echo $dado['id']; ?>">
                                    <button type="submit" name="naoCompareceu" class="btn btn-danger btn-md">Não
                                    </button>
                                </form>
                            </td>
                            <td class="text-center">
                                <a href="#" class="btn btn-info" data-toggle="modal"
                                   data-target="#modal-<?php echo $dado['id']; ?>">Ver</a>
                                <div class="modal fade" id="modal-<?php echo $dado['id']; ?>" tabindex="-1"
                                     role="dialog" aria-labelledby="modalLabel-<?php echo $dado['id']; ?>"
                                     aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modalLabel-<?php echo $dado['id']; ?>">
                                                    Histórico do Usuário: <?php echo $dado['nome']; ?></h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <?php if (!empty($queryHistorico) && mysqli_num_rows($queryHistorico) > 0) { ?>
                                                    <table class="table table-bordered table-striped">
                                                        <thead>
                                                        <tr>
                                                            <th>Data</th>
                                                            <th>Frequência</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php while ($historico = mysqli_fetch_array($queryHistorico)) { ?>
                                                            <tr>
                                                                <td><?php echo $historico['data']; ?></td>
                                                                <td><?php echo $historico['frequencia']; ?></td>
                                                            </tr>
                                                        <?php } ?>
                                                        </tbody>
                                                    </table>
                                                <?php } else { ?>
                                                    <p>Nenhum registro de histórico encontrado.</p>
                                                <?php } ?>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                    Fechar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php } else { ?>
                        <tr>
                            <td colspan="4">Nenhum dado encontrado para exibir.</td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
                <div class="d-flex justify-content-center align-items-center" style="margin-top: 25px">
                    <a href="./principaladministrador.php" class="btn btn-primary">Voltar à lista de usuários</a>
                </div>
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

<script>
    $(document).ready(function () {
        $('a[data-toggle="modal"]').click(function () {
            var targetModal = $(this).attr('data-target');
            $(targetModal).modal('show');
        });
    });
</script>

</body>
</html>
