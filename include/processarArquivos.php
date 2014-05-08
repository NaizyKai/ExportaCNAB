<?php
include_once('classes.php');
include_once('gera_remessa.php');
include_once('../dao/getters.php');

$listaBol = array();
foreach ($_POST["selecionar"] as $chave) {
    array_push($listaBol, getBoleto($chave));
}

$arq_nome = "tmpOutput.txt";
$gerador = new GeradorRemessaSicredi();
$gerador->listaBoletos = $listaBol;
$gerador->conta = getConta($_POST["conta"]);
$gerador->geraRemessa($arq_nome);
header('Location: doDownload.php?conta='.$_POST["conta"].'&arq='.$arq_nome);