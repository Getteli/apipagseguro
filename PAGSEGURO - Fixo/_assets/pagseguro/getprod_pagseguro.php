<?php
	// chama a configuracao
	include("config.php");

	// header('Content-Type: text/xml;');
	// header('Content-Type: text/html; charset=utf-8');

	// POST
	$url = 'https://ws.sandbox.pagseguro.uol.com.br/v2/checkout?'; // remove o sandbox para a producao

	// recebe as variaveis
	$type = filter_input(INPUT_POST , 'type'); // recebe o tipo do pacote
	$n = filter_input(INPUT_POST , 'n'); // o tempo do pacote

	// gera uma variavel/array, que armazenara tudo
	$data = [];
	$data['email'] = EMAIL_PAGSEGURO; // email
	$data['token'] = TOKEN_SANDBOX; // token
	$data['currency'] = 'BRL'; // tipo de moeda

	// se o type existir, faca
	if ( isset($type) ) { 
		switch ( $type ) { // escolhe o tipo do pacote (da var type)
			case 'premium':
					switch ($n) { // escolhe o numero
						case 1:
								$data['reference'] = '0100'; // um id do pedido
								$data['itemId1'] = '1'; // id do item
								$data['itemQuantity1'] = '1'; // quantidade
								$data['itemDescription1'] = 'Teste 0'; // nome - descricacao
								$data['itemAmount1'] = '10.00'; // preco
							break;
						case 2:
								$data['reference'] = '0101';
								$data['itemId1'] = '2';
								$data['itemQuantity1'] = '1';
								$data['itemDescription1'] = 'Teste 1';
								$data['itemAmount1'] = '20.00';
							break;
						case 3:
								$data['reference'] = '0102';
								$data['itemId1'] = '3';
								$data['itemQuantity1'] = '1';
								$data['itemDescription1'] = 'Teste 2';
								$data['itemAmount1'] = '40.00';
							break;
					}
				break;
		}
	}
	// recebe o todo o array data e cria uma query unica usando a funcao nativa do php
	$data = http_build_query($data);
	// inicia o cURL
	$curl = curl_init($url);
	// parametros do cURL
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // FALSE para TESTE, em producao coloque TRUE
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
	$xml = curl_exec($curl); // executa

	curl_close($curl); // fecha
	// transforma o xml em string para a leitura e get's
	// obs: o simplexml_load_string para exibicao da erro, mas para continuar o codigo, funciona, o simplexml_load_string serve como um header, se nao tiver o header usa o simplexml_load_string, se nao tiver o simplexml_load_string usa o header
	// o @ é para remover o warning
	$xml = @simplexml_load_string($xml);

	// verifica erros e etc
	if ($xml) {
		$code_pre = $xml -> code; // add o code a uma var para o if
		// se tem code vai
		if ($code_pre) {
			// recebe o codigo e adicionar ao form para o btn de compra do pagseguro
			echo $xml -> code;
		}else if(count($xml -> error) > 0){
			// echo $xml ->error-> message // erro explicado
			echo 'erro_dados';
		}else{
			// trate o erro
			echo 'unauthorized';
		}
	}else{
		// trate o erro
		echo 'unauthorized';
	}