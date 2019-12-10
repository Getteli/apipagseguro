<?php
	// chama a configuracao
	// header('Content-Type: text/xml;');
	// header('Content-Type: text/html; charset=utf-8');
	include("config.php");

	$TokenCard = $_POST['TokenCard'];
	$HashCard = $_POST['HashCard'];
	$QtdParcelas = filter_input(INPUT_POST,'QtdParcelas',FILTER_SANITIZE_SPECIAL_CHARS);
	$ValorParcelas = filter_input(INPUT_POST,'ValorParcelas',FILTER_SANITIZE_SPECIAL_CHARS);

// cartao
// $Data["email"]=EMAIL_PAGSEGURO;
// $Data["token"]=TOKEN_SANDBOX;

// $Data["receiverEmail"]=EMAIL_PAGSEGURO;

$arrdoc[] = array(
"type" => "CPF",
"value" => "11013671724"
);

$arrdoc2[] = array(
"type" => "CPF",
"value" => "22111944785"
);

$Data1 = array(
	'plan' => '4D97593799996FEBB4180F9E49E94539',
	// 'plan' => '620B12DD-3C3C-4132-24DA-9FB07D4453F6',
	'reference' => '83783783737',
	'sender' => array(
		'name' => 'Jose Comprador',
		'email' => 'c57980665160880589547@sandbox.pagseguro.com.br', // email do comprador
		'hash' => $HashCard,
		'phone' => array(
			'areaCode' => '37',
			'number' => '99999999'
		),
		'address' => array(
			'street' => 'Av. Brig. Faria Lima',
			'number' => '1384',
			'complement' => '5 Andar',
			'district' => 'Jardim Paulistano',
			'city' => 'Sao Paulo',
			'state' => 'SP',
			'country' => 'BRA',
			'postalCode' => '01452002'
		),
		'documents' => $arrdoc
	),
	'paymentMethod' => array(
		'type' => 'CREDITCARD',
		'creditCard' => array(
			'token' => $TokenCard,
			'holder' => array(
				'name' => 'Jose Comprador',
				'birthDate' => '27/10/1987',
				'documents' => $arrdoc2,
				'phone' => array(
					'areaCode' => '21',
					'number' => '986964552'
				),
				'billingAddress' => array(
					'street' => '21',
					'number' => '986964552',
					'complement' => 'casa',
					'district' => 'Rio de Janeiro',
					'city' => 'Rio de Janeiro',
					'state' => 'RJ',
					'country' => 'BRA',
					'postalCode' => '20911030'
				)
			)
		)
	)
);

	// $Data2 = http_build_query($Data2);
	// POST
	// $Url = "https://ws.sandbox.pagseguro.uol.com.br/v2/transactions?";
	// $Url = "https://pagseguro.uol.com.br/checkout/v2/pre-approvals/request.html?email=".EMAIL_PAGSEGURO."&token=".TOKEN_SANDBOX."";
	// $Url = "https://ws.pagseguro.uol.com.br/pre-approvals?email=".EMAIL_PAGSEGURO."&token=".TOKEN_PAGSEGURO."";
	$Url = "https://ws.sandbox.pagseguro.uol.com.br/pre-approvals?email=".EMAIL_PAGSEGURO."&token=".TOKEN_SANDBOX."";

	$Curl = curl_init($Url);
	curl_setopt($Curl, CURLOPT_HTTPHEADER, array('Content-type: application/json;charset=UTF-8', 'Accept: application/vnd.pagseguro.com.br.v3+json;charset=ISO-8859-1'));
	curl_setopt($Curl,CURLOPT_POST,true);
	curl_setopt($Curl,CURLOPT_SSL_VERIFYPEER,false);
	curl_setopt($Curl,CURLOPT_RETURNTRANSFER,true);
	curl_setopt($Curl,CURLOPT_POSTFIELDS, json_encode($Data1) );
	$Retorno = curl_exec($Curl);
	curl_close($Curl);

	$Xml = simplexml_load_string($Retorno);
	// var_dump($Retorno);
	var_dump($Xml);
	// echo json_encode($Data1);