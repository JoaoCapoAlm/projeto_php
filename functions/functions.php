<?php
function obterSaldo($userId)
{
    global $banco;
    $stmt = $banco->prepare("SELECT saldo FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($saldo);
    $stmt->fetch();
    $stmt->close();
    return $saldo;
}

function obterSaldoMoeda($userId, $moeda)
{
    global $banco;
    $stmt = $banco->prepare("SELECT $moeda FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($saldo);
    $stmt->fetch();
    $stmt->close();
    return $saldo;
}

function realizarOperacao($operacao, $moeda, $quantidade, $saldo)
{
    if ($moeda == 'R$') {
        return 'invalido';
    }
    global $banco, $userId;
    $cotacao = obterCotacao($moeda);
    if ($quantidade * $cotacao > $saldo) {
        return "Saldo insulficiente";
    }

    if ($operacao === 'comprar') {
        $valorOperacao = $quantidade * $cotacao;
        $stmt = $banco->prepare("UPDATE usuarios SET saldo = saldo - ?, $moeda = $moeda + ? WHERE id = ?");
        $stmt->bind_param("ddi", $valorOperacao, $quantidade, $userId);
        $stmt->execute();
        $stmt->close();
        obterUsuario($userId);
        return "Comprado $quantidade $moeda Total de R$". number_format($valorOperacao ,2,',');
    } elseif ($operacao === 'vender') {
        $valorOperacao = $quantidade * $cotacao;
        $stmt = $banco->prepare("UPDATE usuarios SET saldo = saldo + ?, $moeda = $moeda - ? WHERE id = ?");
        $stmt->bind_param("ddi", $valorOperacao, $quantidade, $userId);
        $stmt->execute();
        $stmt->close();
        obterUsuario($userId);
        return "Vendido $quantidade $moeda Total de R$". number_format($valorOperacao ,2,',');

    }
    return "Efetuado";
}

function obterCotacao($moeda)
{
    $apiUrl = "https://economia.awesomeapi.com.br/json/last/$moeda-BRL";
    $json = file_get_contents($apiUrl);
    $data = json_decode($json, true);
    return $data["$moeda" . "BRL"]['bid'];
}

function obterUsuario($userId)
{
    global $banco;

    $query = "SELECT id, login, nome, cpf, senha, saldo, USD, EUR, GBP, JPY, AUD, CAD FROM usuarios WHERE id = ?";
    $stmt = $banco->prepare($query);
    if ($stmt === false) {
        throw new Exception("Erro ao preparar a consulta: " . $banco->error);
    }

    $stmt->bind_param("i", $userId);
    $stmt->execute();

    $stmt->bind_result($id, $login, $nome, $cpf, $senha, $saldo, $USD, $EUR,  $GBP, $JPY, $AUD, $CAD);
    $stmt->fetch();

    $usuario = new Usuario($id, $login, $nome, $cpf, $senha, $saldo, $USD, $EUR, $GBP, $JPY, $AUD, $CAD);

    $stmt->close();

    return $usuario;
}


function realizarDeposito($moeda, $quantidade, $userId)
{
    global $banco;
    if($quantidade < 0){
        return 'invalido numeros menores que 0';
    }
    if ($moeda === 'R$') {
        $stmt = $banco->prepare("UPDATE usuarios SET saldo = saldo + ? WHERE id = ?");
        $stmt->bind_param("di", $quantidade, $userId);
        
    } else {
        $stmt = $banco->prepare("UPDATE usuarios SET $moeda = $moeda + ? WHERE id = ?");
        $stmt->bind_param("di", $quantidade, $userId);
    }

    $stmt->execute();
    $stmt->close();
    return "depósito realizado de R$ $quantidade ";
}

function obterSaldoTotalEmReais($userId)
{
    global $banco;

    $stmt = $banco->prepare("SELECT saldo, USD, EUR FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($saldoReais, $saldoUSD, $saldoEUR);
    $stmt->fetch();
    $stmt->close();

    $cotacaoUSD = obterCotacao('USD');
    $cotacaoEUR = obterCotacao('EUR');

    $saldoTotalEmReais = $saldoReais + ($saldoUSD * $cotacaoUSD) + ($saldoEUR * $cotacaoEUR);

    return $saldoTotalEmReais;
}
function renderTableFromApi($apiUrl, $selectedCurrency, $tableClass, $msg, $pageSize = 14, $pag = 'page'): void
{
    $page = isset($_GET[$pag]) ? max(1, intval($_GET[$pag])) : 1;

    $json = file_get_contents($apiUrl);
    $data = json_decode($json, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        echo "<p>Erro ao decodificar o JSON</p>";
        return;
    }

    if (empty($data)) {
        echo "<p>$msg</p>";
        return;
    }

    $totalRows = count($data);
    $totalPages = ceil($totalRows / $pageSize);

    $start = ($page - 1) * $pageSize;
    $data = array_slice($data, $start, $pageSize);

    $classAttribute = ($tableClass !== '') ? "class='$tableClass'" : '';

    $currencyName = $selectedCurrency;

    echo "<h1>Tabela de Dados para $currencyName</h1>";

    echo "<div class='explanation'>";
    echo "<p><strong>Explicação:</strong> 1 Euro vale X Reais para compra e Y Reais para venda no mercado de câmbio.</p>";
    echo "</div>";

    echo "<table $classAttribute><tr>";

    $headers = [
        'high' => 'Alta (R$)',
        'low' => 'Baixa (R$)',
        'varBid' => 'Variação (R$)',
        'pctChange' => 'Mudança (%)',
        'bid' => 'Compra (R$)',
        'ask' => 'Venda (R$)',
        'timestamp' => 'Data'
    ];

    foreach ($headers as $header) {
        echo "<th>$header</th>";
    }
    echo "</tr>";

    foreach ($data as $row) {
        echo "<tr>";
        foreach ($headers as $key => $header) {
            $value = isset($row[$key]) ? $row[$key] : '';
            if ($key == 'timestamp' || $key == 'create_date') {
                $value = !empty($value) ? date('d/m/Y', $value) : '';
            } elseif (is_numeric($value) && $key != 'pctChange') {
                $value = number_format($value, 2, ',', '.');
            } elseif ($key == 'pctChange') {
                $value = number_format($value, 2, ',', '.') . '%';
            }
            echo "<td>$value</td>";
        }
        echo "</tr>";
    }
    echo "</table>";


    echo "<div class='paginas'>";
    if ($page > 1) {
        $prevPage = $page - 1;
        echo "<a href='" . $_SERVER['PHP_SELF'] . "?currency=" . $_GET['currency'] . "&$pag=$prevPage'>Anterior</a>";
    }
    echo " $page ";
    if ($page < $totalPages) {
        $nextPage = $page + 1;
        echo "<a href='" . $_SERVER['PHP_SELF'] . "?currency=" . ($_GET['currency'] ?? 1) . "&$pag=$nextPage'>Próximo</a>";
    }
    echo "</div>";
}

function generateChartFromApi($apiUrl, $chartId = 'chart', $chartType = 'bar', $chartTitle = 'Gráfico', $xAxisLabel = 'Data', $yAxisLabel = 'Valor', $pageSize = 365): void
{
    $json = file_get_contents($apiUrl);
    $data = json_decode($json, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        echo "<p>Erro ao decodificar o JSON</p>";
        return;
    }

    if (empty($data)) {
        echo "<p>Não há dados para exibir</p>";
        return;
    }

    $labels = [];
    $values = [];

    $data = array_slice($data, 0, $pageSize);

    foreach ($data as $row) {
        $labels[] = date('d/m/Y', $row['timestamp']);
        $values[] = $row['bid'];
    }

    $labels = json_encode($labels);
    $values = json_encode($values);
 ?>

    <canvas id="<?= $chartId ?>"></canvas>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('<?= $chartId ?>').getContext('2d');
        const myChart = new Chart(ctx, {
            type: '<?= $chartType ?>',
            data: {
                labels: <?= $labels ?>,
                datasets: [{
                    label: '<?= $yAxisLabel ?>',
                    data: <?= $values ?>,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: '<?= $xAxisLabel ?>'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: '<?= $yAxisLabel ?>'
                        }
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: '<?= $chartTitle ?>'
                    }
                }
            }
        });
    </script>
 <?php
}


function renderPieChart($pie) {
    // Verifica se os dados são suficientes para gerar o gráfico
    if (!isset($pie['saldo']) || count($pie) < 2) {
        echo "Dados insuficientes para gerar o gráfico.";
        return;
    }

    $title = $pie['saldo'];
    
    // Prepare os dados para o gráfico
    $data = [];
    $currencyColors = [];
    foreach ($pie as $currency => $info) {
        if ($currency != 'saldo') {
            $data[] = [$currency, $info[0]];
            $currencyColors[$currency] = $info[1];
        }
    }

    ?>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {
            'packages': ['corechart']
        });
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['Moeda', 'Saldo'],
                <?php
                foreach ($data as $item) {
                    echo "['{$item[0]}', {$item[1]}],";
                }
                ?>
            ]);

            var currencyColors = <?php echo json_encode($currencyColors); ?>;

            var options = {
                title: '<?php echo $title; ?>',
                is3D: true,
                colors: (function() {
                    var colorsArray = [];
                    for (var i = 0; i < data.getNumberOfRows(); i++) {
                        var currency = data.getValue(i, 0);
                        colorsArray.push(currencyColors[currency] || '#ccc'); // Default gray for unknown currencies
                    }
                    return colorsArray;
                })()
            };

            var chart = new google.visualization.PieChart(document.getElementById('piechart'));
            chart.draw(data, options);
        }
    </script>
    <div id="piechart" style="width: 100%; height: 500px;"></div>
    <?php 
}
?>

