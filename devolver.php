<?php

include("conexao.php");

$mensagem      = '';
$tipo_mensagem = '';

//PROCESSA DEVOLUÇÃO
if(isset($_POST['devolver'])){

    $venda_id = (int)$_POST['venda_id'];

    $venda = $conexao->query("
        SELECT qtd_devolucoes FROM vendas WHERE id=$venda_id
    ")->fetch_assoc();

    if(!$venda){
        $mensagem = 'Venda não encontrada.';
        $tipo_mensagem = 'erro';
    } else {

        $devolucoes_anteriores = (int)$venda['qtd_devolucoes'];

        // 2ª DEVOLUÇÃO EM DIANTE: COBRA R$20
        $taxa = ($devolucoes_anteriores >= 1) ? 20.00 : 0.00;

        $conexao->query("
            UPDATE vendas SET
                devolvido = 1,
                qtd_devolucoes = qtd_devolucoes + 1,
                taxa_devolucao = taxa_devolucao + $taxa
            WHERE id = $venda_id
        ");

        if($taxa > 0){
            $mensagem = "Devolução registrada. Taxa de R$ 20,00 cobrada — esta é a " . ($devolucoes_anteriores + 1) . "ª devolução deste item.";
            $tipo_mensagem = 'aviso';
        } else {
            $mensagem = 'Devolução registrada com sucesso. Sem cobrança adicional.';
            $tipo_mensagem = 'sucesso';
        }
    }
}

// BUSCA TODAS AS VENDAS 
$vendas = $conexao->query("
    SELECT vendas.*, produtos.nome AS produto_nome
    FROM vendas
    INNER JOIN produtos ON vendas.produto_id = produtos.id
    ORDER BY data_venda DESC
");
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Devoluções — FIO BOM</title>
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
        <h1>Gestão de <span>Devoluções</span></h1>
        <p>Registre devoluções e veja as taxas aplicadas.</p>
    </div>
</div>

<section class="section">
    <div class="container">

        <a href="dashboard.php" class="link-voltar mb-4 d-inline-block">Voltar ao painel</a>

        <!-- AVISO DE REGRA -->
        <div class="aviso aviso--info mb-5">
            <strong>Regra:</strong>
            Primeira devolução é de graça.
            Da <strong>segunda em diante</strong>, cobra <strong>R$ 20,00</strong> de frete da transportadora.
        </div>

        <!-- MENSAGEM DE RETORNO -->
        <?php if($mensagem): ?>
        <div class="aviso aviso--<?= $tipo_mensagem ?> mb-4">
            <?= $mensagem ?>
        </div>
        <?php endif; ?>

        <!-- TABELA DE VENDAS -->
        <div class="tabela-wrapper">

            <div class="tabela-topo">
                <h3 class="tabela-titulo">Todas as vendas</h3>
            </div>

            <div class="table-responsive">
                <table class="tabela-custom">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Produto</th>
                            <th>Qtd</th>
                            <th>Total pago</th>
                            <th>Devoluções</th>
                            <th>Taxa cobrada</th>
                            <th>Data</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($v = $vendas->fetch_assoc()):
                            $ja_devolvido = (int)$v['devolvido'];
                            $qtd_devolucoes = (int)($v['qtd_devolucoes'] ?? 0);
                            $taxa = (float)($v['taxa_devolucao'] ?? 0);
                        ?>
                        <tr>
                            <td class="tabela-id"><?= $v['id'] ?></td>
                            <td><strong><?= $v['produto_nome'] ?></strong></td>
                            <td><?= $v['quantidade'] ?> un.</td>
                            <td>R$ <?= number_format($v['total'], 2, ',', '.') ?></td>
                            <td>
                                <?php if($qtd_devolucoes > 0): ?>
                                    <span class="badge-taxa"><?= $qtd_devolucoes ?>x devolvido</span>
                                <?php else: ?>
                                    <span class="tabela-id">—</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($taxa > 0): ?>
                                    <span class="badge-taxa">R$ <?= number_format($taxa, 2, ',', '.') ?></span>
                                <?php else: ?>
                                    <span class="tabela-id">—</span>
                                <?php endif; ?>
                            </td>

                            <td><?= date('d/m/Y H:i', strtotime($v['data_venda'])) ?></td>
                            
                            <td>
                                <form method="POST" onsubmit="return confirmarDevolucao(<?= $qtd_devolucoes ?>)">
                                    <input type="hidden" name="venda_id" value="<?= $v['id'] ?>">
                                    <button
                                        type="submit"
                                        name="devolver"
                                        class="btn-tabela btn-tabela--devolver"
                                    >
                                        <?= $qtd_devolucoes === 0 ? 'Devolver' : 'Devolver (+R$20)' ?>
                                    </button>
                                </form>
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

<script>
function confirmarDevolucao(qtdDevolucoes) {
    if(qtdDevolucoes >= 1) {
        return confirm('Esta é a ' + (qtdDevolucoes + 1) + 'ª devolução deste item.\nSerá cobrada uma taxa de R$ 20,00. Confirmar?');
    }
    return confirm('Confirmar devolução deste item?');
}
</script>
</body>
</html>
