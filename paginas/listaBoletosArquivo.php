<html>
    <head>
        <title>Listando Boletos</title>
        <link rel="stylesheet" type="text/css" href="../estilos.css" />
        <script type="text/javascript" src="../ajax.js"></script>
        <script type="text/javascript">
            function ListarContas() {
                var cod_conta = document.getElementById('conta').value;
                var result = document.getElementById("div_contas");
                document.getElementById("div_filtro").hidden = true;
                if (cod_conta != 0) {
                    document.getElementById("div_filtro").hidden = false;
                    var xmlreq = CriaRequest();
                    xmlreq.open("GET", "../include/listarContas.php?conta=" + cod_conta, true);
                    xmlreq.onreadystatechange = function() {
                        if (xmlreq.readyState == 4) {
                            if (xmlreq.status == 200) {
                                result.innerHTML = xmlreq.responseText;
                            } else {
                                result.value = "Erro: " + xmlreq.statusText;
                            }
                        }
                    };
                    xmlreq.send(null);
                } else {
                    result.innerHTML = "";
                }
            }
            function FiltrarData() {
                var cod_conta = document.getElementById('conta').value;
                var data_ini = document.getElementById('data_ini').value;
                var data_fim = document.getElementById('data_fim').value;
                var result = document.getElementById("div_contas");
                if (cod_conta != 0) {
                    document.getElementById("div_filtro").hidden = false;
                    var xmlreq = CriaRequest();
                    xmlreq.open("GET", "listarContas.php?conta=" + cod_conta + "&data_ini=" + data_ini + "&data_fim=" + data_fim, true);
                    xmlreq.onreadystatechange = function() {
                        if (xmlreq.readyState == 4) {
                            if (xmlreq.status == 200) {
                                result.innerHTML = xmlreq.responseText;
                            } else {
                                result.value = "Erro: " + xmlreq.statusText;
                            }
                        }
                    };
                    xmlreq.send(null);
                } else {
                    result.innerHTML = "";
                }
            }
        </script>
    </head>
    <body>
        <form action="../include/processarArquivos.php" method="POST" name="frmProcessar">
            <?php
            include_once('../dao/config.php');

            $con = getConexao();
            $sql = "Select Descricao, Codigo From contas order by Descricao";
            if (!$res = mysqli_query($con, $sql, MYSQL_ASSOC)) {
                die('Erro: ' . die(mysqli_error($con)));
            }
            echo("<label for=\"conta\">Conta: </label> ");
            echo("<select name=\"conta\" id=\"conta\" value=\"Selecionar\" onchange=\"ListarContas();\">");
            echo("<option value=\"0\" selected=\"true\">Selecione</option> ");
            while ($escrever = mysqli_fetch_array($res)) {
                echo("<option value=" . $escrever["Codigo"] . ">" . $escrever["Descricao"] . "</option>");
            }
            echo("</select>");
            echo("<br/>");
            ?>
            <div id="div_filtro" hidden="true" >
                <label for="data_ini">Data Inicial: </label>
                <input type="date" id="data_ini" name="data_ini" /> </br>
                <label for="data_fim">Data Final: </label>
                <input type="date" id="data_fim" name="data_fim" />
                <input type="button" value="Filtrar por Data" onclick="FiltrarData();" /><br/><br />
            </div>
            <div id="div_contas">
            </div>
            <br/>
            <input type="submit" value="Enviar" >
        </form>
    </body>
</html>