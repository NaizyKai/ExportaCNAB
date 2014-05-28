<!-- Arquivo: viewExportacaoDetalhe.php -->
<html>
	<head>
		<title></title>
		<link rel="stylesheet" type="text/css" href="../estilos.css" />
	<head>
	<body>
		<?php
            include_once('../dao/config.php');
            $con = getConexao();		
			$sql = "Select bol.*, cli.NOME From boletos bol inner join clientes cli on bol.cod_cliente = cli.codigo  where bol.chave in (select boleto_id from exportacao_item where exportacao_id = " . filter_input(INPUT_POST, "exp"). ")";			
			
			if (!$res = mysqli_query($con, $sql, MYSQL_ASSOC)) {
                die('Erro: ' . die(mysqli_error($con)));
            }
		?>
		
			<h3> Detalhes de Exporta&ccedil;&atilde;o</h3>
			<br />
			<br />
			<a href="../include/processarArquivos.php?exportacao=<?php echo filter_input(INPUT_POST, "exp"); ?>">Gerar arquivo novamente.</a>
			<br />
			<br />
			<table>
				<tr>
					<th>Cliente</th>
					<th>Valor</th>
					<th>Emissão</th>
					<th>Vencimento</th>
					<th></th>
				</tr>
				<?php
					while ($escrever = mysqli_fetch_array($res)) {
						echo("<tr>");
						echo("	<td>" . $escrever["NOME"] . "</td>");
						echo("	<td>" . $escrever["VALOR_BOLETO"] . "</td>");
						echo("	<td>" . $escrever["VENCIMENTO"] . "</td>");
						echo("	<td>" . $escrever["EMISSAO"] . "</td>");
						echo("<td><a href=\"../include/manutencao_exp.php?action=remove&exp=" . $_POST["exp"] ."&boleto=" . $escrever["CHAVE"] . "\">Remover</a></td>");
						echo("</tr>");
					}
				?>
			</table>
			<br />
			<br />
			<a href="listaBoletosArquivo.php?exportacao=<?php echo $_POST["exp"]; ?>">Adicionar novo registro para exportar no arquivo</a><br />
			<a href="#">Excluir Exporta&ccedil;&atilde;o</a>
	</body>
</html>