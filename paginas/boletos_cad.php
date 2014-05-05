<html>
    <head>
        <title>Cadastro de Boletos</title>
        <link rel="stylesheet" type="text/css" href="../estilos.css" />
		<script type="text/javascript" src="../ajax.js"></script>
		<script type="text/javascript">		
			function ExibePesquisa() {
				document.getElementById("dv_pesquisa").hidden = false;
			}
			
			function ExibeGetConta() {
				nossoNro = document.getElementById("NOSSO_NUMERO").value;
				
				if (!(nossoNro == "") && !(nossoNro == null)) {
					if (!confirm("Nosso n�mero j� informado, deseja sobrescrever?"))
						return;
				}
				var emissao = document.getElementById('DT_EMISSAO').value;				
				if ((emissao == "") || (emissao == "dd/mm/aaaa")) {
					alert("Informe a emissao.");
					return;
				}			
				document.getElementById("dv_getConta").hidden = false;
			}
			function OcultaGetConta() {
				document.getElementById("dv_getConta").hidden = true;
			}			

			function setResult(val) {
				document.getElementById("dv_pesquisa").hidden = true;
				if (val != null)
					document.getElementById("COD_CLIENTE").value = val;
				document.getElementById("div_resultado").innerHTML = null;
				document.getElementById("txt_pesquisa").value = null;
			}
			
			function geraNossoNumero() {				
				var cod_conta = document.getElementById('select_conta').value;				
				var emissao = document.getElementById('DT_EMISSAO').value;
				OcultaGetConta();
				var result = document.getElementById("NOSSO_NUMERO");
				var xmlreq = CriaRequest(); // Exibi a imagem de progresso 
				xmlreq.open("GET", "include/geraNossoNumero.php?emissao=" + emissao + "&cod_conta="+/*cod_conta*/1, true); // Atribui uma fun��o para ser executada sempre que houver uma mudan�a de ado 
				xmlreq.onreadystatechange = function () { // Verifica se foi conclu�do com sucesso e a conex�o fechada 	(readyState = 4)
				if (xmlreq.readyState == 4) { // Verifica se o arquivo foi encontrado com sucesso 
						if (xmlreq.status == 200) {
							result.value = xmlreq.responseText;
						} else {
							result.value = "Erro: " + xmlreq.statusText;
						}
					}
				};
				xmlreq.send(null);				
			}
		</script>
    </head>
    <body>
		<div id="dv_pesquisa" hidden="true">
			<img id="btn_fechar" src="../images/close.png" onclick="setResult(null);" />
			<form id="frmPesquisa" method="GET">
				<input type="text" id="txt_pesquisa" name="q" />
				<input type="hidden" id="hd_pesquisa" name="pesquisa" value="clientes" />
				<input type="button" value="Pesquisar" onclick="getDados();" />
			</form>
			<div id="div_resultado">
			</div>
		</div>
        <form action="../dao/gravaBoletos.php" method="POST">                         
            <label for="COD_CLIENTE">Cod. Cliente:</label>
            <input type="text" name="COD_CLIENTE" id="COD_CLIENTE"
                   onkeypress="return validaEditInteger(event);"/>
			<img src="../images/search.png" onclick="ExibePesquisa();"><br/>

            <label for="VALOR_BOLETO">Valor:</label>
            <input type="text" name="VALOR_BOLETO" id="VALOR_BOLETO" /><br/>

            <label for="NRO_DOCTO">Nro. do Docto (&Uacute;nico):</label>
            <input type="text" name="NRO_DOCTO" id="NRO_DOCTO"/><br/>

            <label for="CONTA">Conta Corrente: </label>
			<?php
            include_once('../dao/config.php');

            $con = getConexao();
            $sql = "Select Descricao, Codigo From Contas order by Descricao";
            if (!$res = mysqli_query($con, $sql, MYSQL_ASSOC)) {
                die('Erro: ' . die(mysqli_error($con)));
            }
            echo("<select name=\"CONTA\">");
            while ($escrever = mysqli_fetch_array($res)) {
                echo("<option value=" . $escrever["Codigo"] . ">" . $escrever["Descricao"] . "</option>");
            }
            echo("</select>");
            echo("<br/>");
			?>
			<br/>            

            <label for="DT_EMISSAO">Dt. Emiss&atilde;o:</label>
            <input type="date" name="EMISSAO" id="DT_EMISSAO"/><br/>

            <label for="VENCIMENTO">Dt. Vencimento</label>
            <input type="Date" name="VENCIMENTO" id="VENCIMENTO"/><br/>                                

            <input type="Submit" value="Enviar"/><br/>
        </form>
    </body>
