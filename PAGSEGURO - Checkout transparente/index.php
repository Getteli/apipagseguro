<?php
	// charset
	header('Content-Type: text/html; charset=utf-8');
	// coxecao com o pagseguro, cria o pedido no pagseguro, usando o carrinho do sistema
	// include_once('_assets/pagseguro/getprod_pagseguro.php');
?>
<!doctype html>
<html lang="pt-br">
<head>
	<title>API - PagSeguro - carrinho</title>
	<meta charset="utf-8" />
	<link rel="apple-touch-icon" sizes="76x76" href="">
	<link rel="icon" type="image/png" href="_assets/_img/favicon.png">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta name="theme-color" content="#4B4B4D"/>
	<meta property="og:locale" content="pt_BR">
	<meta property="og:url" content="index.php">
	<meta property="og:title" content="">
	<meta property="og:site_name" content="">
	<meta property="og:description" content="API PagSeguro - carrinho">
	<meta property="og:image" content="">
	<meta property="og:image:secure_url" content="">
	<meta property="og:image:type" content="image/jpeg">
	<meta property="og:image:width" content="200"> <!-- pixel -->
	<meta property="og:image:height" content="200"> <!-- pixel -->
	<meta name="description" content="API PagSeguro">
	<!-- /** CASO SEJA UM SITE NORMAL **/ -->
	<meta property="og:type" content="website">
	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
	<!-- mobile -->
	<meta name="apple-mobile-web-app-capable" content="yes"> 
	<meta name="mobile-web-app-capable" content="yes">
	<!-- estilo interno -->
	<style type="text/css">
		*{
			padding: 0;
			font-family: 'arial', sans-serif;
			font-weight: 400;
		}
		ul{
			list-style-type: none;
		}
		.CartaoCredito, .Debito , .Boleto{float:left; width: 30%; margin: 30px 1.5%; border-radius: 10px; border: 1px solid #999; font-size: 18px; font-weight: bold;}
		.Titulo{float:left; width: 100%; border-radius: 10px 10px 0 0; font-weight: bold; color: #fff; background: #000; text-align: center;}
		.DisplayNone{display: none;}
	</style>
</head>

<body>

<a href="_assets/pagseguro/consult_pagseguro.php">Consultar compras</a>
<hr />
<form id="Form1" name="Form1" method="post" action="_assets/pagseguro/finish_pedido_pagseguro.php">

    <input type="text" id="NumeroCartao" name="NumeroCartao">
    <div class="BandeiraCartao"></div>

	<select name="QtdParcelas" id="QtdParcelas" class="DisplayNone">
		<option value="">Selecione</option>
	</select>
    <input type="hidden" id="ValorParcelas" name="ValorParcelas">
	<input type="hidden" id="TokenCard" name="TokenCard">
	<input type="hidden" id="HashCard" name="HashCard">
	<button type="submit" id="btn_buy">Comprar</button>
</form>

<div class="CartaoCredito"><div class="Titulo">Cartão de Crédito</div></div>
<div class="Boleto"><div class="Titulo">Boleto</div></div>
<div class="Debito"><div class="Titulo">Débito Online</div></div>

<!-- script -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js" type="text/javascript"></script>
<script type="text/javascript" src= "https://stc.sandbox.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js"></script>
<!-- <script type="text/javascript" src= "https://stc.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js"></script> -->
<script src="_assets/pagseguro/pagseguro.js" type="text/javascript" defer ></script> 