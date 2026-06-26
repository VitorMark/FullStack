<?php

include("conexao.php");

$id = $_GET['id'];

$produto = $conexao->query(
    "SELECT * FROM produtos WHERE id=$id"
)->fetch_assoc();

// RESTRIÇÃO: limite de 50 vendas por dia
$vendas_hoje = $conexao->query("
    SELECT COUNT(*) AS total FROM vendas
    WHERE DATE(data_venda) = CURDATE()
")->fetch_assoc();

$limite_diario = 50;
$vendas_restantes = $limite_diario - (int)$vendas_hoje['total'];
$loja_fechada = $vendas_restantes <= 0;

// PROCESSA COMPRA 
$mensagem = '';
$tipo_mensagem = '';

if(isset($_POST['comprar'])){

    if($loja_fechada){

        $mensagem = 'A loja atingiu o limite de 50 vendas por hoje. Volte amanhã!';
        $tipo_mensagem = 'erro';

    } else {

        $qtd = (int)$_POST['quantidade'];
        $total = $produto['preco'] * $qtd;

        $conexao->query("
            INSERT INTO vendas (produto_id, quantidade, total)
            VALUES ('$id', '$qtd', '$total')
        ");

        $mensagem = 'Compra realizada com sucesso!';
        $tipo_mensagem = 'sucesso';

        echo "<script>
            alert('Compra realizada com sucesso!');
            window.location='produtos.php';
        </script>";
    }
}

// PROCESSA DEVOLUÇÃO
if(isset($_POST['devolver'])){

    $venda_id = (int)$_POST['venda_id'];

    // BUSCA QUANTAS VEZES ESTE ITEM JÁ FOI DEVOLVIDO
    $venda = $conexao->query("
        SELECT qtd_devolucoes FROM vendas WHERE id=$venda_id
    ")->fetch_assoc();

    $devolucoes_anteriores = (int)($venda['qtd_devolucoes'] ?? 0);

    // NA PRIMEIRA DEVOLUÇÃO, É GRÁTIS. DA SEGUNDA EM DIANTE, COBRAMOS R$ 20,00 DE TAXA
    $taxa = ($devolucoes_anteriores >= 1) ? 20.00 : 0.00;

    $conexao->query("
        UPDATE vendas SET
            devolvido = 1,
            qtd_devolucoes = qtd_devolucoes + 1,
            taxa_devolucao = taxa_devolucao + $taxa
        WHERE id = $venda_id
    ");

    if($taxa > 0){
        $mensagem = 'Devolução registrada. Taxa de R$ 20,00 cobrada (2ª devolução deste item).';
        $tipo_mensagem = 'aviso';
    } else {
        $mensagem = 'Devolução registrada com sucesso.';
        $tipo_mensagem = 'sucesso';
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Comprar — FIO BOM</title>
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
            <a href="contato.php" class="nav-link-custom">Fale Conosco</a>
        </div>
    </div>
</nav>

<!-- CONTEÚDO -->
<section class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">

                <a href="produtos.php" class="link-voltar">Voltar pra loja</a>

                <!-- AVISO DE LIMITE DIÁRIO -->
                <?php if($loja_fechada): ?>
                <div class="aviso aviso--erro mb-4">
                    <strong>Loja encerrada por hoje.</strong>
                    Atingimos o limite de <?= $limite_diario ?> vendas diárias. Volta amanhã!
                </div>
            
                <?php elseif($vendas_restantes <= 10): ?>
                <div class="aviso aviso--aviso mb-4">
                    Só restam <strong><?= $vendas_restantes ?> vagas</strong> hoje.
                </div>

                <?php endif; ?>

                <!-- MENSAGEM DE RETORNO -->
                <?php if($mensagem): ?>
                <div class="aviso aviso--<?= $tipo_mensagem ?> mb-4">
                    <?= $mensagem ?>
                </div>

                <?php endif; ?>

                <div class="sobre-box">

                    <span class="badge-eco mb-3 d-inline-block">sustentável</span>

                    <h2 class="mb-1"><?= $produto['nome'] ?></h2>
                    <p class="text-muted mb-3"><?= $produto['descricao'] ?></p>

                    <div class="preco-destaque mb-4">
                        R$ <?= number_format($produto['preco'], 2, ',', '.') ?>
                    </div>

                    <!-- FORMULÁRIO DE COMPRA -->
                    <?php if(!$loja_fechada): ?>
                    <form method="POST">

                        <label class="label-campo">Quantidade</label>
                        <input
                            type="number"
                            name="quantidade"
                            value="1"
                            min="1"
                            class="campo-form mb-4"
                        >

                        <button class="btn-fiobom w-100" name="comprar">
                            Finalizar compra
                        </button>
                    </form>

                    <?php else: ?>
                    <a href="produtos.php" class="btn-fiobom-outline-dark w-100 text-center">
                        Ver outros produtos
                    </a>
                    <?php endif; ?>
                </div>

                <!-- INFORMAÇÃO SOBRE DEVOLUÇÃO -->
                <div class="aviso aviso--info mt-4">
                    <strong>Devolução:</strong>
                    Primeira é grátis. Da segunda em diante, R$ 20,00 de frete.
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
