<?php
function renderTableFromApi($apiUrl, $selectedCurrency, $tableClass, $msg, $pageSize = 20, $pag = 'page'): void
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

    /*
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
    */
}

function generateChartFromApi($apiUrl, $chartId = 'chart', $chartType = 'line', $chartTitle = 'Gráfico', $xAxisLabel = 'Data', $yAxisLabel = 'Valor', $pageSize = 20): void
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
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
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