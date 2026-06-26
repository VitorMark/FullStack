<?php

include("conexao.php");

$id = $_GET['id'];

$produto = $conexao->query(
    "SELECT * FROM produtos WHERE id=$id"
)->fetch_assoc();

if(isset($_POST['editar'])){

    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $categoria = $_POST['categoria'];
    $imagem = $_POST['imagem'];

    $conexao->query("
        UPDATE produtos SET
        nome='$nome', descricao='$descricao', preco='$preco',
        categoria='$categoria', imagem='$imagem'
        WHERE id=$id
    ");

    header("Location: dashboard.php");
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar Produto — FIO BOM</title>
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

<!-- CONTEÚDO -->
<section class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">

                <a href="dashboard.php" class="link-voltar">Voltar ao painel</a>

                <div class="sobre-box mt-3">

                    <h2 class="mb-4">Editar produto</h2>

                    <form method="POST">

                        <label class="label-campo">Nome</label>
                        <input type="text" name="nome" class="campo-form mb-3"
                               value="<?= $produto['nome'] ?>" required>

                        <label class="label-campo">Descrição</label>
                        <textarea name="descricao" class="campo-form mb-3" required><?= $produto['descricao'] ?></textarea>

                        <label class="label-campo">Preço (R$)</label>
                        <input type="number" step="0.01" name="preco" class="campo-form mb-3"
                               value="<?= $produto['preco'] ?>" required>

                        <label class="label-campo">Categoria</label>
                        <input type="text" name="categoria" class="campo-form mb-3"
                               value="<?= $produto['categoria'] ?>" required>

                        <label class="label-campo">URL da imagem</label>
                        <input type="text" name="imagem" class="campo-form mb-4"
                               value="<?= $produto['imagem'] ?>" required>

                        <button class="btn-fiobom w-100" name="editar">
                            Salvar alterações
                        </button>

                    </form>
                </div>
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
