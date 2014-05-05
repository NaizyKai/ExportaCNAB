<?php
	include_once('../dao/config.php');
	$con = getConexao();
	$sql = "SELECT b.chave, b.vencimento, b.emissao, b.valor_boleto, "
	. "b.nro_docto, b.nosso_numero, cli.nome, cli.cpf_cnpj "
	. "FROM boletos b INNER JOIN clientes cli "
	. "ON b.cod_cliente = cli.codigo "
	. "WHERE b.pagamento = 0 "
	. " and b.conta = " . filter_input(INPUT_GET, "conta");
	
	if (isset($_GET["data_ini"])){
		$sql .= " and b.emissao >= \"" . filter_input(INPUT_GET, "data_ini") . "\"";
	}
	
	if (isset($_GET["data_fim"])) {
		$sql .= " and b.emissao <= \"" . filter_input(INPUT_GET, "data_fim") . "\"";
	}	
	
	if (!$res = mysqli_query($con, $sql, MYSQL_ASSOC)) {
		die('Erro: ' . die(mysqli_error($con)));
	}		

	echo("<table><tr>"
	. "<th></th>"
	. "<th width=\"300px\">Cliente</th>"
	. "<th width=\"120px\">CPF / CNPJ</th>"
	. "<th width=\"90px\">Emiss&atilde;o</th>"
	. "<th width=\"90px\">Vencimento</th>"
	. "<th width=\"70px\">Valor</th>"
	. "<th>Nosso N&uacute;mero</th>"
	. "<th>N&uacute;mero Docto.</th></tr>");
	while ($escrever = mysqli_fetch_array($res)) {
		echo("<tr><td>"
		. "<input type=\"checkbox\" class=\"check\" name=\"selecionar[]\" value=\"" . $escrever["chave"] . "\""
		. "</td>"
		. "<td>" . $escrever["nome"] . "</td>"
		. "<td>" . $escrever["cpf_cnpj"] . "</td>"
		. "<td>" . date("d/m/Y", strtotime($escrever["emissao"])) . "</td>"
		. "<td>" . date("d/m/Y", strtotime($escrever["vencimento"])) . "</td>"
		. "<td>" . $escrever["valor_boleto"] . "</td>"
		. "<td>" . $escrever["nosso_numero"] . "</td>"
		. "<td>" . $escrever["nro_docto"] . "</td></tr>");
	}
	echo("</table>");
	mysqli_close($con);
