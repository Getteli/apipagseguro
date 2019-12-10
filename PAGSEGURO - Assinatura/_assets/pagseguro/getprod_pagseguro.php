<?php
	// chama a configuracao
	include("config.php");

	// POST
	$url = 'https://ws.sandbox.pagseguro.uol.com.br/v2/checkout'; // remove o sandbox para a producao
	$type = filter_input(INPUT_POST , 'type'); // recebe o tipo do pacote
	$n = filter_input(INPUT_POST , 'n'); // o tempo do pacote

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
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // false para TESTE, em producao coloque true
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
	$xml = curl_exec($curl); // executa

	curl_close($curl); // fecha
	// transforma o xml em string para a leitura e get's
	$xml = simplexml_load_string($xml);

	// verifica erros e etc
	if($xml == 'Unauthorized'){
		echo 'unauthorized';
		exit;
	}else if(count($xml -> error) > 0){
		// echo $xml ->error-> message // erro explicado
		echo 'erro_dados';
		exit;
	}else{
		echo $xml -> code; // exibi o code, return da erro, use echo
		exit;
	}