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
$usd = obterSaldoMoeda($userId, 'USD');
$eur = obterSaldoMoeda($userId, 'EUR');
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
        <div class="chartForm" >
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
                    <option value=""><?php echo $_SESSION['grafico']?></option>
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
        <h3>Meu Perfil</h3>
        <p>Saldo Atual em R$: <?php echo number_format($saldo, 2, ',', '.'); ?></p>
        <p>Saldo Atual em USD: <?php echo number_format($usd, 2, ',', '.'); ?></p>
        <p>Saldo Atual em EUR: <?php echo number_format($eur, 2, ',', '.'); ?></p>
        <p>Saldo Total em R$: <?php echo number_format($saldoTotalEmReais, 2, ',', '.'); ?></p>

        <a href="perfil.php" class="btn-perfil">Acessar Meu Perfil</a>
    </div>
</div>


<div class="container" style="height: 800px;">
    <iframe src="./parts/table.php?currency=<?php echo $selectedCurrency ?>&periodo=<?php echo $selectedPeriodo ?>&" frameborder="0" style="width: 100%; height:100%;"></iframe>
</div>

<?php include_once './parts/footer.php'; ?>