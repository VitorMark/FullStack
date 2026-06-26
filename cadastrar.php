<?php

include("conexao.php");

if(isset($_POST['salvar'])){

    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $categoria = $_POST['categoria'];
    $imagem = $_POST['imagem'];

    $conexao->query("
        INSERT INTO produtos (nome, descricao, preco, categoria, imagem)
        VALUES ('$nome', '$descricao', '$preco', '$categoria', '$imagem')
    ");

    header("Location: dashboard.php");
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Novo Produto — FIO BOM</title>
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

                    <h2 class="mb-4">Novo produto</h2>

                    <form method="POST">

                        <label class="label-campo">Nome</label>
                        <input type="text" name="nome" class="campo-form mb-3" placeholder="Ex: Camiseta de Linho" required>

                        <label class="label-campo">Descrição</label>
                        <textarea name="descricao" class="campo-form mb-3" placeholder="Descreva o produto..." required></textarea>

                        <label class="label-campo">Preço (R$)</label>
                        <input type="number" step="0.01" name="preco" class="campo-form mb-3" placeholder="0,00" required>

                        <label class="label-campo">Categoria</label>
                        <input type="text" name="categoria" class="campo-form mb-3" placeholder="Ex: Camisetas" required>

                        <label class="label-campo">URL da imagem</label>
                        <input type="text" name="imagem" class="campo-form mb-4" placeholder="https://..." required>

                        <button class="btn-fiobom w-100" name="salvar">
                            Cadastrar produto
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
