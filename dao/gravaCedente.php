<?php

include_once("config.php");

$sql = "INSERT INTO cedentes "
        . "(NOME"
        . ",CPF_CNPJ"
        . ",TIPO_PESSOA"
        . ",ENDERECO"
        . ",CIDADE"
        . ",UF"
        . ",CEP) " .
        "VALUES (
       	'".filter_input(INPUT_POST, "NOME")."',
       	'".filter_input(INPUT_POST, "CPF_CNPJ")."',
       	'".filter_input(INPUT_POST, "TIPO_PESSOA")."',
       	'".filter_input(INPUT_POST, "ENDERECO")."',
       	'".filter_input(INPUT_POST, "CIDADE")."',
       	'".filter_input(INPUT_POST, "UF")."',
       	'".filter_input(INPUT_POST, "CEP")."')";

$con = getConexao();

if (!mysqli_query($con, $sql)) {
    die('Error: ' . mysqli_error($con));
}
header('Location: ../index.html');

mysqli_close($con);
