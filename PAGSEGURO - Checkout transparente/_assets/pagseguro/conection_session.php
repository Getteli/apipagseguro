<?php
	// chama a configuracao
	include("config.php");

	// POST
	$url = "https://ws.sandbox.pagseguro.uol.com.br/v2/sessions?";

	$data['email'] = EMAIL_PAGSEGURO; // email
	$data['token'] = TOKEN_SANDBOX; // token
	$data = http_build_query($data);

	$curl = curl_init($url);
	curl_setopt($curl,CURLOPT_HTTPHEADER,array("Content-Type: application/x-www-form-urlencoded; charset=UTF-8"));
	curl_setopt($curl,CURLOPT_POST,1);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,false);
	curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
	$xml = curl_exec($curl); // executo o curl
	curl_close($curl); // fecha

	$xml = simplexml_load_string($xml);
	echo json_encode($xml);
	// var_dump($xml);