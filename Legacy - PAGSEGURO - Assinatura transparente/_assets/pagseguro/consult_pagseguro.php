<?php
	// charset
	header('Content-Type: text/html; charset=utf-8');
	// header('Content-Type: text/xml;');

	// chama a configuracao
	include("config.php");

	$Referencia = '83783783737';

	// GET
	$Url="https://ws.sandbox.pagseguro.uol.com.br/v2/transactions?email=".EMAIL_PAGSEGURO."&token=".TOKEN_SANDBOX."&reference=".$Referencia;

	$Curl = curl_init($Url);
	curl_setopt($Curl,CURLOPT_HTTPHEADER,array("Content-Type: application/x-www-form-urlencoded; charset=UTF-8"));
	curl_setopt($Curl,CURLOPT_SSL_VERIFYPEER,false);
	curl_setopt($Curl,CURLOPT_RETURNTRANSFER,true);
	$Retorno = curl_exec($Curl);
	curl_close($Curl);

	$Xml = simplexml_load_string($Retorno);

	// var_dump($Xml);
	// echo $Retorno;

	echo "Ordem decrescente (o mais recente para o mais antigo):<br />";
	// 90 dias para a devolucao do dinheiro
	// 120 dias para buscar o codigo da transacao, data mais antiga sera necessario verificar extrato da transacao na conta do pagseguro
	foreach($Xml->transactions as $Transactions){
		foreach($Transactions as $Transaction){
			if ( $Transaction->status == 6 || $Transaction->status == 7 || $Transaction->status == 8 ) {
			}else{
				echo "Código: <a href='consult_advanced_pagseguro.php?code=". $Transaction->code ."'>". $Transaction->code ."</a>";
				echo " | ";
				if ( $Transaction->status == 3 || $Transaction->status == 4 || $Transaction->status == 5 ) {
					echo "<a href='estorn_pagseguro.php?code=". $Transaction->code ."'>Estornar compra</a>";
				}else{
					echo "<a href='cancel_pagseguro.php?code=". $Transaction->code ."'>Cancelar compra</a>";
				}
				echo "<hr/>";
			}
		}
	}