<?php
	// charset
	header('Content-Type: text/html; charset=utf-8');
	// coxecao com o pagseguro, cria o pedido no pagseguro, usando o carrinho do sistema
	include_once('_assets/pagseguro/getprod_pagseguro.php');
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
		}
		ul{
			list-style-type: none;
		}
		.t{
			color: #a58f8f;
		}
		h4,h5{
			margin: 0;
		}
		.desc{
			list-style-type: circle;
			padding-left: 15px;
		}
		.desc > li{
			margin-bottom: 5px;
		}
	</style>
</head>
<body>

	<!-- descrição sobre essa modalidade -->
	<h4>PAGSEGURO</h4>
	<ul class="desc">
		<li><a class="t">Modalidade</a>: Carrinho</li>
		<li><a class="t">Tipo</a>: lightbox</li>
		<li><a class="t">Descrição</a>: O botão é do PagSeguro, mas o codigo (o produto, com seus atributos), vem do array/cache carrinho via php (com reference, nome, valor, descricao,..) e então o codigo gerado é colocado dentro do input (no form do PagSeguro)</li>
		<li><a class="t">Descrição</a>: Essa página seria um exemplo de página carrinho, onde verá a lista dos produtos e finalizará a compra. a variavel que armazena tudo do carrinho (cada atributo do produto) em array, vem pelo PHP, passa pelo script para gerar o codigo e é add no input. Se sair e add mais itens, ao voltar fará novamente.</li>
		<li><a class="t">OBS</a>: Para mais detalhes, informações, inspecione todo o código (html, php) e leia o info.txt</li>
	</ul>

	<hr />

	<!-- ----------------------------------------------------- PAGSEGURO FIXO ----------------------------------------------------- -->
		<!-- INICIO FORMULARIO BOTAO PAGSEGURO -->
		<form id="form_prod_pagseguro" action="https://sandbox.pagseguro.uol.com.br/checkout/v2/payment.html" method="post" onsubmit="PagSeguroLightbox(this); return false;">
		<!-- NÃO EDITE OS COMANDOS DAS LINHAS ABAIXO -->
		<input type="hidden" id="code_prod" name="code" value="<?php echo $code_ps ?>" /> <!-- value gerado do primeiro checkbox -->
		<input type="hidden" name="iot" value="button" />
		<input type="image" id="btn_pagseguro" <?php echo $disabled_btn_pg ?> src="https://stc.pagseguro.uol.com.br/public/img/botoes/pagamentos/120x53-comprar-roxo.gif" name="submit" alt="Pague com PagSeguro - é rápido, grátis e seguro!" />
		</form>
		<script type="text/javascript" src="https://stc.sandbox.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.lightbox.js"></script>
		<!-- FINAL FORMULARIO BOTAO PAGSEGURO -->

<!-- script -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js" type="text/javascript"></script>
<!-- script padrao criado por mim, do pagseguro -->
<!-- <script src="_assets/pagseguro/pagseguro.js" type="text/javascript" ></script> -->