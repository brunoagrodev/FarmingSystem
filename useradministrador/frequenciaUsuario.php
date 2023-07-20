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
    $sql = "SELECT usuarios.id, usuarios.nome, usuarios.datanascimento, usuarios.funcao, usuarios.cpf, usuarios.telefone, usuarios.chavepix, fotos.nome_arquivo AS foto
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

    // Consulta para obter o histórico do usuário, ordered by weekNumber in ascending order
    $sqlHistorico = "SELECT weekNumber, data, frequencia, frequencia_value 
                     FROM historico 
                     WHERE id_usuario = $idUsuario 
                     ORDER BY weekNumber ASC";
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
            <?php if (!empty($dado)) { ?>
                <div class="table-responsive">
                    <h2>Frequência do Funcionário</h2>
                    <table id="mytable" class="table table-bordred table-striped">
                        <thead>
                        <tr>
                            <th scope="col" class="text-center">Nome</th>
                            <th scope="col" class="text-center">Compareceu</th>
                            <th scope="col" class="text-center">Não compareceu</th>
                            <th scope="col" class="text-center">Histórico</th>
                        </tr>
                        </thead>
                        <tbody>
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
                                    <!-- Modal content for historical data -->
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modalLabel-<?php echo $dado['id']; ?>">
                                                    Histórico do Funcionário: <?php echo $dado['nome']; ?></h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <?php if (!empty($queryHistorico) && mysqli_num_rows($queryHistorico) > 0) { ?>
                                                    <?php
                                                    $count = 0;
                                                    while ($historico = mysqli_fetch_array($queryHistorico)) {
                                                        if ($count % 6 === 0) {
                                                            echo '<h5>Week ' . ceil(($count + 1) / 6) . '</h5>';
                                                            echo '<table class="table table-bordered table-striped">';
                                                            echo '<thead><tr><th>Data</th><th>Frequência</th></tr></thead>';
                                                            echo '<tbody>';
                                                        }
                                                        $count++;
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $historico['data']; ?></td>
                                                            <td><?php echo $historico['frequencia']; ?></td>
                                                        </tr>
                                                        <?php
                                                        if ($count % 6 === 0) {
                                                            echo '</tbody></table>';
                                                            echo '<div class="text-center">';
                                                            echo '<button type="button" class="btn btn-primary generate-qr-btn">Gerar QR Code</button>';
                                                            echo '</div>';
                                                            echo '<div class="qr-code-container"></div>';
                                                        }
                                                    }
                                                    if ($count % 6 !== 0) {
                                                        echo '</tbody></table>';
                                                        echo '<div class="text-center">';
                                                        echo '<button type="button" class="btn btn-primary generate-qr-btn">Gerar QR Code</button>';
                                                        echo '</div>';
                                                        echo '<div class="qr-code-container"></div>';
                                                    }
                                                    ?>
                                                <?php } else { ?>
                                                    <p>Nenhum registro de histórico encontrado.</p>
                                                <?php } ?>
                                            </div>
                                            <div class="modal-footer"
                                                 style="display: flex; justify-content: space-between;">
                                                <button type="button" class="btn btn-danger"
                                                        onclick="showConfirmationAlert()"
                                                        data-id="<?php echo $dado['id']; ?>">Limpar
                                                </button>
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                    Fechar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            <?php } else { ?>
                <p>Nenhum dado encontrado para exibir.</p>
            <?php } ?>
            <div class="d-flex justify-content-center align-items-center" style="margin-top: 25px">
                <a href="./principaladministrador.php" class="btn btn-primary">Voltar à lista de funcionários</a>
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

        // Click event for "Sim" and "Não" buttons
        $('.btn-success, .btn-danger').click(function () {
            clickCount++;
            if (clickCount % 6 === 0) {
                createNewWeekTable();
            }

            // Calculate and update the total frequency value for the current week
            var totalFrequency = calculateTotalFrequency();
            updateTotalFrequency(totalFrequency);
        });
    });

    // Function to create a new week table
    function createNewWeekTable() {
        var tableHTML = `
            <h5>Week ${Math.ceil(clickCount / 6)}</h5>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Frequência</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
            <div class="text-center">
                <button type="button" class="btn btn-primary generate-qr-btn">Gerar QR Code</button>
            </div>
            <div class="qr-code-container"></div>
        `;
        $('#mytable').append(tableHTML);
    }

    // Function to calculate the total frequency for the current week
    function calculateTotalFrequency() {
        var totalFrequency = 0;
        $('.btn-success').each(function () {
            totalFrequency += 60;
        });
        return totalFrequency;
    }

    // Function to update the total frequency value in the current week table
    function updateTotalFrequency(totalFrequency) {
        var currentWeek = Math.ceil(clickCount / 6);
        var totalFrequencyElement = $(`#mytable h5:contains('Week ${currentWeek}')`).next().next().next().next();
        totalFrequencyElement.text(`Total Frequency: ${totalFrequency}`);
    }

    // QR code generation event
    $(document).on('click', '.generate-qr-btn', function () {
        var weekNumber = $(this).closest('table').prev().text().replace('Week ', '');
        var chavepix = "<?php echo $dado['chavepix']; ?>";
        var qrCodeElement = $(this).closest('.text-center').next();

        var qrCodeHTML = `<img src="https://api.qrserver.com/v1/create-qr-code/?data=${chavepix}&size=150x150" alt="QR Code - Week ${weekNumber}">`;
        qrCodeElement.html(qrCodeHTML);
    });
</script>

<script>
    // Function to show the confirmation dialog for historical data deletion
    function showConfirmationAlert() {
        var idUsuario = $(event.target).data('id');
        if (confirm("Você tem certeza que quer limpar todo o histórico do usuário? Os dados não poderão ser recuperados.")) {
            // Redirect to the deletarFrequencia.php script for deletion
            window.location.href = "deletarFrequencia.php?id=" + idUsuario;
        } else {
            alert("Dados não Deletados!");
        }
    }
</script>

</body>
</html>
