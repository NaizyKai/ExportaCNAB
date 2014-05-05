<?php

include_once('../dao/config.php');
include_once('./classes.php');
include_once('../dao/getters.php');

function processarArquivoRetorno($fileName) {
    $arq = fopen($fileName, "r");
    $boletos = [];
    $boleto = null;
    $conta = getConta(filter_input(INPUT_POST, "CONTA"));
    $i = 0;
    while (!feof($arq)) {
        $linha = fgets($arq, 4096);
        $i++;
        if ((substr($linha, 0, 1) != "0") && (substr($linha, 1, 1) != "2") && ($i == 1)) { // Registro Header && Retorno
            print "<h2>Erro: O arquivo informado n&atilde;o se trata de arquivo de retorno banc&aacute;rio.</h2>";
            return;
        } elseif (substr($linha, 0, 1) == "1") { // Detalhe
            $boleto = new Boleto;
            $boleto->conta = $conta;
            $boleto->nro_docto = substr($linha, 116, 10); // Seu Número
            $boleto->vencimento = strtotime(substr($linha, 150, 2) . "-" . substr($linha, 148, 2) . "-" . substr($linha, 146, 2)); // Data de Vencimento
            $boleto->valor_boleto = ((float) substr($linha, 152, 13)) / 100;
            $boleto->valor_pago = ((float) substr($linha, 253, 13)) / 100;
            $boleto->data_pagamento = strtotime(substr($linha, 328, 4) . "-" . substr($linha, 332, 2) . "-" . substr($linha, 334, 2));
            array_push($boletos, $boleto);
        }
    }
    return $boletos;
}

function gravarBoletos($listaBoletos) {
    $con = getConexao();
    $bolNaoEncontrado = [];
    $sqlBusca = "SELECT CHAVE "
            . "    FROM BOLETOS "
            . "   WHERE VENCIMENTO = ?"
            . "     AND VALOR_BOLETO = ?"
            . "     AND NRO_DOCTO =  ?"
            . "     AND CONTA = ?"
            . "     AND PAGAMENTO <> 1";

    $sqlUpdate = "UPDATE BOLETOS "
            . "      SET PAGAMENTO = 1, "
            . "          VALOR_PAGO = ?, "
            . "          DATA_PAGTO = ? "
            . "    WHERE CHAVE = ? ";
    $stmt = mysqli_prepare($con, $sqlBusca);
    $stmt_update = mysqli_prepare($con, $sqlUpdate);
    foreach ($listaBoletos as $boleto) {
        $chave = 0;
        $vencimento = date("Y-m-d", $boleto->vencimento);
        $documento = trim($boleto->nro_docto);
        mysqli_stmt_bind_param($stmt, "sdsi", $vencimento, $boleto->valor_boleto, $documento, $boleto->conta->codigo);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $chave);
        if (mysqli_stmt_fetch($stmt) && ($boleto->valor_pago > 0)) {
            mysqli_stmt_close($stmt);
            mysqli_stmt_bind_param($stmt_update, "dsi", $boleto->valor_pago, $pagamento, $chave);
            mysqli_stmt_execute($stmt_update);
            print "<h2>Foram conciliados " . mysqli_affected_rows($stmt_update) . ", boletos com sucesso.</h2>";
            mysqli_stmt_close($stmt_update);
        } else {
            array_push($bolNaoEncontrado, $boleto);
        }
    }
    return $bolNaoEncontrado;
}

$uploadDir = '../uploads/';
$uploadFile = $uploadDir . $_FILES['userfile']['name'];
print "<pre>";
if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadDir . $_FILES['userfile']['name'])) {
    $boletos = processarArquivoRetorno($uploadFile);

    if (!empty($boletos)) {
        $n_encontrados = gravarBoletos($boletos);
        
        if (!empty($n_encontrados)) {
            print "<h3>Os Seguintes boletos n&atilde;o foram encontrados para concilia&ccedil;&atilde;o ou j&aacute; foram conciliados:</h3>";
			print "<table border=\"1\">";
			print "<tr>";
			print "<th>Vencimento</th>";
			print "<th>Conta</th>";
			print "<th>Valor</th>";
			print "<th>Nro. Docto</th>";
			print "</tr>";
			foreach ($n_encontrados as $boleto) {
				print "<tr>";
				print "<td>" . date("d/m/Y", $boleto->vencimento) . "</td>";
				print "<td>" . $boleto->conta->descricao . "</td>";				
				print "<td>" . $boleto->valor_boleto . "</td>";
				print "<td>" . $boleto->nro_docto . "</td>";
				print "</tr>";
			}
			print "</table>";
			print "<br />";
			print "<a href=\"../index.html\">Voltar</a>";
		}
    } else {
        print "<h2>Erro: N&atilde;o foram encontrados nenhum registro no arquivo informado.</h2>";
    }
} else {
    print "N&atilde;o foi poss&iacute;vel realizar o envio. Aqui est&aacute; alguma informa&ccedil;&atilde;o:\n";
    print_r($_FILES);
}
print "</pre>";
