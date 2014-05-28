<?php
include_once('../dao/config.php');
include_once('classes.php');
include_once('../dao/getters.php');

function getNextExportacao($conta, $exportacao) {
    $con = getConexao();
	if ($exportacao > 0) {
		$sql_retorna_cod_exp = "SELECT count(chave) FROM exportacoes WHERE CONTA = ? AND Cast(DATA_EXPORTACAO As Date) = Cast(NOW() As Date)";
	} else {
		$sql_retorna_cod_exp = "SELECT count(chave) FROM exportacoes WHERE CONTA = ? AND DATA_EXPORTACAO > (SELECT DATA_EXPORTACAO FROM exportacoes WHERE CHAVE = " . $exportacao .")";
	}
    $stmt = mysqli_prepare($con, $sql_retorna_cod_exp);
	
	if ($stmt === FALSE) {
		die("ERRO: " . mysqli_error($con));
	}
    mysqli_stmt_bind_param($stmt, "i", $conta->codigo);
    if (!mysqli_stmt_execute($stmt)) {
		die("ERRO: " . mysqli_error($con));
	}
    $chave = 0;
    mysqli_stmt_bind_result($stmt, $chave);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
    if ($chave >= 1) {
        $chave++;
    }
    return $chave;
}

function getNovoNome($conta, $exportacao) {
    $conta = getConta($conta);
    $mes = (int)date("m");
    
    switch ($mes) {
        case 10:
            $mes = "O";
            break;
        case 11:
            $mes = "N";
            break;
        case 12:
            $mes = "D";
            break;
    }
    $ext = "";
    $qtdExp = getNextExportacao($conta, $exportacao);
   
    if ($qtdExp == 0) {
        $ext = "crm";
    } else {
        $ext = "rm" . $qtdExp;
    }    
    $nome = strtoupper(str_pad($conta->conta, 5, '0', STR_PAD_LEFT) . $mes . str_pad(date("d"), 2, '0', STR_PAD_LEFT) . "." . $ext);
    return $nome;
}