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

// Verifica se o parâmetro "id" está presente na URL
if (isset($_GET['id'])) {
    $idUsuario = $_GET['id'];

    // Inclui o arquivo de conexão com o banco de dados
    include('../controller/conexaoDataBaseV2.php');

    // Comando SQL para deletar registros da tabela "historico" onde o id_usuario corresponde ao valor fornecido
    $sqlDeleteHistorico = "DELETE FROM historico WHERE id_usuario = $idUsuario";

    // Executa o comando SQL para deletar o histórico do usuário
    $queryDeleteHistorico = mysqli_query($conn, $sqlDeleteHistorico);

    // Verifica se o delete foi efetuado com sucesso
    if ($queryDeleteHistorico) {
        header("Location: ./frequenciaUsuario.php?id=$idUsuario");
        exit();
    } else {
        echo "Erro ao deletar dados do histórico.";
        exit();
    }
}
?>




// Verifica se o parâmetro "idUsuario" está presente no formulário
if (isset($_POST['idUsuario'])) {
$idUsuario = $_POST['idUsuario'];

// Query to get the latest week number and start date of the current week
$sqlLatestWeek = "SELECT MAX(weekNumber) AS latestWeekNumber, MAX(dataweek) AS latestStartDate FROM historico WHERE id_usuario = $idUsuario";
$queryLatestWeek = mysqli_query($conn, $sqlLatestWeek);
$latestWeekData = mysqli_fetch_assoc($queryLatestWeek);

// Calculate the new week number and start date
$currentWeekNumber = $latestWeekData['latestWeekNumber'] ?? 0;
$currentStartDate = $latestWeekData['latestStartDate'] ?? date('Y-m-d');

if (isset($_POST['compareceu'])) {
// Insere a frequência do usuário como "Sim"
$frequencia = 60; // 60 minutes for "Sim"
} elseif (isset($_POST['naoCompareceu'])) {
// Insere a frequência do usuário como "Não"
$frequencia = 0; // 0 minutes for "Não"
}

// Check if a new week needs to be created
if ($currentWeekNumber == 0 || ($currentWeekNumber % 6) == 0) {
$currentWeekNumber++;
// Increment the start date to the next week's start date
$currentStartDate = date('Y-m-d', strtotime($currentStartDate . ' + 7 days'));
}

// Insere o registro de frequência na tabela "historico"
$sqlInsert = "INSERT INTO historico (id_usuario, data, frequencia, weekNumber, dataweek) VALUES ('$idUsuario', CURDATE(), $frequencia, $currentWeekNumber, '$currentStartDate')";
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
