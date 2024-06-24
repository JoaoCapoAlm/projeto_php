<div>
    <?php
    require_once "../functions/functions.php";
    $selectedCurrency = empty($_GET['currency']) ? 'USD-BRL' : $_GET['currency'];
    $selectedPeriodo = empty($_GET['periodo']) ? '60' : $_GET['periodo'];
    $apiUrl = "https://economia.awesomeapi.com.br/json/daily/$selectedCurrency/$selectedPeriodo";
    renderTableFromApi($apiUrl, $selectedCurrency, '', 'Não há dados para exibir');
    ?>
</div>
