<?php
    $servername = 'localhost:3306';
    $username = 'root';
    $password = '';
    $dbname = 'esp32';

    $conn = new mysqli($servername, $username, $password, $dbname);

    $temp = isset($_POST['temperature']) ? $_POST['temperature'] : null;
    $umid = isset($_POST['umidity']) ? $_POST['umidity'] : null;

    if ($temp != null && $umid != null) {
        $sql = "INSERT INTO dht_data (temperatura, umidade) VALUES ('$temp', '$umid')";


    $conn->query($sql);
    }

    $sql = "SELECT * FROM dht_data ORDER BY id DESC LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $temp = $row['temperatura'];
        $umid = $row['umidade'];

        $sql = "SELECT temperatura, umidade, data_hora 
                FROM dht_data
                WHERE temperatura = (SELECT min(temperatura) as temperatura
                                     FROM dht_data
                                     WHERE temperatura <> 0)
                                     LIMIT 1";

        $resultMenor = $conn->query($sql);

        if ($resultMenor -> num_rows > 0) {
            $row = $resultMenor-> fetch_assoc();
            $menorTemp = $row["temperatura"];
            $menorDataHora = date('d/m/Y H:i:s', strtotime($row["data_hora"]));

        } else {
            $menorTemp = "--";
            $menorDataHora = "--";
        }

        $sql = "SELECT temperatura, data_hora
                FROM dht_data
                WHERE temperatura = (SELECT max(temperatura) as temperatura
                                     FROM dht_data
                                     WHERE temperatura <> 0)
                                     LIMIT 1";
        $resultMaior = $conn->query($sql);

        if ($resultMaior -> num_rows > 0) {
            $row = $resultMaior->fetch_assoc();
            $maiorTemp = $row["temperatura"];
            $maiorDataHora = date('d/m/Y H:i:s', strtotime($row["data_hora"]));
        } else {
            $maiorTemp = "--";
            $maiorDataHora = "--";
        }

        $sql = "SELECT temperatura, data_hora
                FROM dht_data
                WHERE temperatura = (SELECT max(temperatura) as temperatura
                                     FROM dht_data
                                     WHERE temperatura <> 0)
                                     LIMIT 1";
        $resultMaior = $conn->query($sql);

        if ($resultMaior->num_rows > 0) {
            $row = $resultMaior->fetch_assoc();
            $maiorTemp = $row["temperatura"];
            $maiorDataHora = date('d/m/Y H:i:s', strtotime($row["data_hora"]));
        } else {
            $maiorTemp = "--";
            $maiorDataHora = "--";
        }

        $sql = "SELECT umidade, data_hora
                FROM dht_data
                WHERE umidade = (SELECT min(umidade) as umidade
                                 FROM dht_data
                                 WHERE temperatura <> 0)
                                 LIMIT 1";
        $resultMenorUmidade = $conn->query($sql);

        if ($resultMenorUmidade -> num_rows > 0) {
            $row = $resultMenorUmidade->fetch_assoc();
            $menorUmidade = $row["umidade"];
            $menorUmidadeDataHora = date('d/m/Y H:i:s', strtotime($row["data_hora"]));
        } else {
            $menorUmidade = "--";
            $menorUmidadeDataHora = "--";
        }

        $sql = "SELECT umidade, data_hora
                FROM dht_data
                WHERE umidade = (SELECT max(umidade) as umidade
                                 FROM dht_data
                                 WHERE temperatura <> 0)
                                 LIMIT 1";
        
        $resultMaiorUmidade = $conn->query($sql);

        if ($resultMaiorUmidade->num_rows > 0) {
            $row = $resultMaiorUmidade->fetch_assoc();
            $maiorUmidade = $row["umidade"];
            $maiorUmidadeDataHora = date('d/m/Y H:i:s', strtotime($row["data_hora"]));
        } else {
            $maiorUmidade = "--";
            $maiorUmidadeDataHora = "--";
        }


    } else {
        $temp = "--";
        $umid = "--";
        $menorTemp = "--";
        $menorDataHora = "--";
        $maiorTemp = "--";
        $maiorDataHora = "--";
        $menorUmidade = "--";
        $maiorUmidadeDataHora = "--";
        $maiorUmidade = "--";
        $menorUmidadeDataHora = "--";
        $maiorUmidadeDataHora = "--";
    }

    $conn->close();

    

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leitura do Sensor DHT11 - IoT - Redes de Computadores</title>
</head>
<style>
    body {
     background-image: url('./rain.jpg');
     background-repeat: no-repeat;
     background-size: cover;   
    }

    .container {
        position: absolute;
        top: 30%;  
        left: 32%; 
        border-radius: 20px;
        padding: 20px;
        font-family: 'Verdana', sans-serif;
        width: 500px;
        background-color: #ddd;
        
        align-items: center;
        justify-content: center;
    }

    .container h2, p {
        align-items: center;
        justify-content: center;
        text-align: center;
    }
</style>
<body>
    <div class="container">
        <h2>Leitura do Sensor DHT11</h2>
        <p>🌡 Temperatura: 
            <?php echo $temp; ?> °C
        </p>
        <p>💧 Umidade: 
            <?php echo $umid; ?> %
        </p>
        <p>📅 Data/Hora de verificação:
            <?php date_default_timezone_set('America/Sao_Paulo'); ?> -->
            <?php echo date('d/m/Y H:i:s'); ?>
        </p>
        <p>🥶 Menor Temperatura:
            <?php echo $menorTemp . " °C às " . $menorDataHora; ?>
        </p>
        <p>🥵 Maior Temperatura:
            <?php echo $maiorTemp . " °C às " . $maiorDataHora; ?>
        </p>
        <p>⬇💧 Menor umidade:
            <?php echo $menorUmidade . "% às " . $menorUmidadeDataHora; ?>
        </p>
        <p>⬆💧Maior umidade:
            <?php echo $maiorUmidade . "% às " . $maiorUmidadeDataHora; ?>
        </p>
    </div>
</body>
</html>