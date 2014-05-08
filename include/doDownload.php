<?php
	include_once('nome_exportacao.php');
	header('Content-Description: File Transfer');
	header('Content-Disposition: attachment; filename="' . getNovoNome($_GET["conta"]) .'"');
	header('Content-Type: application/octet-stream');
	header('Content-Transfer-Encoding: binary');
	header('Content-Length: ' . filesize($_GET["arq"]));
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header('Pragma: public');
	header('Expires: 0');
	readfile($_GET["arq"]);
	gravaExportacao($_GET["conta"]);