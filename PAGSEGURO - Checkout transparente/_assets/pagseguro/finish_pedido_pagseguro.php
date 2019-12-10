<?php
	// chama a configuracao
	include("config.php");

	$TokenCard = $_POST['TokenCard'];
	$HashCard = $_POST['HashCard'];
	$QtdParcelas = filter_input(INPUT_POST,'QtdParcelas',FILTER_SANITIZE_SPECIAL_CHARS);
	$ValorParcelas = filter_input(INPUT_POST,'ValorParcelas',FILTER_SANITIZE_SPECIAL_CHARS);

// cartao
// $Data["email"]=EMAIL_PAGSEGURO;
// $Data["token"]=TOKEN_SANDBOX;
// $Data["reference"]="83783783737";

// $Data["paymentMode"]="default";
// $Data["paymentMethod"]="creditCard";
// $Data["receiverEmail"]=EMAIL_PAGSEGURO;
// $Data["currency"]="BRL";

// $Data["itemId1"] = 1;
// $Data["itemDescription1"] = 'Website';
// $Data["itemAmount1"] = '500.00';
// $Data["itemQuantity1"] = 1;

// $Data["notificationURL="]="https://www.meusite.com.br/notificacao.php";

// $Data["senderName"]='José Comprador';
// $Data["senderCPF"]='22111944785';
// $Data["senderAreaCode"]='37';
// $Data["senderPhone"]='99999999';
// $Data["senderEmail"]="c57980665160880589547@sandbox.pagseguro.com.br";
// $Data["senderHash"]=$HashCard;

// $Data["shippingAddressStreet"]='Av. Brig. Faria Lima';
// $Data["shippingAddressNumber"]='1384';
// $Data["shippingAddressComplement"]='5 Andar';
// $Data["shippingAddressDistrict"]='Jardim Paulistano';
// $Data["shippingAddressPostalCode"]='01452002';
// $Data["shippingAddressCity"]='Sao Paulo';
// $Data["shippingAddressState"]='SP';
// $Data["shippingAddressCountry"]="BRA";
// $Data["shippingType"]="1";
// $Data["shippingCost"]="0.00";
// $Data["creditCardToken"]=$TokenCard;
// $Data["installmentQuantity"]=$QtdParcelas;
// $Data["installmentValue"]=$ValorParcelas;
// $Data["noInterestInstallmentQuantity"]=2;
// $Data["creditCardHolderName"]='Jose Comprador';
// $Data["creditCardHolderCPF"]='22111944785';
// $Data["creditCardHolderBirthDate"]='27/10/1987';
// $Data["creditCardHolderAreaCode"]='37';
// $Data["creditCardHolderPhone"]='99999999';
// $Data["billingAddressStreet"]='Av. Brig. Faria Lima';
// $Data["billingAddressNumber"]='1384';
// $Data["billingAddressComplement"]='5 Andar';
// $Data["billingAddressDistrict"]='Jardim Paulistano';
// $Data["billingAddressPostalCode"]='01452002';
// $Data["billingAddressCity"]='Sao Paulo';
// $Data["billingAddressState"]='SP';
// $Data["billingAddressCountry"]="BRA";


// boleto
$Data["email"]=EMAIL_PAGSEGURO;
$Data["token"]=TOKEN_SANDBOX;
$Data["reference"]="83783783737";

$Data["paymentMode"]="default";
$Data["paymentMethod"]="boleto";
$Data["receiverEmail"]=EMAIL_PAGSEGURO;
$Data["currency"]="BRL";

$Data["senderName"]='José Comprador';
$Data["senderCPF"]='22111944785';
$Data["senderAreaCode"]='37';
$Data["senderPhone"]='99999999';
$Data["senderEmail"]="c57980665160880589547@sandbox.pagseguro.com.br";
$Data["senderHash"]=$HashCard;

$Data["itemId1"] = 1;
$Data["itemDescription1"] = 'Website';
$Data["itemAmount1"] = '500.00';
$Data["itemQuantity1"] = 1;

$Data["shippingAddressStreet"]='Av. Brig. Faria Lima';
$Data["shippingAddressNumber"]='1384';
$Data["shippingAddressComplement"]='5 Andar';
$Data["shippingAddressDistrict"]='Jardim Paulistano';
$Data["shippingAddressPostalCode"]='01452002';
$Data["shippingAddressCity"]='Sao Paulo';
$Data["shippingAddressState"]='SP';
$Data["shippingAddressCountry"]="BRA";
$Data["shippingType"]="1";
$Data["shippingCost"]="0.00";

	$BuildQuery = http_build_query($Data);
	// POST
	$Url = "https://ws.sandbox.pagseguro.uol.com.br/v2/transactions?";

	$Curl = curl_init($Url);
	curl_setopt($Curl,CURLOPT_HTTPHEADER,Array("Content-Type: application/x-www-form-urlencoded; charset=UTF-8"));
	curl_setopt($Curl,CURLOPT_POST,true);
	curl_setopt($Curl,CURLOPT_SSL_VERIFYPEER,false);
	curl_setopt($Curl,CURLOPT_RETURNTRANSFER,true);
	curl_setopt($Curl,CURLOPT_POSTFIELDS,$BuildQuery);
	$Retorno = curl_exec($Curl);
	curl_close($Curl);

	$Xml = simplexml_load_string($Retorno);
	var_dump($Xml);
	// echo "
	// <script>
	// window.location.href = '". $Xml->paymentLink ."'
	// </script>
	// ";