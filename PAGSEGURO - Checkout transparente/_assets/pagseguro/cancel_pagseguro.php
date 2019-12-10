<?php
	// charset
	// header('Content-Type: text/xml;');
	header('Content-Type: text/html; charset=utf-8');

	// chama a configuracao
	include("config.php");

	$Code = filter_input(INPUT_GET,'code',FILTER_SANITIZE_SPECIAL_CHARS);

	// POST
	$Url="https://ws.sandbox.pagseguro.uol.com.br/v2/transactions/cancels?";

	$data['email'] = EMAIL_PAGSEGURO; // email
	$data['token'] = TOKEN_SANDBOX; // token
	$data['transactionCode'] = $Code; // code da transacao
	$data = http_build_query($data);

	$Curl=curl_init($Url);
	curl_setopt($Curl,CURLOPT_HTTPHEADER,Array("Content-Type: application/x-www-form-urlencoded; charset=UTF-8"));
	curl_setopt($Curl,CURLOPT_POST,true);
	curl_setopt($Curl, CURLOPT_POSTFIELDS, $data);
	curl_setopt($Curl,CURLOPT_SSL_VERIFYPEER,false);
	curl_setopt($Curl,CURLOPT_RETURNTRANSFER,true);
	$Retorno=curl_exec($Curl);
	curl_close($Curl);
	$Xml=simplexml_load_string($Retorno);

	var_dump($Xml);
	// echo $Retorno;