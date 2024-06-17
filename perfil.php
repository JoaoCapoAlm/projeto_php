<?php
$paginaApenasLogado = true;
include_once './parts/header.php';
include_once './functions/functions.php';

if (!$_SESSION['user_id']) {
    header('Location: ./index.php');
    exit();
}

$userId = $_SESSION['user_id'];
$saldo = obterSaldo($userId);
$usd = obterSaldoMoeda($userId, 'USD');
$eur = obterSaldoMoeda($userId, 'EUR');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['operacao'])) {
        if ($_POST['operacao'] === 'comprar') {
            $_SESSION['operação'] = realizarOperacao('comprar', $_POST['moeda'], $_POST['quantidade'], $saldo);
        } elseif ($_POST['operacao'] === 'vender') {
            $_SESSION['operação'] = realizarOperacao('vender', $_POST['moeda'], $_POST['quantidade'], $saldo);
        }
        header('Location: perfil.php');
        exit();
    } elseif (isset($_POST['deposito'])) {
        realizarDeposito($_POST['moeda'], $_POST['quantidade'], $userId);
        header('Location: perfil.php');
        exit();
    }
}
?>

<div class="container">
    <div class="profile-header">
        <h2>Meu Perfil</h2>
        <p><?php echo $_SESSION['operação'] ?? ''; 
            $_SESSION['operação'] = '';
        ?></p><br>
    </div>
    <div class="profile-balance">
        <p>Saldo Atual em R$: <?php echo number_format($saldo, 2, ',', '.'); ?></p>
        <p>Saldo Atual em USD: <?php echo number_format($usd, 2, ',', '.'); ?></p>
        <p>Saldo Atual em EUR: <?php echo number_format($eur, 2, ',', '.'); ?></p>
    </div>

    <div class="profile-form">
        <form method="post">
            <label for="moeda">Moeda:</label>
            <select name="moeda" id="moeda">
                <option value="USD">USD</option>
                <option value="EUR">EUR</option>
                <option value="R$">R$</option>
            </select>
            <label for="quantidade">Quantidade:</label>
            <input type="number" name="quantidade" id="quantidade" step="0.01" required>
            <button type="submit" name="deposito">Depositar</button>
            <button type="submit" name="operacao" value="comprar" style="background-color: #28a745;">Comprar</button>
            <button type="submit" name="operacao" value="vender" style=" background-color: #f00;">Vender</button>
        </form>
    </div>

    <a href="menu.php" class="btn-back">Voltar ao Menu</a>
</div>

<?php include_once './parts/footer.php'; ?>
