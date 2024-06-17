<?php
require_once './functions/session.php';
require_once './functions/banco.php';
require_once './functions/functions.php';
@session_start();
$paginaApenasLogado = $paginaApenasLogado ?? false;
?>

<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Trabalho</title>

    <link rel="stylesheet" href="./style.css">
</head>
<body>
<header>
    <h1>Trabalho PHP</h1>
    <nav>
        <ul class="main-nav">
            <?php if(estaLogado()): ?>
                <li><a href="./logout.php" class="logout-btn">Sair</a></li>
                <li><a href="menu.php" class="btn-back">Home</a></li>
                <li><a href="perfil.php" class="btn-perfil">Acessar Meu Perfil</a></li>
            <?php endif; ?>
        </ul>
        <?php if($paginaApenasLogado): ?>
            <ul class="currency-nav">
                <?php
                $currencies = ["USD-BRL", "EUR-BRL", "BTC-BRL", "GBP-BRL", "JPY-BRL", "AUD-BRL", "CAD-BRL"];
                foreach ($currencies as $currency) {
                    echo "<li><a href='?currency=$currency'>$currency</a></li>";
                }
                ?>
            </ul>
        <?php endif; ?>
    </nav>
</header>
<main>