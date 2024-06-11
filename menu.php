<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Trabalho</title>

    <link rel="stylesheet" href="./style.css">
</head>
<body>
<header>
    <h1>Trabalho Java</h1>
    <nav>
        <ul>
            <?php
            $currencies = ["USD-BRL", "EUR-BRL", "BTC-BRL"];
            foreach ($currencies as $currency) {
                echo "<li><a href='?currency=$currency'>$currency</a></li>";
            }
            ?>
        </ul>
    </nav>
</header>
<main>
    <div class="container">
        <?php
        include "banco.php";
        include 'apiMoedas.php';

        $selectedCurrency = isset($_GET['currency']) ? $_GET['currency'] : 'USD-BRL';
        $apiUrl = "https://economia.awesomeapi.com.br/json/daily/$selectedCurrency/1000";
        generateChartFromApi($apiUrl, 'myChart', 'line', 'Variação do Dólar Americano', 'Data', 'Valor em Reais');
        ?>
    </div>
    <div class="container">
        <?php
        renderTableFromApi($apiUrl, $selectedCurrency, '', 'Não há dados para exibir');
        ?>
    </div>
</main>
<footer>
    <p>© <?php echo date("Y"); ?> Levoratech. Todos os direitos reservados.</p>
</footer>
</body>
</html>
