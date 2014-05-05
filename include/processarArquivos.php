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
header('Content-Description: File Transfer');
header('Content-Disposition: attachment; filename="' . getNovoNome($_POST["conta"]) .'"');
header('Content-Type: application/octet-stream');
header('Content-Transfer-Encoding: binary');
header('Content-Length: ' . filesize($arq_nome));
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
header('Expires: 0');
readfile($arq_nome);
gravaExportacao($_POST["conta"]);