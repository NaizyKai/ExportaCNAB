<?php
include_once('classes.php');
include_once('gera_remessa.php');
include_once('../dao/getters.php');
include_once('nome_exportacao.php');
include_once('config.php');

if (isset($_POST["exportacao"])) {
	$listaBol = array();
	foreach ($_POST["selecionar"] as $chave) {
		array_push($listaBol, getBoleto($chave));
	}

	if ($_POST["exportacao"] == 0)  {
		$arq_nome = "tmpOutput.txt";
		$gerador = new GeradorRemessaSicredi();
		$gerador->listaBoletos = $listaBol;
		$gerador->conta = getConta($_POST["conta"]);
		$gerador->geraRemessa($arq_nome);
		$novoNome = getNovoNome($_POST["conta"], 0);
		header('Location: doDownload.php?conta='.$_POST["conta"]."&nome=". $novoNome);
	} else {
		header('Location: manutencao_exp.php?action=add&boletos=' . serialize($listaBol) . '&exp='. $_POST["exportacao"]);
	}
} else if (isset($_GET["exportacao"])) {
	$listaBol = getBoletosFromExportacao($_GET["exportacao"]);
	$con = getConexao();
	
	$conta = getContaFromExportacao($_GET["exportacao"]);
	
	$arq_nome = "tmpOutput.txt";
	$gerador = new GeradorRemessaSicredi();
	$gerador->listaBoletos = $listaBol;
	$gerador->conta = $conta;
	$gerador->exp = $_GET["exportacao"];
	$gerador->geraRemessa($arq_nome);
	$novoNome = getNovoNome($conta->codigo, $_GET["exportacao"]);
	header('Location: doDownload.php?conta='.$_POST["conta"]."&nome=". $novoNome);		
}