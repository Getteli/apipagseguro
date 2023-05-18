<?php
	// charset
	// header('Content-Type: text/xml;');
	header('Content-Type: text/html; charset=utf-8');

	// chama a configuracao
	include("config.php");

	$Code = filter_input(INPUT_GET,'code',FILTER_SANITIZE_SPECIAL_CHARS);

	// GET
	$Url = "https://ws.sandbox.pagseguro.uol.com.br/v3/transactions/". $Code ."?email=".EMAIL_PAGSEGURO."&token=".TOKEN_SANDBOX."";

	$Curl=curl_init($Url);
	curl_setopt($Curl,CURLOPT_SSL_VERIFYPEER,false);
	curl_setopt($Curl,CURLOPT_RETURNTRANSFER,true);
	$Retorno=curl_exec($Curl);
	curl_close($Curl);
	$Xml=simplexml_load_string($Retorno);

	// var_dump($Xml);
	// echo $Retorno;

	echo $Xml->sender->name.' <br />';
	echo "Pagamento: ";
	switch ( $Xml->status ) {
		case 1:
			echo 'Aguardando <br />';
			break;
		case 2:
			echo 'Em análise <br />';
			break;
		case 3:
		case 4:
			echo 'efetuado <br />';
			break;
		case 5:
			echo 'Em disputa <br />';
			break;
		case 6:
			echo 'Devolvido <br />';
			break;
		case 7:
			echo 'Cancelado <br />';
			break;
	}
	echo "Tipo: ";
	switch ( $Xml->paymentMethod->type ) {
		case 1:
			echo "Cartão de Crédito <br />";
			break;
		case 2:
			echo "Boleto <br />";
			break;
		case 3:
			echo "Débito online (TFE) <br />";
			break;
		case 4:
			echo "Saldo Pagseguro <br />";
			break;
		case 5:
			echo "Oi Paggo <br />";
			break;
		case 7:
			echo "Depósito em conta <br />";
			break;
	}
	echo "N° de parcelas: " . $Xml->installmentCount . "<br />";
	echo "Nessa transação você comprou os seguintes produtos: <br />";
	foreach ($Xml->items as $Item) {
		foreach ($Item as $Itens) {
			echo "Descrição: ".$Itens->description.' <br />';
			echo "Quantidade: ".$Itens->quantity.' <br />';
			echo "Valor: ".$Itens->amount.' <br />';
		}
	}
	echo "Preço Total: " . $Xml->grossAmount . "<br />";
	echo "Tipo de frete: ";
	switch ( $Xml->shipping->type ) {
		case 1:
			echo "Encomenda normal (PAC). <br />";
			break;
		case 2:
			echo "SEDEX <br />";
			break;
		case 3:
			echo "Tipo de frete não especificado. <br />";
			break;
	}