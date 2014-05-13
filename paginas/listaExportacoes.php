<html>
    <head>
        <title>Listando Boletos</title>
        <link rel="stylesheet" type="text/css" href="../estilos.css" />
        <script type="text/javascript" src="../ajax.js"></script>
    </head>
    <body>
        <form action="../include/processarArquivos.php" method="POST" name="frmProcessar">
            <?php
            include_once('../dao/config.php');

            $con = getConexao();
            $sql = "select con.descricao " .
                         ",exp.data_exportacao " .
						 ",exp.chave " .
                     "from exportacoes exp " .
                    "inner join contas con " .
                       "on con.codigo = exp.conta " .
	                "order by exp.data_exportacao desc";

            if (!$res = mysqli_query($con, $sql, MYSQL_ASSOC)) {
                die('Erro: ' . die(mysqli_error($con)));
            }
			echo("<table>");
			echo("<tr>");
			echo("<th>");
			echo("Selecionar");
			echo("</th>");
			echo("<th>");
			echo("Conta");
			echo("</th>");
			echo("<th>");
			echo("Data Exporta&ccedil;&atilde;o");
			echo("</th>");
			echo("</tr>");
			while ($escrever = mysqli_fetch_array($res)) {
				echo("<tr>");
				echo("<td>");
				echo("<input type=\"radio\" name=\"exportacao\" value=\"" . $escrever["chave"] . "\">");
				echo("</td>");
				echo("<td>");
				echo($escrever["descricao"]);
				echo("</td>");
				echo("<td>");
				echo($escrever["data_exportacao"]);
				echo("</td>");
			}
			echo("</table>");
            echo("<br/>");
            ?>
            <input type="submit" value="Estornar">
        </form>
    </body>
</html>