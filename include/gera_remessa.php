<?php

class GeradorRemessaSicredi {

    public $conta;
    public $listaBoletos;
    private $contador = 0;

    function salvaContagem($novaContagem) {
        $con = getConexao();
        $sql = "update contas set cont_remessa = " . $novaContagem . " where codigo = " . $this->conta->codigo;

        if (!mysqli_query($con, $sql)) {
            die('Erro: ' . mysqli_error($con));
        }
        mysqli_close($con);
    }
	
	
	function removeMascara($Texto) {
		$strChar = ".-/\\*,";
		
		for ($i = 0; $i < strlen($strChar); $i++) {
			$Texto = str_replace(substr($strChar, $i, 1), "", $Texto);			
		}			
		return $Texto;
	}

    function geraRemessa($outputArquivo) {
        $saida = $this->geraHeaderArquivo();
        $saida .= $this->geraDetalhe();
        $saida .= $this->geraTrailer();
        $arq = fopen($outputArquivo, "w");
        fwrite($arq, $saida);
        fclose($arq);
        $this->salvaContagem($this->conta->cont_remessa);
    }

    function geraHeaderArquivo() {
        $this->contador = 1;
        $this->conta->cont_remessa++;
        $linhaHeader = "0";                 // 1 - Identificacao do Header
        $linhaHeader .= "1";                 // 2 - Remessa
        $linhaHeader .= "REMESSA";                // 3 a 9 literal remessa
        $linhaHeader .= "01";                     // 10 a 11 cod serv. cobran�a
        $linhaHeader .= "COBRANCA       ";              // 12 a 26 literal cobrança
        $linhaHeader .= str_pad($this->conta->conta, 5, '0', STR_PAD_LEFT);     // 27 a 31 - conta sem o dv
        $linhaHeader .= str_pad($this->conta->cedente->cpf_cnpj, 14, '0', STR_PAD_LEFT);  // 32 a 45 - CPF / CNPJ
        $linhaHeader .= str_repeat(" ", 31);             // 46 a 76 - Preencher com zeros
        $linhaHeader .= "748";                  // 77 a 79 - número do banco
        $linhaHeader .= str_pad("SICREDI", 15, " ", STR_PAD_RIGHT);       // 80 a 94 - Literal banco
        $linhaHeader .= Date("Ymd");                    // 95 a 102 - data de gera��o do arquivo		
        $linhaHeader .= str_repeat(" ", 8);              // 103 a 110 - filler
        $linhaHeader .= str_pad($this->conta->cont_remessa, 7, '0', STR_PAD_LEFT);    // 111 a 117 - numero da ultima remessa
        $linhaHeader .= str_repeat(" ", 273);             // 118 a 390 - filler
        $linhaHeader .= "2.00";                  // 391 a 394 -  versão do sistema
        $linhaHeader .= str_pad($this->contador, 6, '0', STR_PAD_LEFT);      // 395 a 400 - numero sequencial do registro
        $linhaHeader .= "\r\n";
        return $linhaHeader;
    }

