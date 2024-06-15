<?php

$paginaApenasLogado = true;
include_once './parts/header.php';
$selectedPeriodo = empty($_POST['periodo']) ? '7' : $_POST['periodo'];
if(!$_SESSION['user_id']){
    header('Location: ./index.php');
    exit();
}
?>
    <div class="container">
        <form method="post">
            <select name="periodo" id="">
                <option value=""><?php echo $selectedPeriodo?> dias</option>
                <option value="7">semana</option>
                <option value="15">quinzeza</option>
                <option value="30">mes</option>
                <option value="90">trimestre</option>
                <option value="180">semestre</option>
                <option value="365">ano</option>
            </select>
            <button type="submit">Aplicar</button>
        </form>
        <?php

        $selectedCurrency = empty($_GET['currency']) ? 'USD-BRL' : $_GET['currency'];
        
        $apiUrl = "https://economia.awesomeapi.com.br/json/daily/$selectedCurrency/$selectedPeriodo";
        generateChartFromApi($apiUrl, 'myChart', 'line',$selectedCurrency, 'Data', 'Valor em Reais');
        ?>
    </div>
    <div class="container" style="height: 600px; padding:10px;">
        <iframe src="./parts/table.php?periodo=<?php echo $selectedPeriodo ?>?currency=<?php echo $selectedCurrency?>" frameborder="0" style="width: 100%; height:100%;"></iframe>
    </div>

<?php include_once './parts/footer.php'; ?>