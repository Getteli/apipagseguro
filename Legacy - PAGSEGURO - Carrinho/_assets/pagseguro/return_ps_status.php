<?php
	header("access-control-allow-origin: https://pagseguro.uol.com.br"); // header pagseguro
	require_once("pagseguro.class.php"); // class do pagseguro padrao
	//Inclue o arquivo da conexao do banco

	// recebe via a api do pagseguro apos o pagamento
	if(isset($_POST['notificationType']) && $_POST['notificationType'] == 'transaction'){
		// inicia a classe do pagseguro padrao, e recebe o que e importante, o id/codigo, status e data
		$PagSeguro = new PagSeguro();
		$response = $PagSeguro->executeNotification($_POST);
		$status = $response->status;
		$reference = $response->reference;
		$data = $response->date;

		// ao receber o status, veja o que fazer de acordo com o tipo.
		switch ($status) {
			case 1:
				// PAGAMENTO PENDENTE
				echo $PagSeguro->getStatusText($PagSeguro->status);
				break;
			case 2:
				// PAGAMENTO PENDENTE
				echo $PagSeguro->getStatusText($PagSeguro->status);
				break;
			case 3:
			case 4:
			// PAGAMENTO CONFIRMADO
			// ATUALIZAR O STATUS NO BANCO DE DADOS
				break;
			case 5:
				// PAGAMENTO PENDENTE
				echo $PagSeguro->getStatusText($PagSeguro->status);
				break;
			case 6:
				// PAGAMENTO PENDENTE
				echo $PagSeguro->getStatusText($PagSeguro->status);
				break;
			case 7:
				// PAGAMENTO PENDENTE
				echo $PagSeguro->getStatusText($PagSeguro->status);
				break;
		}

	}else{
		// ERRO
	}

/*
status e seus tipos:
	0 - Pendente
	1 - Aguardando pagamento
	2 - Em análise
	3 - Pago
	4 - Disponível
	5 - Em disputa
	6 - Devolvida
	7 - Cancelada
*/