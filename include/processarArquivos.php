<?php
include_once('classes.php');
include_once('gera_remessa.php');
include_once('../dao/getters.php');
include_once('nome_exportacao.php');	

$listaBol = array();
foreach ($_POST["selecionar"] as $chave) {
    array_push($listaBol, getBoleto($chave));
}

$arq_nome = "tmpOutput.txt";
$gerador = new GeradorRemessaSicredi();
$gerador->listaBoletos = $listaBol;
$gerador->conta = getConta($_POST["conta"]);
$gerador->geraRemessa($arq_nome);
$novoNome = getNovoNome($_POST["conta"]);
gravaExportacao($_POST["conta"]);
header('Location: doDownload.php?conta='.$_POST["conta"]."&nome=". $novoNome);