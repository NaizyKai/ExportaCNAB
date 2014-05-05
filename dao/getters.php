<?php
include_once('../dao/config.php');

function getCliente($codigo) {
    $con = getConexao();
    $sql = "select * from clientes where codigo = " . $codigo;
    if (!$res = mysqli_query($con, $sql, MYSQL_ASSOC)) {
        die('Erro: ' . die(mysqli_error($con)));
    }
    $cliente = new Cliente();
    if ($escrever = mysqli_fetch_array($res)) {
        $cliente->codigo = $escrever["CODIGO"];
        $cliente->nome = $escrever["NOME"];
        $cliente->cpf_cnpj = $escrever["CPF_CNPJ"];
        $cliente->endereco = $escrever["ENDERECO"];
        $cliente->cep = $escrever["CEP"];
        $cliente->tipo_pessoa = $escrever["TIPO_PESSOA"] == 0 ? "F" : "J";
		$cliente->cidade = $escrever["CIDADE"];
		$cliente->uf = $escrever["UF"];
		
    }
    mysqli_close($con);
    return $cliente;
}

function getConta($codigo) {
    $con = getConexao();
    $sql = "select * from contas where codigo = " . $codigo;
    if (!$res = mysqli_query($con, $sql, MYSQL_ASSOC)) {
        die('Erro: ' . die(mysqli_error($con)));
    }
    $conta = new Conta();
    if ($escrever = mysqli_fetch_array($res)) {
        $conta->agencia = $escrever["AGENCIA"];
        $conta->banco = $escrever["BANCO"];
        $conta->conta = $escrever["CONTA"];
        $conta->conta_dv = $escrever["CONTA_DV"];
        $conta->cont_remessa = $escrever["CONT_REMESSA"];
        $conta->descricao = $escrever["DESCRICAO"];
        $conta->tx_boleto = $escrever["TX_BOLETO"];
        $conta->cedente = getCedente($escrever["COD_CEDENTE"]);
        $conta->codigo = $codigo;
		$conta->ultimo_nossonro_gerado = $escrever["ULTIMO_NOSSONRO_GERADO"];
    }
    mysqli_close($con);
    return $conta;
}

function getCedente($codigo) {
    $con = getConexao();
    $sql = "select * from cedentes where codigo = " . $codigo;
    if (!$res = mysqli_query($con, $sql, MYSQL_ASSOC)) {
        die('Erro: ' . die(mysqli_error($con)));
    }
    $cedente = new Cedente();
    if ($escrever = mysqli_fetch_array($res)) {
        $cedente->nome = $escrever["nome"];
        $cedente->codigo = $escrever["codigo"];
        $cedente->cpf_cnpj = $escrever["cpf_cnpj"];
        $cedente->endereco = $escrever["endereco"];
        $cedente->tipo_pessoa = $escrever["tipo_pessoa"] == 0 ? "F" : "J";
        $cedente->cep = $escrever["cep"];
        $cedente->cidade = $escrever["cidade"];
        $cedente->uf = $escrever["uf"];
    }
    mysqli_close($con);
    return $cedente;
}

function getBoleto($chave) {
    $con = getConexao();
    $sql = "select * from boletos where chave = " . $chave;
    if (!$res = mysqli_query($con, $sql, MYSQL_ASSOC)) {
        die('Erro: ' . die(mysqli_error($con)));
    }
    $boleto = new Boleto();
    if ($escrever = mysqli_fetch_array($res)) {
        $boleto->chave = $escrever["CHAVE"];
        $boleto->cliente = getCliente($escrever["COD_CLIENTE"]);
        $boleto->emissao = $escrever["EMISSAO"];
        $boleto->vencimento = $escrever["VENCIMENTO"];
        $boleto->nosso_numero = $escrever["NOSSO_NUMERO"];
        $boleto->nro_docto = $escrever["NRO_DOCTO"];
        $boleto->valor_boleto = $escrever["VALOR_BOLETO"];
        $boleto->pagamento = $escrever["PAGAMENTO"];
    }
    mysqli_close($con);
    return $boleto;
}