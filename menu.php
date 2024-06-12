<?php include_once './parts/header.php'; ?>
    <div class="container">
        <?php
        include_once './functions/apiMoedas.php';

        $selectedCurrency = empty($_GET['currency']) ? 'USD-BRL' : $_GET['currency'];
        $apiUrl = "https://economia.awesomeapi.com.br/json/daily/$selectedCurrency/1000";
        generateChartFromApi($apiUrl, 'myChart', 'line', 'Variação do Dólar Americano', 'Data', 'Valor em Reais');
        ?>
    </div>
    <div class="container">
        <?php
        renderTableFromApi($apiUrl, $selectedCurrency, '', 'Não há dados para exibir');
        ?>
    </div>

<?php include_once './parts/footer.php'; ?>