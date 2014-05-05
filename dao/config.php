<?php
// CONFIGURAÇÕES DA APLICAÇÃO
function getConexao() {

	$bancoUrl = "localhost";
	$bancoUser = "root";
	$bancoPassword = "";
	$bancoNome = "COBRANCA";	
	
	return mysqli_connect($bancoUrl, $bancoUser, $bancoPassword, $bancoNome);
}

if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
