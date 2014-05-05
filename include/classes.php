<?php

class Cliente {
    public $codigo;
    public $nome;
    public $cpf_cnpj;
    public $endereco;
    public $tipo_pessoa;
    public $cep;
	public $cidade;
	public $uf;
}

class Cedente {
    public $codigo;
    public $nome;
    public $tipo_pessoa;
    public $cpf_cnpj;
    public $endereco;
    public $cidade;
    public $uf;
    public $cep;
}

class Conta {
    public $descricao;
    public $agencia;
    public $conta;
    public $conta_dv;
    public $banco;
    public $cedente;
    public $cont_remessa;
    public $tx_boleto;
    public $codigo;
	public $ultimo_nossonro_gerado;
}

class Boleto {
    public $chave;
    public $cliente;
    public $conta;
    public $vencimento;
    public $emissao;
    public $valor_boleto;
    public $valor_pago;
    public $nro_docto;
    public $nosso_numero;
    public $pagamento;
    public $data_pagamento;
}