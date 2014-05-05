<html>
    <head>
        <title>Listando Boletos</title>
        <link rel="stylesheet" type="text/css" href="../estilos.css" />
	</head>
    <body>
		<a href="contas_cad.php">Cadastrar Nova Conta</a> <br/>	
	
		<h3>Selecione a conta a editar</h3>
		<table>
			<tr>
				<th>Codigo</th>
				<th>Descri&ccedil;&atilde;o</th>
			</tr>	
            <?php
            include_once('../dao/config.php');

            $con = getConexao();
            $sql = "Select Descricao, Codigo From Contas order by Descricao";
            if (!$res = mysqli_query($con, $sql, MYSQL_ASSOC)) {
                die('Erro: ' . die(mysqli_error($con)));
            }
            while ($escrever = mysqli_fetch_array($res)) {
				echo("<tr>");
                echo("<td>". $escrever["Codigo"] . "</td>");
				echo("<td><a href=\"contas_cad.php?editar=" . $escrever["Codigo"] ." \">" . $escrever["Descricao"] . "</a></td>");
				echo("</tr>");
            }
            ?>
		</table>
    </body>
</html>