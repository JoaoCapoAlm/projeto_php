<?php
$paginaApenasLogado = true;
include_once './parts/header.php';
include_once './functions/functions.php';

if (!$_SESSION['user_id']) {
    header('Location: ./index.php');
    exit();
}




if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['operacao'])) {
        if ($_POST['operacao'] === 'comprar') {
            $_SESSION['operação'] = realizarOperacao('comprar', $_POST['moeda'], $_POST['quantidade'], $usuario->saldo);
        } elseif ($_POST['operacao'] === 'vender') {
            $_SESSION['operação'] = realizarOperacao('vender', $_POST['moeda'], $_POST['quantidade'], $usuario->saldo);
        }
        header('Location: perfil.php');
        exit();
    } elseif (isset($_POST['deposito'])) {
        $_SESSION['operação'] = realizarDeposito('R$',$_POST['quantidade'], $userId);
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
    <?php echo ($usuario->saldo > 0) ? '<p>Saldo R$: ' . number_format($usuario->saldo, 2, ',', '.') . '</p>' : ''; ?>
    <?php echo ($usuario->USD > 0) ? '<p>Saldo USD: ' . number_format($usuario->USD, 2, ',', '.') . '</p>' : ''; ?>
    <?php echo ($usuario->EUR > 0) ? '<p>Saldo EUR: ' . number_format($usuario->EUR, 2, ',', '.') . '</p>' : ''; ?>
    <?php echo ($usuario->GBP > 0) ? '<p>Saldo GBP: ' . number_format($usuario->GBP, 2, ',', '.') . '</p>' : ''; ?>
    <?php echo ($usuario->JPY > 0) ? '<p>Saldo JPY: ' . number_format($usuario->JPY, 2, ',', '.') . '</p>' : ''; ?>
    <?php echo ($usuario->AUD > 0) ? '<p>Saldo AUD: ' . number_format($usuario->AUD, 2, ',', '.') . '</p>' : ''; ?>
    <?php echo ($usuario->CAD > 0) ? '<p>Saldo CAD: ' . number_format($usuario->CAD, 2, ',', '.') . '</p>' : ''; ?>
</div>


    <div class="profile-form">
        <form method="post">
            <label for="moeda">Moeda:</label>
            <select name="moeda" id="moeda">
            <option value="R$">R$</option>
                <?php 
                foreach ($currencies as $currency) {
                    
                    $parts = explode('-', $currency);
                    $primeira_moeda = $parts[0];
                    echo "<option value='$primeira_moeda'>$primeira_moeda</option>";
                }
                ?>
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
