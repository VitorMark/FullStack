<?php include("conexao.php"); ?>
<?php $produtos = $conexao->query("SELECT * FROM produtos"); ?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Loja — FIO BOM</title>
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
            <a href="produtos.php" class="nav-link-custom active">Loja</a>
            <a href="sobre.php" class="nav-link-custom">Sobre</a>
            <a href="ods.php" class="nav-link-custom">ODS 12</a>
            <a href="dashboard.php" class="nav-link-custom">Painel</a>
            <a href="contato.php" class="nav-link-custom">Fale Conosco</a>
        </div>
    </div>
</nav>

<!-- CABEÇALHO -->
<div class="cabecalho-pagina">
    <div class="container text-center">
        <h1>Nossa <span>Loja</span></h1>
        <p>O que tem disponível agora.</p>
    </div>
</div>

<!-- PRODUTOS -->
<section class="section">
    <div class="container">
        <div class="row g-4">

            <?php while($produto = $produtos->fetch_assoc()): ?>

            <div class="col-md-4">
                <div class="card-custom">
                    <img src="<?= $produto['imagem'] ?>" alt="<?= $produto['nome'] ?>">
                    <div class="card-body">
                        <span class="badge-eco">sustentável</span>
                        <div class="card-title"><?= $produto['nome'] ?></div>
                        <p class="card-text"><?= $produto['descricao'] ?></p>
                        <div class="preco-linha">
                            <span class="preco-valor">
                                R$ <?= number_format($produto['preco'], 2, ',', '.') ?>
                            </span>
                            <a href="comprar.php?id=<?= $produto['id'] ?>" class="btn-fiobom btn-fiobom-sm">
                                Comprar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
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