<?php
function renderBarChart($bars) {
    // Verifica se os dados são suficientes para gerar o gráfico
    if (!isset($bars['titulo']) || !isset($bars['data']) || count($bars['data']) === 0) {
        echo "Dados insuficientes para gerar o gráfico de barras.";
        return;
    }

    $title = $bars['titulo'];
    
    // Prepara os dados para o gráfico de barras
    $data = [['Element', 'Quantidade']];
    foreach ($bars['data'] as $elemento => $quantidade) {
        $data[] = [$elemento, $quantidade];
    }

    ?>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {
            'packages': ['bar']
        });
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable(<?php echo json_encode($data); ?>);

            var options = {
                title: '<?php echo $title; ?>',
                is3D: true,
                colors: ['#3366cc'], // Cor das barras
                chartArea: {width: '50%'}, // Área do gráfico
                hAxis: {
                    title: 'Quantidade',
                    minValue: 0
                },
                vAxis: {
                    title: 'Elementos'
                }
            };

            var chart = new google.charts.Bar(document.getElementById('barchart_3d'));
            chart.draw(data, google.charts.Bar.convertOptions(options));
        }
    </script>
    <div id="barchart_3d" style="width: 100%; height: 500px;"></div>
    <?php 
}

// $bars = [
//     "titulo" => "Exemplo de Gráfico de Barras 3D",
//     "data" => [
//         "Elemento 1" => 10,
//         "Elemento 2" => 20,
//         "Elemento 3" => 15,
//         "Elemento 4" => 30
//     ]
// ];

// renderBarChart($bars);
?>

