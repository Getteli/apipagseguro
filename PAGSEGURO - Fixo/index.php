<!doctype html>
<html lang="pt-br">
<head>
	<title>API - PagSeguro - fixo</title>
	<meta charset="utf-8" />
	<link rel="apple-touch-icon" sizes="76x76" href="">
	<link rel="icon" type="image/png" href="_assets/_img/favicon.png">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta name="theme-color" content="#4B4B4D"/>
	<meta property="og:locale" content="pt_BR">
	<meta property="og:url" content="index.php">
	<meta property="og:title" content="">
	<meta property="og:site_name" content="">
	<meta property="og:description" content="API PagSeguro - fixo">
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
		<li><a class="t">Modalidade</a>: Fixa</li>
		<li><a class="t">Tipo</a>: lightbox</li>
		<li><a class="t">Descrição</a>: O botão é do PagSeguro, mas o codigo (o produto, com seus atributos), é gerado via php (com reference, nome, valor, descricao,..) e então o codigo gerado é colocado dentro do input (no form do PagSeguro), via JS</li>
		<li><a class="t">Descrição</a>: No Javascript, ao clicar em cada tipo de button radio, vai gerar um novo produto, seus devidos valores.</li>
		<li><a class="t">OBS</a>: Para mais detalhes, informações, inspecione todo o código (html, php e js) e leia o info.txt</li>
	</ul>

	<hr />

	<div>
		<div>
			<ul>
				<li>&#x2714; VANTAGENS DO PACOTE.</li>
			</ul>
		</div>
		<h2>R$ <a id="preco_preview"></a></h2>
		<!-- 1 mes -->
		<div class="radio">
			<label>
				<input id="radio_1" required="required" value="1" name="pagamentom" type="radio" />
				1 mês
			</label>
		</div>
		<!-- 6 meses -->
		<div class="radio">
			<label>
				<input id="radio_2" required="required" value="2" name="pagamentom" type="radio" />
				6 meses
			</label>
		</div>
		<!-- 1 ano -->
		<div class="radio">
			<label>
				<input id="radio_3" required="required" value="3" name="pagamentom" type="radio" />
				12 meses
			</label>
		</div>
	</div>

	<!-- ----------------------------------------------------- PAGSEGURO FIXO ----------------------------------------------------- -->
	<!-- codigo gerado pelo site do pagseguro, um button configuravel e etc. inspecionar o info.txt -->
		<!-- INICIO FORMULARIO BOTAO PAGSEGURO -->
		<form id="form_prod_pagseguro" action="https://sandbox.pagseguro.uol.com.br/checkout/v2/payment.html" method="post" onsubmit="PagSeguroLightbox(this); return false;">
		<!-- NÃO EDITE OS COMANDOS DAS LINHAS ABAIXO -->
		<input type="hidden" id="code_prod" name="code" value="" /> <!-- aqui receberá o codigo novo vindo do javascript, ao escolher um novo pacote e gerar o seu codigo via php -->
		<input type="hidden" name="iot" value="button" />
		<input type="image" id="btn_pagseguro" disabled="disabled" src="https://stc.pagseguro.uol.com.br/public/img/botoes/pagamentos/120x53-comprar-roxo.gif" name="submit" alt="Pague com PagSeguro - é rápido, grátis e seguro!" />
		</form>
		<script type="text/javascript" src="https://stc.sandbox.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.lightbox.js"></script>
		<!-- FINAL FORMULARIO BOTAO PAGSEGURO -->

<!-- script -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js" type="text/javascript"></script>
<!-- script padrao criado por mim, do pagseguro -->
<script src="_assets/pagseguro/pagseguro.js" type="text/javascript" ></script>