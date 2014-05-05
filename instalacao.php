<?php

include('dao/config.php');

$con = getConexao();

$sql = "CREATE TABLE boletos (
  CHAVE int(11) NOT NULL AUTO_INCREMENT,
  COD_CLIENTE int(11) DEFAULT NULL,
  VENCIMENTO date DEFAULT NULL,
  EMISSAO date DEFAULT NULL,
  VALOR_BOLETO decimal(9,2) DEFAULT NULL,
  NRO_DOCTO int(11) DEFAULT NULL,
  NOSSO_NUMERO varchar(10) DEFAULT NULL,
  PAGAMENTO tinyint(1) NOT NULL DEFAULT '0',
  CONTA int(11) DEFAULT NULL,
  PRIMARY KEY (CHAVE),
  KEY BOLETOS_FK (COD_CLIENTE))";

if (!mysqli_query($con, $sql)) {
    die('Erro: ' . mysqli_error($con));
}


$sql = "CREATE TABLE cedentes (
  codigo int(11) NOT NULL AUTO_INCREMENT,
  nome varchar(60) DEFAULT NULL,
  cpf_cnpj varchar(14) DEFAULT NULL,
  tipo_pessoa decimal(1,0) NOT NULL,
  endereco varchar(50) DEFAULT NULL,
  cidade varchar(20) DEFAULT NULL,
  uf varchar(2) DEFAULT NULL,
  cep decimal(8,0) DEFAULT NULL,
  PRIMARY KEY (codigo))";

if (!mysqli_query($con, $sql)) {
    die('Erro: ' . mysqli_error($con));
}
$sql = "CREATE TABLE clientes (
  CODIGO int(11) NOT NULL AUTO_INCREMENT,
  NOME varchar(50) DEFAULT NULL,
  CPF_CNPJ varchar(14) DEFAULT NULL,
  ENDERECO varchar(50) DEFAULT NULL,
  TIPO_PESSOA decimal(1,0) NOT NULL DEFAULT '0',
  CIDADE varchar(20) DEFAULT NULL,
  UF varchar(2) DEFAULT NULL,
  CEP decimal(8,0) DEFAULT NULL,
  PRIMARY KEY (CODIGO))";

if (!mysqli_query($con, $sql)) {
    die('Erro: ' . mysqli_error($con));
}

$sql = "CREATE TABLE IF NOT EXISTS `contas` (
  CODIGO int(11) NOT NULL AUTO_INCREMENT,
  DESCRICAO varchar(50) DEFAULT NULL,
  AGENCIA varchar(6) DEFAULT NULL,
  CONTA varchar(15) DEFAULT NULL,
  CONTA_DV varchar(2) DEFAULT NULL,
  BANCO decimal(3,0) DEFAULT NULL,
  COD_CEDENTE int(11) DEFAULT NULL,
  CONT_REMESSA int(11) DEFAULT NULL,
  ULTIMO_NOSSONRO_GERADO int(11) DEFAULT NULL,
  TX_BOLETO decimal(5,2) DEFAULT NULL,
  PRIMARY KEY (CODIGO),
  KEY contas_fk (COD_CEDENTE))";

if (!mysqli_query($con, $sql)) {
    die('Erro: ' . mysqli_error($con));
}

$sql = "ALTER TABLE boletos ADD CONSTRAINT BOLETOS_FK FOREIGN KEY (COD_CLIENTE) REFERENCES clientes (CODIGO)";

if (!mysqli_query($con, $sql)) {
    die('Erro: ' . mysqli_error($con));
}
$sql = "ALTER TABLE contas ADD CONSTRAINT contas_fk FOREIGN KEY (COD_CEDENTE) REFERENCES cedentes (codigo)";

if (!mysqli_query($con, $sql)) {
    die('Erro: ' . mysqli_error($con));
}

$sql = "create table exportacoes (
    chave int(11) not null auto_increment,
    conta int(11) default null,
    data_exportacao timestamp null default current_timestamp,
    primary key (chave))";

if (!mysqli_query($con, $sql)) {
    die('Erro: ' . mysqli_error($con));
}


$sql = "alter table boletos add column data_pagto date";

if (!mysqli_query($con, $sql)) {
    die('Erro: ' . mysqli_error($con));
}
$sql = "alter table valor_pago decimal(9,2)";

if (!mysqli_query($con, $sql)) {
    die('Erro: ' . mysqli_error($con));
}

header('Location: index.html');
mysqli_close($con);