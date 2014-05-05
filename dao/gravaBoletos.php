<?php

include_once("config.php");

$sql = "INSERT INTO BOLETOS "
        . "(COD_CLIENTE"
        . ",VENCIMENTO"
        . ",EMISSAO"
        . ",VALOR_BOLETO"
        . ",NRO_DOCTO"
        . ",CONTA) " .
        "VALUES (
       	'" . filter_input(INPUT_POST, "COD_CLIENTE") . "',
       	'" . filter_input(INPUT_POST, "VENCIMENTO") . "',
       	'" . filter_input(INPUT_POST, "EMISSAO") . "',
       	'" . str_replace(",", ".", filter_input(INPUT_POST, "VALOR_BOLETO")) . "',
        '" . filter_input(INPUT_POST, "NRO_DOCTO") . "',
       	'" . filter_input(INPUT_POST, "CONTA") . "')";

$con = getConexao();

if (!mysqli_query($con, $sql)) {
    die('Erro: ' . mysqli_error($con));
}
header('Location: ../index.html');

mysqli_close($con);
