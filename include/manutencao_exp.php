<!-- include/manutencao_exp.php -->
<?php
	include_once("../dao/config.php");
	include_once("classes.php");
	class ManutencaoExportacao {

		private $conexao = null;
	
		function __construct() {
			$this->conexao = getConexao();
		}
	
		function AdicionarBoleto($Boletos, $Exportacao) {
			$sql = "Insert into exportacao_item (boleto_id, exportacao_id) values (?, ?)";
			$stmt = mysqli_prepare($this->conexao, $sql);
			
			if ($stmt === FALSE) {
				die("ERRO: " . mysqli_error($con));
			}	
			foreach($Boletos as $boleto) {
				mysqli_stmt_bind_param($stmt, "ii", $boleto->chave, $Exportacao);
						
				if (!mysqli_stmt_execute($stmt)) {
					die("ERRO: " . mysqli_error($con));
				}
			}
			mysqli_stmt_close($stmt);
		
		}
	
		function RemoverBoleto($Boleto, $Exportacao) {
			$sql = "Delete from exportacao_item where boleto_id = ? and exportacao_id = ?";
			$stmt = mysqli_prepare($this->conexao, $sql);
			
			if ($stmt === FALSE) {
				die("ERRO: " . mysqli_error($con));
			}	
			
			mysqli_stmt_bind_param($stmt, "ii", $Boleto, $Exportacao);
			
			if (!mysqli_stmt_execute($stmt)) {
				die("ERRO: " . mysqli_error($con));
			}	
			
			mysqli_stmt_close($stmt);
		}
		
		function ExcluirExportacao($Exportacao) {
			$sql = "select * from exportacoes where chave = " . $Exportacao;
			
			if (!$res = mysqli_query($this->conexao, $sql, MYSQL_ASSOC)) {
				die('Erro: ' . die(mysqli_error($con)));
			}
			
			$conta = 0;
			$remessa = 0;
			
			if ($consulta = mysqli_fetch_array($res)) {
				$conta = $consulta["conta"];
				$remessa = $consulta["remessa"];
				mysqli_close($this->conexao);
				$this->conexao = getConexao();
			} else {
				die('Exporta&ccedil;&atilde;o n&atilde;o existe!');
			}
			
			$this->IncluirRemessaLivre($conta, $remessa);
		
			$sql = "Delete from exportacoes where chave = ?";
			
			$stmt = mysqli_prepare($this->conexao, $sql);
			
			if ($stmt === FALSE) {
				die("ERRO: " . mysqli_error($con));
			}	
			
			mysqli_stmt_bind_param($stmt, "i", $Exportacao);
			
			if (!mysqli_stmt_execute($stmt)) {
				die("ERRO: " . mysqli_error($con));
			}
			
			mysqli_stmt_close($stmt);
		}
	
		function GetIdExportacaoLivre($CodigoConta) {		
			$sql = "select remessa from remessas_disponiveis where conta = " . $CodigoConta;
			
			if (!$res = mysqli_query($this->conexao, $sql, MYSQL_ASSOC)) {
				die('Erro: ' . die(mysqli_error($con)));
			}
			
			$resultado = 0;
			
			if ($consulta = mysqli_fetch_array($res)) {
				$resultado = $consulta["remessa"];
			}
			
			return $resultado;
		}
		
		function IncluirRemessaLivre($CodigoConta, $Remessa) {
			$sql = "Insert Into remessas_disponiveis (conta, remessa) values (?, ?)";
			$stmt = mysqli_prepare($this->conexao, $sql);
			
			if ($stmt === FALSE) {
				die("ERRO: " . mysqli_error($this->conexao));
			}	
			
			mysqli_stmt_bind_param($stmt, "ii", $CodigoConta, $Remessa);
			
			if (!mysqli_stmt_execute($stmt)) {
				die("ERRO: " . mysqli_error($con));
			}
			
			mysqli_stmt_close($stmt);
		}
	}
	
	if (isset($_GET["action"])) {	
	
		$acao = $_GET["action"];
		
		if (($acao == "getexplivre") && isset($_GET["conta"])) {
			$manut = new ManutencaoExportacao();
			$manut->GetIdExportacaoLivre($_GET["conta"]);
		} else {		
			
			if (($acao == "remove") && isset($_GET["exp"]) && isset($_GET["boleto"])) {
				$manut = new ManutencaoExportacao();
				$manut->RemoverBoleto($_GET["boleto"], $_GET["exp"]);
			}
			
			if (($acao == "add") && isset($_GET["exp"]) && isset($_GET["boletos"])) {
				$manut = new ManutencaoExportacao();
				$boletos = unserialize($_GET["boletos"]); 
				$manut->AdicionarBoleto($boletos, $_GET["exp"]);
			}	
			
			if (($acao == "removeexp") && isset($_GET["exp"])) {
				$manut = new ManutencaoExportacao();
				$manut->ExcluirExportacao($_GET["exp"]);
			}	
			
			header('Location: ../paginas/listaExportacoes.php');
		}
	}