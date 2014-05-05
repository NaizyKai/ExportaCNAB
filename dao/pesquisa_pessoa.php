<?php

if (isset($_GET["q"])) {
    $tabela = filter_input(INPUT_GET, "pesquisa");
    $termo = filter_input(INPUT_GET, "q");
    include('config.php');
    $con = getConexao();

    $sql = "select CODIGO, NOME, CPF_CNPJ from " . $tabela . " where nome like '" . $termo . "%'";

    if (!($res = mysqli_query($con, $sql))) {
        die('Erro: ' . mysqli_error($con));
    }
    print_r("<table>");
    print_r("<tr>");
    print_r("	<th>Codigo</th>");
    print_r("	<th>Nome</th>");
    print_r("	<th>CPF / CNPJ</th>");
    print_r("</tr>");
    while ($escrever = mysqli_fetch_array($res)) {
        print_r("<tr onclick=\"setResult('" . $escrever["CODIGO"] . "');\">");
        print_r("<td>" . $escrever["CODIGO"] . "</td>");
        print_r("<td><a href=\"#\">" . $escrever["NOME"] . "</a></td>");
        print_r("<td>" . $escrever["CPF_CNPJ"] . "</td>");
        print_r("</tr>");
    }
    print_r("</table>");
}
?>