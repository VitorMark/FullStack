<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FIO BOM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script> <!-- importação do jquery -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.css"> <!-- importação do toast pro envio do formulário -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.js"></script> <!-- importação do toast pro envio do formulário -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
<!-- NAVBAR -->  
<nav class="navbar navbar-expand-lg">
    <div class="container">

        <a class="navbar-brand" href="index.php">
            FIO BOM
        </a>

        <div class="nav-links">
            <a href="index.php" class="nav-link-custom active">Início</a>
            <a href="produtos.php" class="nav-link-custom">Loja</a>
            <a href="sobre.php" class="nav-link-custom">Sobre</a>
            <a href="ods.php" class="nav-link-custom">ODS 12</a>
            <a href="dashboard.php" class="nav-link-custom">Painel</a>
            <a href="contato.php" class="nav-link-custom">Fale Conosco</a>
        </div>
    </div>
</nav>
<!-- CONTEÚDO -->
<section class="pagina-contato">
    <div class="card-contato">
        <h3 class="titulo-contato">ENVIE SUA MENSAGEM</h3>
        <form class="form-contato" onsubmit="event.preventDefault(); abrirGmail();">
            <input class="input-contato" type="text" id="assunto" name="assunto" placeholder="Assunto" required>
            <textarea class="textarea-contato" name="mensagem" id="mensagem" placeholder="Sua mensagem" rows="5" required></textarea>
            <button class="botao-contato" type="submit">Enviar</button>
        </form>
    
        <script src="index.js"></script>
    
    </section>
</section>
<!--FOOTER -->
<footer>
    <div class="footer-line"></div>
    <p><strong>FIO BOM</strong> &copy; 2026 &mdash; Tijuca, Rio de Janeiro.</p>
</footer>
</body>
</html>