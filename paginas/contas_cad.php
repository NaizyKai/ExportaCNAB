<html>
    <head>
        <title>Cadastro de Contas</title>
        <link rel="stylesheet" type="text/css" href="../estilos.css" />
        <script type="text/javascript" src="../funcoes.js"></script>
		<script type="text/javascript" src="../ajax.js"></script>
		<script type="text/javascript">
			function ExibePesquisa() {
				document.getElementById("dv_pesquisa").hidden = false;
			}

			function setResult(val) {
				document.getElementById("dv_pesquisa").hidden = true;
				if (val != null)
					document.getElementById("COD_CEDENTE").value = val;
				document.getElementById("div_resultado").innerHTML = null;
				document.getElementById("txt_pesquisa").value = null;
			}
			
			function SetaValor(campo, valor, editable) {
				document.getElementById(campo).value = valor;
				document.getElementById(campo).disabled = !editable;
			}
			
			function MostraEsconde(campo) {
				document.getElementById(campo).hidden = !document.getElementById(campo).hidden;
			}
			
			function SetActionUpd() {
				document.getElementById("cadastro").action = "../dao/gravaConta.php?oper=update";
			}
		</script>
    </head>
    <body>
		<div id="dv_pesquisa" hidden=true >
		<img id="btn_fechar" src="../images/close.png" onclick="setResult(null);" />
		<form id="frmPesquisa" method="GET">
			<input type="text" id="txt_pesquisa" name="q" />
			<input type="hidden" id="hd_pesquisa" name="pesquisa" value="cedentes" />
			<input type="button" value="Pesquisar" onclick="getDados();" />
		</form>
		<div id="div_resultado">
		</div>
		</div>
		<br/>
		<div>	
			<form id="cadastro" name="cadastro" action="../dao/gravaConta.php?oper=<?php print isset($_GET["editar"]) ? "edit" : "insert"?>" method="POST">
				<label for="DESCRICAO">DESCRI&Ccedil;&Atilde;O: </label> 
				<input type="text" name="DESCRICAO" id="DESCRICAO" class="edt_uppercase" /> <br/>            
				<label for="AGENCIA">AG&Ecirc;NCIA: </label> 
				<input type="text" name="AGENCIA" id="AGENCIA" /> <br/>
				<label for="CONTA">CONTA: </label> 
				<input type="text" name="CONTA" id="CONTA" /> <br/>
				<label for="CONTA_DV">CONTA_DV: </label> 
				<input type="text" name="CONTA_DV" id="CONTA_DV" /> <br/>
				<label for="BANCO">BANCO: </label> 
				<input type="text" name="BANCO" maxlength="3" id="BANCO"
					   onkeypress="return validaEditInteger()(event);"/> <br/>
				<label for="COD_CEDENTE">CODIGO CEDENTE: </label> 
				<input type="text" name="COD_CEDENTE" 
					   onkeypress="return validaEditInteger(event);" id="COD_CEDENTE" />
				<img id="BTN_PESQUISA" src="../images/search.png" alt="Pesquisar" onclick="ExibePesquisa();"><br/>
				<label for="CONT_REMESSA">NRO ULTIMA REMESSA: </label> 
				<input type="text" name="CONT_REMESSA"  id="CONT_REMESSA"
					   onkeypress="return validaEditInteger(event);" /> <br/>
				<label for="ULT_NOSSONRO_GERADO">NRO ULTIMP NOSSO NRO: </label>             
				<input type="text" name="ULT_NOSSONRO_GERADO" id="ULT_NOSSONRO_GERADO"
					   onkeypress="return validaEditInteger(event);" /> <br/>
				<label for="TX_BOLETO">TAXA BOLETO: </label> 
				<input type="text" name="TX_BOLETO" id="TX_BOLETO"
					   onkeypress="return validaEditNumerico(event);" /> <br/>            
				<input type="hidden" id="CODIGO" name="CODIGO"/>
				<input type="submit" value="Gravar">
			</form>
		</div>
		<?php
			if (isset($_GET["editar"])) {
				include_once('../dao/config.php');
				$con = getConexao();
				$codigo = filter_input(INPUT_GET, "editar");
				$sql = "SELECT DESCRICAO, AGENCIA, CONTA, CONTA_DV, BANCO, COD_CEDENTE, CONT_REMESSA, ULTIMO_NOSSONRO_GERADO, TX_BOLETO, CODIGO FROM contas WHERE CODIGO = " . $codigo;
				
				if (!$res = mysqli_query($con, $sql, MYSQL_ASSOC)) {
					die('Erro: ' . die(mysqli_error($con)));
				}	
				if ($escrever = mysqli_fetch_array($res)) {
					echo("<script>SetaValor(\"DESCRICAO\", \"" . $escrever["DESCRICAO"] . "\", false)</script>");
					echo("<script>SetaValor(\"AGENCIA\", \"" . $escrever["AGENCIA"] . "\", true)</script>");
					echo("<script>SetaValor(\"CONTA\", \"" . $escrever["CONTA"] . "\", true)</script>");
					echo("<script>SetaValor(\"CONTA_DV\", \"" . $escrever["CONTA_DV"] . "\", true)</script>");
					echo("<script>SetaValor(\"BANCO\", \"" . $escrever["BANCO"] . "\", false)</script>");
					echo("<script>SetaValor(\"COD_CEDENTE\", \"" . $escrever["COD_CEDENTE"] . "\", false)</script>");
					echo("<script>SetaValor(\"CONT_REMESSA\", \"" . $escrever["CONT_REMESSA"] . "\", true)</script>");
					echo("<script>SetaValor(\"ULT_NOSSONRO_GERADO\", \"" . $escrever["ULTIMO_NOSSONRO_GERADO"] . "\", true)</script>");				
					echo("<script>SetaValor(\"TX_BOLETO\", \"" . $escrever["TX_BOLETO"] . "\", true)</script>");				
					echo("<script>MostraEsconde(\"BTN_PESQUISA\")</script>");
					echo("<script>SetaValor(\"CODIGO\", \"" . $escrever["CODIGO"] . "\", true)</script>");
				}
			}			
		?>			
    </body>