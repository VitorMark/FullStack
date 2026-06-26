<?php

include("conexao.php");

$totalProdutos = $conexao->query(
    "SELECT COUNT(*) as total FROM produtos"
)->fetch_assoc();

$vendasHoje = $conexao->query(
    "SELECT COUNT(*) as total FROM vendas WHERE DATE(data_venda)=CURDATE()"
)->fetch_assoc();

$vendasMes = $conexao->query(
    "SELECT COUNT(*) as total FROM vendas
     WHERE MONTH(data_venda)=MONTH(CURDATE())
     AND YEAR(data_venda)=YEAR(CURDATE())"
)->fetch_assoc();

$faturamento = $conexao->query(
    "SELECT SUM(total) as total FROM vendas"
)->fetch_assoc();

$produtos = $conexao->query("SELECT * FROM produtos");
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Painel — FIO BOM</title>
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
            <a href="sobre.php" class="nav-link-custom">Sobre</a>
            <a href="ods.php" class="nav-link-custom">ODS 12</a>
            <a href="dashboard.php" class="nav-link-custom active">Painel</a>
            <a href="contato.php" class="nav-link-custom">Fale Conosco</a>
        </div>
    </div>
</nav>

<!-- CABEÇALHO -->
<div class="cabecalho-pagina">
    <div class="container text-center">
        <h1>Painel de <span>Controle</span></h1>
        <p>Vendas e produtos cadastrados.</p>
    </div>
</div>

<section class="section">
    <div class="container">

        <!-- MÉTRICAS -->
        <div class="metricas-grid mb-5">

            <div class="metrica-card">
                <span class="metrica-label">Produtos</span>
                <span class="metrica-valor"><?= $totalProdutos['total'] ?></span>
            </div>

            <div class="metrica-card">
                <span class="metrica-label">Vendas hoje</span>
                <span class="metrica-valor"><?= $vendasHoje['total'] ?></span>
            </div>

            <div class="metrica-card">
                <span class="metrica-label">Vendas no mês</span>
                <span class="metrica-valor"><?= $vendasMes['total'] ?></span>
            </div>

            <div class="metrica-card">
                <span class="metrica-label">Faturamento total</span>
                <span class="metrica-valor metrica-valor--menor">
                    R$ <?= number_format($faturamento['total'] ?? 0, 2, ',', '.') ?>
                </span>
            </div>
        </div>

        <!-- AÇÕES -->
        <div class="acoes-painel mb-5">
            <a href="cadastrar.php" class="btn-fiobom">Novo produto</a>
            <a href="devolver.php" class="btn-fiobom-outline-dark">Devoluções</a>
            <a href="relatorio_diario.php" class="btn-fiobom-outline-dark">Relatório diário</a>
            <a href="relatorio_mensal.php" class="btn-fiobom-outline-dark">Relatório mensal</a>
            <a href="relatorio_anual.php" class="btn-fiobom-outline-dark">Relatório anual</a>
        </div>

        <!-- TABELA -->
        <div class="tabela-wrapper">

            <div class="tabela-topo">
                <h3 class="tabela-titulo">Produtos cadastrados</h3>
            </div>

            <div class="table-responsive">
                <table class="tabela-custom">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nome</th>
                            <th>Preço</th>
                            <th>Categoria</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($produto = $produtos->fetch_assoc()): ?>
                        <tr>
                            <td class="tabela-id"><?= $produto['id'] ?></td>
                            <td><strong><?= $produto['nome'] ?></strong></td>
                            <td>R$ <?= number_format($produto['preco'], 2, ',', '.') ?></td>
                            <td><span class="badge-categoria"><?= $produto['categoria'] ?></span></td>
                            <td class="tabela-acoes">
                                <a href="editar.php?id=<?= $produto['id'] ?>" class="btn-tabela btn-tabela--editar">Editar</a>
                                <a href="excluir.php?id=<?= $produto['id'] ?>" class="btn-tabela btn-tabela--excluir"
                                   onclick="return confirm('Excluir este produto?')">Excluir</a>
                            </td>
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
