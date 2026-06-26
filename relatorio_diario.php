<?php

include("conexao.php");

$vendas = $conexao->query("
    SELECT vendas.*, produtos.nome
    FROM vendas
    INNER JOIN produtos ON vendas.produto_id = produtos.id
    WHERE DATE(data_venda) = CURDATE()
");

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Relatório Diário — FIO BOM</title>
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
        <h1>Relatório <span>Diário</span></h1>
        <p>Vendas de hoje, <?= date('d/m/Y') ?>.</p>
    </div>
</div>

<!-- TABELA -->
<section class="section">
    <div class="container">

        <a href="dashboard.php" class="link-voltar mb-4 d-inline-block">Voltar ao painel</a>

        <div class="tabela-wrapper">
            <div class="table-responsive">
                <table class="tabela-custom">
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th>Quantidade</th>
                            <th>Total</th>
                            <th>Horário</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($v = $vendas->fetch_assoc()): ?>
                        <tr>
                            <td><strong><?= $v['nome'] ?></strong></td>
                            <td><?= $v['quantidade'] ?> un.</td>
                            <td>R$ <?= number_format($v['total'], 2, ',', '.') ?></td>
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
