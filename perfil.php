<?php
$paginaApenasLogado = true;
include_once './parts/header.php';
include_once './functions/functions.php'; // Adicione esta linha

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
        $_SESSION['operação'] = realizarOperacao($_POST['operacao'], $_POST['moeda'], $_POST['quantidade'],$saldo);
        header('Location: perfil.php');
        exit();
    }
    if (isset($_POST['deposito'])) {
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
            <input type="hidden" name="operacao" value="comprar">
            <label for="moeda">Moeda:</label>
            <select name="moeda" id="moeda">
                <option value="USD">USD</option>
                <option value="EUR">EUR</option>
            </select>
            <label for="quantidade">Quantidade:</label>
            <input type="number" name="quantidade" id="quantidade" step="0.01" required>
            <button type="submit">Comprar</button>
        </form>
    </div>

    <div class="profile-form">
        <form method="post">
            <input type="hidden" name="operacao" value="vender">
            <label for="moeda">Moeda:</label>
            <select name="moeda" id="moeda">
                <option value="USD">USD</option>
                <option value="EUR">EUR</option>
            </select>
            <label for="quantidade">Quantidade:</label>
            <input type="number" name="quantidade" id="quantidade" step="0.01" required>
            <button type="submit">Vender</button>
        </form>
    </div>

    <div class="profile-form">
        <form method="post">
            <input type="hidden" name="deposito" value="true">
            <label for="moeda">Moeda:</label>
            <select name="moeda" id="moeda">
                <option value="R$">R$</option>
                <option value="USD">USD</option>
                <option value="EUR">EUR</option>
            </select>
            <label for="quantidade">Quantidade:</label>
            <input type="number" name="quantidade" id="quantidade" step="0.01" required>
            <button type="submit">Depositar</button>
        </form>
    </div>

    <a href="menu.php" class="btn-back">Voltar ao Menu</a>
</div>

<?php include_once './parts/footer.php'; ?>
