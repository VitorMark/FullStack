<?php

$host = "localhost";
$usuario = "root";
$senha = "Levi2208";
$banco = "fiobom";

$conexao = new mysqli(
    $host,
    $usuario,
    $senha,
    $banco
);

if($conexao->connect_error){
    die("Erro de conexão: " . $conexao->connect_error);
}

?>

