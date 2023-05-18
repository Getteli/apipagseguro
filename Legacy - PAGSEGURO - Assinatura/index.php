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
		}
		ul{
			list-style-type: none;
		}
	</style>
</head>

<body>

	<!-- ----------------------------------------------------- PAGSEGURO FIXO ----------------------------------------------------- -->
		<!-- INICIO FORMULARIO BOTAO PAGSEGURO: NAO EDITE OS COMANDOS DAS LINHAS ABAIXO -->
		<form action="https://pagseguro.uol.com.br/pre-approvals/request.html" method="post">
		<input type="hidden" name="code" value="620B12DD3C3C413224DA9FB07D4453F6" />
		<input type="hidden" name="iot" value="button" />
		<input type="image" src="https://stc.pagseguro.uol.com.br/public/img/botoes/assinaturas/120x53-assinar-roxo.gif" name="submit" alt="Pague com PagSeguro - É rápido, grátis e seguro!" width="120" height="53" />
		</form>
		<!-- FINAL FORMULARIO BOTAO PAGSEGURO -->

<!-- script -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js" type="text/javascript"></script>
<!-- <script src="_assets/pagseguro/pagseguro.js" type="text/javascript" ></script>  -->