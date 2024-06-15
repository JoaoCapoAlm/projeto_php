<?php
$paginaApenasLogado = true;
include_once './parts/header.php';
?>
    <div class="container">
        <?php

        $selectedCurrency = empty($_GET['currency']) ? 'USD-BRL' : $_GET['currency'];
        $apiUrl = "https://economia.awesomeapi.com.br/json/daily/$selectedCurrency/1000";
        generateChartFromApi($apiUrl, 'myChart', 'line',$selectedCurrency, 'Data', 'Valor em Reais');
        ?>
    </div>
    <div class="container" style="height: 600px; padding:10px;">
        <iframe src="./parts/table.php?currency=<?php echo $selectedCurrency?>" frameborder="0" style="width: 100%; height:100%;"></iframe>
    </div>

<?php include_once './parts/footer.php'; ?>