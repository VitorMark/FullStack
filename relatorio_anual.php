<?php

include("conexao.php");

// BUSCA VENDAS DO ANO AGRUPADAS POR MÊS, COM TOTAIS
$resumo_mensal = $conexao->query("
    SELECT
        MONTH(data_venda) AS mes,
        COUNT(*) AS qtd_vendas,
        SUM(quantidade) AS qtd_itens,
        SUM(total) AS faturamento,
        SUM(IFNULL(taxa_devolucao, 0)) AS total_taxas
    FROM vendas
    WHERE YEAR(data_venda) = YEAR(CURDATE())
    GROUP BY MONTH(data_venda)
    ORDER BY mes ASC
");

// BUSCA TODAS AS VENDAS DO ANO PARA A TABELA DETALHADA
$vendas = $conexao->query("
    SELECT vendas.*, produtos.nome,
           IFNULL(vendas.taxa_devolucao, 0) AS taxa_devolucao
    FROM vendas
    INNER JOIN produtos ON vendas.produto_id = produtos.id
    WHERE YEAR(data_venda) = YEAR(CURDATE())
    ORDER BY data_venda DESC
");

// TOTAIS GERAIS DO ANO
$totais = $conexao->query("
    SELECT
        COUNT(*) AS total_vendas,
        SUM(quantidade) AS total_itens,
        SUM(total) AS faturamento_bruto,
        SUM(IFNULL(taxa_devolucao, 0)) AS total_taxas
    FROM vendas
    WHERE YEAR(data_venda) = YEAR(CURDATE())
")->fetch_assoc();

$nomes_meses = [
    1=>'Janeiro', 2=>'Fevereiro', 3=>'Março', 4=>'Abril',
    5=>'Maio', 6=>'Junho', 7=>'Julho', 8=>'Agosto',
    9=>'Setembro', 10=>'Outubro', 11=>'Novembro', 12=>'Dezembro'
];
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Relatório Anual — FIO BOM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="index.php">FIO BOM</a>
        <div class="nav-links">
            <a href="index.php" class="nav-link-custom">Início</a>
            <a href="produtos.php" class="nav-link-custom">Loja</a>
            <a href="dashboard.php" class="nav-link-custom active">Painel</a>
            <a href="contato.php" class="nav-link-custom">Fale Conosco</a>
        </div>
    </div>
</nav>

<!-- CABEÇALHO -->
<div class="cabecalho-pagina">
    <div class="container text-center">
        <h1>Relatório <span>Anual</span></h1>
        <p>Tudo que foi vendido em <?= date('Y') ?>.</p>
    </div>
</div>

<section class="section">
    <div class="container">

        <a href="dashboard.php" class="link-voltar mb-5 d-inline-block">Voltar ao painel</a>

        <!-- MÉTRICAS DO ANO -->
        <div class="metricas-grid mb-5">

            <div class="metrica-card">
                <span class="metrica-label">Total de vendas</span>
                <span class="metrica-valor"><?= $totais['total_vendas'] ?? 0 ?></span>
            </div>

            <div class="metrica-card">
                <span class="metrica-label">Itens vendidos</span>
                <span class="metrica-valor"><?= $totais['total_itens'] ?? 0 ?></span>
            </div>

            <div class="metrica-card">
                <span class="metrica-label">Faturamento bruto</span>
                <span class="metrica-valor metrica-valor--menor">
                    R$ <?= number_format($totais['faturamento_bruto'] ?? 0, 2, ',', '.') ?>
                </span>
            </div>

            <div class="metrica-card">
                <span class="metrica-label">Taxas de devolução</span>
                <span class="metrica-valor metrica-valor--menor metrica-valor--alerta">
                    R$ <?= number_format($totais['total_taxas'] ?? 0, 2, ',', '.') ?>
                </span>
            </div>
        </div>

        <!-- RESUMO POR MÊS -->
        <div class="tabela-wrapper mb-5">

            <div class="tabela-topo">
                <h3 class="tabela-titulo">Resumo por mês</h3>
            </div>

            <div class="table-responsive">
                <table class="tabela-custom">
                    <thead>
                        <tr>
                            <th>Mês</th>
                            <th>Vendas</th>
                            <th>Itens</th>
                            <th>Faturamento</th>
                            <th>Taxas de devolução</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($linha = $resumo_mensal->fetch_assoc()): ?>
                        <tr>
                            <td><strong><?= $nomes_meses[$linha['mes']] ?></strong></td>
                            <td><?= $linha['qtd_vendas'] ?></td>
                            <td><?= $linha['qtd_itens'] ?> un.</td>
                            <td>R$ <?= number_format($linha['faturamento'], 2, ',', '.') ?></td>
                            <td>
                                <?php if($linha['total_taxas'] > 0): ?>
                                    <span class="badge-taxa">
                                        R$ <?= number_format($linha['total_taxas'], 2, ',', '.') ?>
                                    </span>
                                <?php else: ?>
                                    <span class="tabela-id">—</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- TODAS AS VENDAS DO ANO -->
        <div class="tabela-wrapper">

            <div class="tabela-topo">
                <h3 class="tabela-titulo">Todas as vendas de <?= date('Y') ?></h3>
            </div>

            <div class="table-responsive">
                <table class="tabela-custom">
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th>Quantidade</th>
                            <th>Total</th>
                            <th>Taxa devolução</th>
                            <th>Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($v = $vendas->fetch_assoc()): ?>
                        <tr>
                            <td><strong><?= $v['nome'] ?></strong></td>
                            <td><?= $v['quantidade'] ?> un.</td>
                            <td>R$ <?= number_format($v['total'], 2, ',', '.') ?></td>
                            <td>
                                <?php if($v['taxa_devolucao'] > 0): ?>
                                    <span class="badge-taxa">
                                        R$ <?= number_format($v['taxa_devolucao'], 2, ',', '.') ?>
                                    </span>
                                <?php else: ?>
                                    <span class="tabela-id">—</span>
                                <?php endif; ?>
                            </td>
                            <td><?= $v['data_venda'] ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer>
    <div class="footer-line"></div>
    <p><strong>FIO BOM</strong> &copy; 2026 &mdash; Tijuca, Rio de Janeiro.</p>
</footer>
</body>
</html>
