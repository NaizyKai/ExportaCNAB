	function CriaRequest() {
		try {
			request = new XMLHttpRequest();
		} catch (IEAtual) {
			try {
				request = new ActiveXObject("Msxml2.XMLHTTP");
			} catch (IEAntigo) {
				try {
					request = new ActiveXObject("Microsoft.XMLHTTP");
				} catch (falha) {
					request = false;
				}
			}
		}
		if (!request) alert("Seu Navegador n�o suporta Ajax!");
		else return request;
	} /** * Fun��o para enviar os dados */

	function getDados() { // Declara��o de Vari�veis 
		var nome = document.getElementById("txt_pesquisa").value;
		var tipo = document.getElementById("hd_pesquisa").value;
		var result = document.getElementById("div_resultado");
		var xmlreq = CriaRequest(); // Exibi a imagem de progresso 
		//result.innerHTML = '<img src="Progresso1.gif"/>'; // Iniciar uma requisi��o 
		xmlreq.open("GET", "dao/pesquisa_pessoa.php?q=" + nome + "&pesquisa="+tipo, true); // Atribui uma fun��o para ser executada sempre que houver uma mudan�a de ado 
		xmlreq.onreadystatechange = function () { // Verifica se foi conclu�do com sucesso e a conex�o fechada 	(readyState = 4)
		if (xmlreq.readyState == 4) { // Verifica se o arquivo foi encontrado com sucesso 
				if (xmlreq.status == 200) {
					result.innerHTML = xmlreq.responseText;
				} else {
					result.innerHTML = "Erro: " + xmlreq.statusText;
				}
			}
		};
		xmlreq.send(null);
	}	