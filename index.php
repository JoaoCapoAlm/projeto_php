<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Trabalho</title>

    <link rel="stylesheet" href="./style.css">
</head>
<body>
<header>
    <h1>Trabalho Java</h1>
    <nav>
        <ul>
            <?php
            $currencies = ["USD-BRL", "EUR-BRL", "BTC-BRL"];
            foreach ($currencies as $currency) {
                echo "<li><a href='?currency=$currency'>$currency</a></li>";
            }
            ?>
        </ul>
    </nav>
</header>
</body>
</html>