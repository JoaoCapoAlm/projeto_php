<?php
$paginaApenasLogado = true;
include_once './parts/header.php';
include_once './functions/functions.php';

if (!isset($_SESSION['periodo'])) {
    $_SESSION['periodo'] = '7';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['periodo'])) {
        $_SESSION['periodo'] = $_POST['periodo'];
    }
    if (isset($_POST['grafico'])) {
        $_SESSION['grafico'] = $_POST['grafico'];
    }
}

if (!isset($_SESSION['grafico'])) {
    $_SESSION['grafico'] = 'line';
}

$selectedCurrency = empty($_GET['currency']) ? 'USD-BRL' : $_GET['currency'];
$selectedGrafico = $_SESSION['grafico'];
$selectedPeriodo = $_SESSION['periodo'];

if (!$_SESSION['user_id']) {
    header('Location: ./index.php');
    exit();
}

$userId = $_SESSION['user_id'];
$saldoTotalEmReais = obterSaldoTotalEmReais($userId);
$saldo = obterSaldo($userId);
?>
<nav>
    <?php if ($paginaApenasLogado) : ?>
        <ul class="currency-nav">
            <?php
            foreach ($currencies as $currency) {
                echo "<li><a style='color: black;' href='?currency=$currency'>$currency</a></li>";
            }
            ?>
        </ul>
    <?php endif; ?>
</nav>
<div class="container" id="Menu">
    <div class="chart">
        <div class="chartForm">
            <form method="post" class="form-periodo">
                <label for="">Periodo: </label>
                <select name="periodo" id="">
                    <option value=""><?php echo $_SESSION['periodo'] ?> dias</option>
                    <option value="7">7 dias</option>
                    <option value="15">15 dias</option>
                    <option value="30">30 dias</option>
                    <option value="90">90 dias</option>
                    <option value="180">180 dias</option>
                    <option value="365">365 dias</option>
                </select>
                <button type="submit">Aplicar</button>

            </form>
          
            <form method="post" class="form-grafico">
                <label for="">Tipo de grafico: </label>
                <select name="grafico" id="tipo_grafico">
                    <option value=""><?php echo $_SESSION['grafico'] ?></option>
                    <option value="line">line</option>
                    <option value="bar">bar</option>
                    <option value="radar">radar</option>
                </select>
                <button type="submit">Aplicar</button>
            </form>
        </div>


        <?php
        $apiUrl = "https://economia.awesomeapi.com.br/json/daily/$selectedCurrency/$selectedPeriodo";
        generateChartFromApi($apiUrl, 'myChart', $selectedGrafico, $selectedCurrency, 'Data', 'Valor em Reais');
        ?>
    </div>

    <div class="profile-summary">
        <h3>Meu Perfil - Saldo</h3>
        <div style="display: flex;width: 100%;">
            <div style="width: 30%;">
                <?php echo ($usuario->USD > 0) ? '<p> USD: ' . number_format($usuario->USD, 2, ',', '.') . '</p>' : ''; ?>
                <?php echo ($usuario->EUR > 0) ? '<p> EUR: ' . number_format($usuario->EUR, 2, ',', '.') . '</p>' : ''; ?>
                <?php echo ($usuario->GBP > 0) ? '<p> GBP: ' . number_format($usuario->GBP, 2, ',', '.') . '</p>' : ''; ?>
                <?php echo ($usuario->JPY > 0) ? '<p> JPY: ' . number_format($usuario->JPY, 2, ',', '.') . '</p>' : ''; ?>
                <?php echo ($usuario->AUD > 0) ? '<p> AUD: ' . number_format($usuario->AUD, 2, ',', '.') . '</p>' : ''; ?>
                <?php echo ($usuario->CAD > 0) ? '<p> CAD: ' . number_format($usuario->CAD, 2, ',', '.') . '</p>' : ''; ?>
                <?php echo ($usuario->saldo > 0) ? '<p> R$: ' . number_format($usuario->saldo, 2, ',', '.') . '</p>' : ''; ?>
            </div>
            <div style="width: 70%; ">
                <?php 
                $pie = [
                    "saldo" => "Finanças",
                    "EUR" => [$usuario->EUR, "yellow"],
                    "JPY" => [$usuario->GBP, "red"],
                    "USD" => [$usuario->USD, "red"],
                    "JPY" => [$usuario->JPY, "blue"],
                    "AUD" => [$usuario->AUD , "orange"],
                    "CAD" => [$usuario->CAD , "#C10020"],
                    "BRL" => [$usuario->saldo, "GREEN"],
                ];
                
                renderPieChart($pie);
                
               ?>
           
            </div>
        </div>



        <a href="perfil.php" class="btn-perfil">Acessar Meu Perfil</a>
    </div>
</div>


<div class="container" style="height: 800px;">
    <iframe src="./parts/table.php?currency=<?php echo $selectedCurrency ?>&periodo=<?php echo $selectedPeriodo ?>&" frameborder="0" style="width: 100%; height:100%;"></iframe>
</div>

<?php include_once './parts/footer.php'; ?>