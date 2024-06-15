<head>
<link rel="stylesheet" href="../style.css">
</head>
<div>

    <?php
require_once "../functions/functions.php";
$selectedCurrency = empty($_GET['currency']) ? 'USD-BRL' : $_GET['currency'];
$apiUrl = "https://economia.awesomeapi.com.br/json/daily/$selectedCurrency/1000";
renderTableFromApi($apiUrl, $selectedCurrency, '', 'Não há dados para exibir');
?>
</div>