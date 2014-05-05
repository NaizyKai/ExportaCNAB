<html>
    <head>
        <title>Cadastro de Cliente</title>
        <link rel="stylesheet" type="text/css" href="../estilos.css" />
        <script type="text/javascript" src="../funcoes.js"></script>
    </head>
    <body>
        <form name="cadastro" action="../dao/gravaCliente.php" method="POST">
            <label for="NOME">NOME: </label> 
            <input type="text" name="NOME" id="NOME" class="edt_uppercase" /> <br/>
            <label for="CPF_CNPJ">CPF / CNPJ: </label>
            <input type="text" name="CPF_CNPJ" id="CPF_CNPJ" maxlength="14" onkeypress="return validaEditInteger(event);" /> <br/>
            <label for="ENDERECO">ENDERE&Ccedil;O: </label>
            <input type="text" name="ENDERECO" id="ENDERECO" class="edt_uppercase" /> <br/>
            <label for="CIDADE">CIDADE: </label>
            <input type="text" name="CIDADE" id="CIDADE" class="edt_uppercase" /> <br/>
            <label for="UF">UF: </label>
            <input type="text" name="UF" id="UF" class="edt_uppercase" maxlength="2" /> <br/>
            <label for="CEP">CEP: </label>
            <input type="text" name="CEP" id="CEP" maxlength="8" onkeypress="return validaEditInteger(event);" /> <br/>
            TIPO PESSOA:<br/>
            <input type="radio" name="TIPO_PESSOA" id="TP_FISICA" value="0"><label for="TP_FISICA">Fisica</label><br/> 
            <input type="radio" name="TIPO_PESSOA" id="TP_JURIDICA" value="1"><label for="TP_JURIDICA">Jur&iacute;dica</label><br/>
            <input type="submit" value="Gravar" />
        </form>

    </body>