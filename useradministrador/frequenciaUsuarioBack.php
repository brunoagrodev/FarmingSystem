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

    // Query to get the latest week number and start date of the current week
    $sqlLatestWeek = "SELECT MAX(weekNumber) AS latestWeekNumber, MAX(dataweek) AS latestStartDate FROM historico WHERE id_usuario = $idUsuario";
    $queryLatestWeek = mysqli_query($conn, $sqlLatestWeek);
    $latestWeekData = mysqli_fetch_assoc($queryLatestWeek);

    // Calculate the new week number and start date
    $currentWeekNumber = $latestWeekData['latestWeekNumber'] ?? 0;
    $currentStartDate = $latestWeekData['latestStartDate'] ?? date('Y-m-d');

    if (isset($_POST['compareceu'])) {
        // Insere a frequência do usuário como "Sim" (store the string value "Sim")
        $frequencia = 'Sim';
        // Set the value of "frequencia_value" to "60" for "Sim"
        $frequencia_value = '60';
    } elseif (isset($_POST['naoCompareceu'])) {
        // Insere a frequência do usuário como "Não" (store the string value "Não")
        $frequencia = 'Não';
        // Set the value of "frequencia_value" to "0" for "Não"
        $frequencia_value = '0';
    }

    // Check if a new week needs to be created
    if ($currentWeekNumber == 0 || ($currentWeekNumber % 6) == 0) {
        $currentWeekNumber++;
        // Increment the start date to the next week's start date
        $currentStartDate = date('Y-m-d', strtotime($currentStartDate . ' + 7 days'));
    }

    // Insere o registro de frequência na tabela "historico"
    $sqlInsert = "INSERT INTO historico (id_usuario, data, frequencia, frequencia_value, weekNumber, dataweek) VALUES ('$idUsuario', CURDATE(), '$frequencia', '$frequencia_value', $currentWeekNumber, '$currentStartDate')";
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
