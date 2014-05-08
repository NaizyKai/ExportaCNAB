<?php
	header('Location: ../index.html');
	
	include_once("config.php");
	$sql = "";

	if ($_GET["oper"] == "edit") {
		$sql = "UPDATE CONTAS " .
		       "SET AGENCIA = \"" .filter_input(INPUT_POST, "AGENCIA")."\"".
			   "    ,CONTA = \"" .filter_input(INPUT_POST, "CONTA")."\"".
			   "    ,CONTA_DV =  \"" .filter_input(INPUT_POST, "CONTA_DV")."\"".
			   "    ,CONT_REMESSA = \"" .filter_input(INPUT_POST, "CONT_REMESSA")."\"".
			   "    ,ULTIMO_NOSSONRO_GERADO = \"" .filter_input(INPUT_POST, "ULT_NOSSONRO_GERADO")."\"".
			   "    ,TX_BOLETO = \"".str_replace(",", ".", filter_input(INPUT_POST, "TX_BOLETO"))."\"".
			   " WHERE CODIGO = \"".filter_input(INPUT_POST, "CODIGO")."\"";			   
	} else {

	$sql = "INSERT INTO CONTAS "
			. "(DESCRICAO"
			. ",AGENCIA"
			. ",CONTA"
			. ",CONTA_DV"
			. ",BANCO"
			. ",COD_CEDENTE"
			. ",CONT_REMESSA"
			. ",ULTIMO_NOSSONRO_GERADO"
			. ",TX_BOLETO) " .
			"VALUES (
			'".filter_input(INPUT_POST, "DESCRICAO")."',
			'".filter_input(INPUT_POST, "AGENCIA")."',
			'".filter_input(INPUT_POST, "CONTA")."',
			'".filter_input(INPUT_POST, "CONTA_DV")."',
			'".filter_input(INPUT_POST, "BANCO")."',
			'".filter_input(INPUT_POST, "COD_CEDENTE")."',
			'".filter_input(INPUT_POST, "CONT_REMESSA")."',
			'".filter_input(INPUT_POST, "ULT_NOSSONRO_GERADO")."',    
			'".str_replace(",", ".", filter_input(INPUT_POST, "TX_BOLETO"))."')";
	}
	$con = getConexao();

	if (!mysqli_query($con, $sql)) {
		die('Error: ' . mysqli_error($con));
	}

	mysqli_close($con);