    function geraDetalhe() {
        $detalhe = "";
        foreach ($this->listaBoletos as $boleto) {
            $this->contador++;
            $detalhe .= "1";                   // 1 - Identificação detalhe
            $detalhe .= "A";                   // 2 - Tipo Cobrança
            $detalhe .= "A";                   // 3 - Tipo Carteira
            $detalhe .= "A";                   // 4 - Tipo Impressao
            $detalhe .= str_repeat(" ", 12);               // 5 a 16 - Filler
            $detalhe .= "A";                   // 17 - Tipo moeda (Real)
            $detalhe .= "A";                   // 18 - Tipo Desconto (A = Valor, B = Percentual)
            $detalhe .= "A";                   // 19 - Tipo Juros (A = Valor, B = Percentual)
            $detalhe .= str_repeat(" ", 28);               // 20 a 47 - Filler
            $detalhe .= str_repeat(" ", 9);                // 48 a 56 - nosso numero (Preencher com zeros = gerar automaticamente)
            $detalhe .= str_repeat(" ", 6);               // 57 a 62 - filler
            $detalhe .= Date("Ymd");                 // 63 a 70 - data de instrução
            $detalhe .= " ";                   // 71 - Deixar em branco
            $detalhe .= "S";                   // 72 - Postar o titulo
            $detalhe .= " ";                   // 73 - Deixar em branco
            $detalhe .= "A";                   // 74 - emissao do titulo (A = Banco, B = Cedente)
            $detalhe .= "00";                  // 75 a 76 - numero da parcela
            $detalhe .= "00";                  // 77 a 78 - numero de parcelas
            $detalhe .= str_repeat(" ", 4);               // 79 a 82 - Filler
            $detalhe .= str_pad("0", 10, '0', STR_PAD_LEFT);             // 83 a 92 - Desconto por dia de antecipação
            $detalhe .= str_pad("0", 4, '0', STR_PAD_LEFT);              // 93 a 96 - multa por pagamento em atraso
            $detalhe .= str_repeat(" ", 12);               // 97 a 108 - Filler
            $detalhe .= "01";                   // 109 a 110 - instrução 
            // (01 - Cadastro de t�tulo / 02 - Pedido de baixa / 04 - Concess�o de abatimento / 05 - Cancelamento de abatimento concedido /
            // 06 - Alteracao de vencimento / 09 - Pedido de protesto / 18 - Sustar protesto e baixar t�tulo / 19 - Sustar protesto e manter em carteira)
            $detalhe .= str_pad($boleto->nro_docto, 10, ' 0', STR_PAD_LEFT);       // 111 a 120 - seu n�mero - n�o deve se repetir
            $detalhe .= date("dmy", strtotime($boleto->vencimento));         // 121 a 126 - data de vencimento
            $detalhe .= str_pad($boleto->valor_boleto * 100, 13, '0', STR_PAD_LEFT);     // 127 a 139 - valor do documento
            $detalhe .= str_repeat(" ", 9);               // 140 a 148 - filler
            $detalhe .= "A";                   // 149 - Especie do documento (A - Duplicata Mercantil)
            $detalhe .= "N";                   // 150 - Aceite do titulo
            $detalhe .= Date("dmy", strtotime($boleto->emissao));          // 151 a 156 - data de emiss�o (7 dias antes do vencimento)
            $detalhe .= "00";                   // 157 a 158 - Protestar automaticamente
            $detalhe .= "00";                   // 159 a 160 - Numero de dias protesto automatico
            $detalhe .= str_pad($this->conta->tx_boleto * 100, 13, '0', STR_PAD_LEFT);     // 161 a 173 - valor da taxa
            $detalhe .= "000000";                  // 174 a 179 - data m�xima desconto
            $detalhe .= str_repeat("0", 13);               // 180 a 192 - valor do desconto
            $detalhe .= str_repeat("0", 13);               // 193 a 205 - Filler
            $detalhe .= str_repeat("0", 13);               // 206 a 218 - valor abatimento
            $detalhe .= ($boleto->cliente->tipo_pessoa == "F" ? "1" : "2");       // 219 - Tipo pessoa
            $detalhe .= "0";                   // 220 - Filler
            $detalhe .= $this->removeMascara(str_pad($boleto->cliente->cpf_cnpj, 14, '0', STR_PAD_LEFT));     // 221 a 234 - CPF / CNPJ DO PAGADOR
            $detalhe .= substr(str_pad($boleto->cliente->nome, 40, ' ', STR_PAD_RIGHT), 0, 40);  // 235 a 274 - nome do pagador
            $detalhe .= substr(str_pad($boleto->cliente->endereco, 40, ' ', STR_PAD_RIGHT), 0, 40); // 275 a 314 - endere�o do pagador
            $detalhe .= "00000";                  // 315 a 319 - c�digo do pagador na cooperativa
            $detalhe .= str_repeat("0", 6);               // 320 a 325 - filler
            $detalhe .= " ";                   // 326 - filler
            $detalhe .= str_pad($boleto->cliente->cep, 8, '0', STR_PAD_LEFT);       // 327 a 334 - cep do pagador
            $detalhe .= "00000";                  // 335 a 339 - codigo do pagador no cliente
            $detalhe .= str_repeat("0", 14);               // 340 a 353 - cpf do avalista
            $detalhe .= str_repeat(" ", 41);               // 354 a 394 - nome do avalista
            $detalhe .= str_pad($this->contador, 6, '0', STR_PAD_LEFT);        // 395 a 400
            $detalhe .= "\r\n";
        }
        return $detalhe;
    }

    function geraTrailer() {
        $this->contador++;
        $linha = "9"; // Tipo de registro trailer
        $linha .= "1"; // Identificador de remessa
        $linha .= "748"; // numero sicred
        $linha .= str_pad($this->conta->conta, 5, '0', STR_PAD_LEFT); // conta sem o dv
        $linha .= str_repeat(" ", 384); // filler
        $linha .= str_pad($this->contador, 6, '0', STR_PAD_LEFT); // contador
        $linha .= "\r\n";
        return $linha;
    }

}
