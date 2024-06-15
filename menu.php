<?php


$paginaApenasLogado = true;
include_once './parts/header.php';
if (!isset($_SESSION['periodo'])) {
    $_SESSION['periodo'] = '7';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['periodo'])) {
        $_SESSION['periodo'] = $_POST['periodo'];
    }
}$selectedCurrency = empty($_GET['currency']) ? 'USD-BRL' : $_GET['currency'];

$selectedPeriodo = $_SESSION['periodo'];
if(!$_SESSION['user_id']){
    header('Location: ./index.php');
    exit();
}
?>
    <div class="container">
        <form method="post">
            <select name="periodo" id="">
                <option value=""><?php echo $_SESSION['periodo']?> dias</option>
                <option value="7">7 dias</option>
                <option value="15">15 dias</option>
                <option value="30">30 dias</option>
                <option value="90">90 dias</option>
                <option value="180">180 dias</option>
                <option value="365">365 dias</option>
            </select>
            <button type="submit">Aplicar</button>
        </form>
        <?php

        
        
        $apiUrl = "https://economia.awesomeapi.com.br/json/daily/$selectedCurrency/$selectedPeriodo";
        generateChartFromApi($apiUrl, 'myChart', 'line',$selectedCurrency, 'Data', 'Valor em Reais');
        ?>
    </div>
    <div class="container" style="height: 600px; padding:10px;">
        <iframe src="./parts/table.php?periodo=<?php echo $selectedPeriodo ?>?currency=<?php echo $selectedCurrency?>" frameborder="0" style="width: 100%; height:100%;"></iframe>
    </div>

<?php include_once './parts/footer.php'; ?>