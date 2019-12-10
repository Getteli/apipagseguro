<?php
	// chama a configuracao
	include("config.php");

	// header('Content-Type: text/xml;');
	// header('Content-Type: text/html; charset=utf-8');

	// POST
	$url = 'https://ws.sandbox.pagseguro.uol.com.br/v2/checkout?'; // remove o sandbox para a producao

	// var
	$disabled_btn_pg = 'disabled'; // ja inicia com o botao desativado do pagseguro
	$code_ps = ''; // var q armazena o codigo

	// gera uma variavel/array, que armazenara tudo
	$data = []; // cria o array
	$data['email'] = EMAIL_PAGSEGURO; // email
	$data['token'] = TOKEN_SANDBOX; // token
	$data['currency'] = 'BRL'; // tipo de moeda
	$data['reference'] = ''; // id do pedido

	// ----------------------------------------- EXEMPLO DE ARRAY COM ITENS --------------------------------------
	// Usa o $_SESSION['itens'] igual a logica do carrinho padrao
	// 0
	$_SESSION['itens'][0]['id'] = '01'; // id do produto
	$_SESSION['itens'][0]['qtd'] = '1'; // quantidade
	$_SESSION['itens'][0]['preco'] = '5.00'; // valor
	$_SESSION['itens'][0]['nome'] = 'teste'; // nome
	// 1
	$_SESSION['itens'][1]['id'] = '02';
	$_SESSION['itens'][1]['qtd'] = '4';
	$_SESSION['itens'][1]['preco'] = '1.50';
	$_SESSION['itens'][1]['nome'] = 'teste2';
// se existir itens faca
if (isset($_SESSION['itens'])) {
	// descompacta o array do carrinho
	foreach ($_SESSION['itens'] as $n => $produto) {
		$n += 1; // n = numero do array do item, add mais um pois o primeiro é 0 e pagseguro nao aceita
		// var descompactada do array
		$id = $produto['id']; // id do item
		$qtd = $produto['qtd']; // quantidade
		$preco = $produto['preco']; // preco. leva o preco unitario, pois o pagseguro vai multiplicar
		$nome = $produto['nome']; // nome
		// Abaixo apenas para teste pois e necessario usar o banco para pegar algum outro atributo necessario

		/*
		// pesquisa o produto pelo id para pegar algum atributo necessario, mas no exemplo nao terei banco
		$query_get = mysqli_query($conn, " SELECT 
			campos,
			FROM table
			WHERE id = '$id' ");
		$linha_carrinho = mysqli_fetch_assoc( $query_get );
		// var
		$nome_prod = $linha_carrinho['nome_prod'];
		$preco_prod = $linha_carrinho['preco_prod'] * $qtd;
		*/

		// array com itens (ps: concatena o n para adicionar + um item ao carrinho)
		$data['itemId'.$n] = $id; // id do produto
		$data['itemQuantity'.$n] = $qtd; // quantidade
		$data['itemDescription'.$n] = $nome; // nome - descricao
		$data['itemAmount'.$n] = $preco; // preco
	}
}
	// recebe o todo o array data e cria uma query unica usando a funcao nativa do php
	$data = http_build_query($data);
	// inicia o cURL
	$curl = curl_init($url);
	// parametros do cURL
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // false para TESTE, em producao coloque true
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
	$xml = curl_exec($curl); // executa

	curl_close($curl); // fecha
	// transforma o xml em string para a leitura e get's
	// obs: o simplexml_load_string para exibicao da erro, mas para continuar o codigo, funciona, o simplexml_load_string serve como um header, se nao tiver o header usa o simplexml_load_string, se nao tiver o simplexml_load_string usa o header
	// o @ é para remover o warning
	$xml = @simplexml_load_string($xml);

	// verifica erros e etc
	if ($xml) {
		$code_pre = $xml -> code; // add o code a uma var para o if
		// se tem code vai
		if ($code_pre) {
			// recebe o codigo e adicionar ao form para o btn de compra do pagseguro
			$code_ps = $xml -> code;
			$disabled_btn_pg = ''; // remove o disabled para ativar o botao de comprar
		}else if(count($xml -> error) > 0){
			// echo $xml ->error-> message // erro explicado
			echo 'erro_dados';
		}else{
			// trate o erro
			echo 'unauthorized!!';
		}
	}else{
		// trate o erro
		echo 'unauthorized!';
	}