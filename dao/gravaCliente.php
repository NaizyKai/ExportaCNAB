<?php

include_once("config.php");

$sql = "INSERT INTO CLIENTES (CEP, CIDADE, CPF_CNPJ, ENDERECO, NOME, TIPO_PESSOA, UF) " .
        "VALUES (
       	'".filter_input(INPUT_POST, "CEP")."',
       	'".filter_input(INPUT_POST, "CIDADE")."',
       	'".filter_input(INPUT_POST, "CPF_CNPJ")."',
       	'".filter_input(INPUT_POST, "ENDERECO")."',
       	'".filter_input(INPUT_POST, "NOME")."',
       	'".filter_input(INPUT_POST, "TIPO_PESSOA")."',
       	'".filter_input(INPUT_POST, "UF")."')";

$con = getConexao();

if (!mysqli_query($con, $sql)) {
    die('Error: ' . mysqli_error($con));
}
header('Location: ../index.html');

mysqli_close($con);
