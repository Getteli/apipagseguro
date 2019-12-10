<?php
	// chama a configuracao
	// header('Content-Type: text/xml;');
	// header('Content-Type: text/html; charset=utf-8');
	include("config.php");

// cartao
// $data["email"] = EMAIL_PAGSEGURO;
// $data["token"] = TOKEN_PAGSEGURO;
// $data['preApprovalCharge'] = 'auto';
// $data["reference"] = 'plan_001';
// $data['preApprovalName'] = 'Super seguro para notebook';
// $data['preApprovalDetails'] = 'Todo dia 15 será cobrado o valor de R$150,00 para o seguro do notebook';
// $data['preApprovalAmountPerPayment'] = '150.00';
// $data['preApprovalPeriod'] = 'WEEKLY';
// $data['preApprovalDayOfWeek'] = 'MONDAY';
// $data['preApprovalInitialDate' ] = '2015-01-17T19:20:30.45+01:00';
// $data['preApprovalFinalDate'] = '2017-01-17T19:20:30.45+01:00';
// $data['preApprovalMaxAmountPerPeriod'] = '200.00';
// $data['preApprovalMaxTotalAmount'] = '900.00';

// $dados["email"] = EMAIL_PAGSEGURO;
// $dados["token"] = TOKEN_PAGSEGURO; pode ser feito tanto manual (informando o dia do mes, quanto automatico e deixar sem o dia do mes)
// no automatico a cobrança é feita pre pago, o pagseguro vai fazer a cobrança no dia em que foi comprado o serviço, vai ser debitado do cartao do cliente. no manual vc vai fazer a requisicao para cobrar o cliente e ser debitado do cartao dele, porem sera pos pago, no mes seguinte da compra é q vc cobra, e se passar do dia em q vc disse q seria a cobrança o pagseguro nao aceita.
$dados['reference']							= '04';
$dados['preApproval']['charge'] 			= 'auto';
$dados['preApproval']['name']				= 'Super seguro para notebook auto';
$dados['preApproval']['details']			= 'Todo dia 15 será cobrado o valor de R$150,00 para o seguro do notebook4';
$dados['preApproval']['amountPerPayment']	= '100.00';
$dados['preApproval']['period']				= 'MONTHLY';
// $dados['preApproval']['dayOfWeek']			= 'MONDAY';
// $dados['preApproval']['dayOfMonth']				= '05';
// $dados['preApproval']['initialDate']				= '2018-11-19T19:20:30.45+01:00';
// $dados['preApproval']['finalDate']				= '2019-01-15T19:20:30.45+01:00';
// $dados['preApproval']['maxTotalAmount']				= '300.00';
// $dados['preApproval']['']				= ;
// $dados['preApproval']['expiration']			= $this->expiracao; / opcional

// $data['preApprovalMember'] = '5';
// $data['reviewURL'] = 'http://sounoob.com.br/produto1';

	// $Data = http_build_query($dados);
	// POST
	// $Url = "https://ws.pagseguro.uol.com.br/pre-approvals/request?";
	$Url = "https://ws.pagseguro.uol.com.br/pre-approvals/request?email=".EMAIL_PAGSEGURO."&token=".TOKEN_PAGSEGURO."";
	// $Url = "https://ws.sandbox.pagseguro.uol.com.br/pre-approvals/request?email=".EMAIL_PAGSEGURO."&token=".TOKEN_SANDBOX."";

	$Curl = curl_init($Url);
	curl_setopt($Curl, CURLOPT_HTTPHEADER, array('Content-type: application/json;charset=UTF-8', 'Accept: application/vnd.pagseguro.com.br.v3+json;charset=ISO-8859-1'));
	curl_setopt($Curl,CURLOPT_POST,true);
	curl_setopt($Curl,CURLOPT_SSL_VERIFYPEER,false);
	curl_setopt($Curl,CURLOPT_RETURNTRANSFER,true);
	curl_setopt($Curl,CURLOPT_POSTFIELDS, json_encode($dados) );
	$Retorno = curl_exec($Curl);
	curl_close($Curl);

	// $Xml = simplexml_load_string($Retorno);
	// var_dump($Retorno);
	echo $Retorno;
	// var_dump($Xml);
	// echo $Xml;