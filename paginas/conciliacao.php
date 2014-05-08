<!DOCTYPE html>
<html>
    <head>
        <title>Upload de Arquivo de Conciliacao</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width">
        <link rel="stylesheet" type="text/css" href="../estilos.css" />
    </head>
    <body>
        <div>
            <form enctype="multipart/form-data" action="../include/doUpload.php" method="post">
                <?php
                    include_once('../dao/config.php');

                    $con = getConexao();
                    $sql = "Select Descricao, Codigo From contas order by Descricao";
                    if (!$res = mysqli_query($con, $sql, MYSQL_ASSOC)) {
                        die('Erro: ' . die(mysqli_error($con)));
                    }
                    echo("<label for=\"conta\">Conta: </label> ");
                    echo("<select name=\"CONTA\" id=\"CONTA\" value=\"Selecionar\" onchange=\"ListarContas();\">");
                    echo("<option value=\"0\" selected=\"true\">Selecione</option> ");
                    while ($escrever = mysqli_fetch_array($res)) {
                        echo("<option value=" . $escrever["Codigo"] . ">" . $escrever["Descricao"] . "</option>");
                    }
                    echo("</select>");
                    echo("<br/>");
                ?>
                <input type="hidden" name="MAX_FILE_SIZE" value="300000" />
                <label for="userfile">Envio de Arquivo:</label>
                <input name="userfile" id="userfile" type="file" /><br />                
                <input type="submit" value="Enviar" />
            </form>
        </div>
    </body>
</html>
