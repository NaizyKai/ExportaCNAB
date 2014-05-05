<?php
include_once('../dao/config.php');
include_once('classes.php');
include_once('../dao/getters.php');

function gravaExportacao($conta) {
    $con = getConexao();
    $sql_grava_exp = "INSERT INTO EXPORTACOES (CONTA) VALUES (?)";    
    $stmt = mysqli_prepare($con, $sql_grava_exp);
    mysqli_stmt_bind_param($stmt, "i", $conta);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);   
}

function getNextExportacao($conta) {
    $con = getConexao();
    $sql_retorna_cod_exp = "SELECT count(chave) FROM EXPORTACOES WHERE CONTA = ? AND Cast(DATA_EXPORTACAO As Date) = Cast(NOW() As Date)";
    $stmt = mysqli_prepare($con, $sql_retorna_cod_exp);
    mysqli_stmt_bind_param($stmt, "i", $conta);
    mysqli_stmt_execute($stmt);
    $chave = 0;
    mysqli_stmt_bind_result($stmt, $chave);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
    if ($chave >= 1) {
        $chave++;
    }
    return $chave;
}

function getNovoNome($conta) {
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
    $qtdExp = getNextExportacao($conta);
    print $qtdExp;
    
    if ($qtdExp == 0) {
        $ext = "crm";
    } else {
        $ext = "rm" . $qtdExp;
    }    
    $nome = str_pad($conta->conta, 5, '0', STR_PAD_LEFT) . '.' . $mes . str_pad(date("d"), 2, '0', STR_PAD_LEFT) . "." . $ext;
    return $nome;
}