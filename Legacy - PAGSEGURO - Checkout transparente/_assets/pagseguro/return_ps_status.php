<?php
	header("access-control-allow-origin: https://pagseguro.uol.com.br"); // header pagseguro
	require_once("pagseguro.class.php"); // class do pagseguro
	//Inclue o arquivo da conexao do banco

	if(isset($_POST['notificationType']) && $_POST['notificationType'] == 'transaction'){
		$PagSeguro = new PagSeguro();
		$response = $PagSeguro->executeNotification($_POST);
		$status = $response->status;
		$reference = $response->reference;
		$data = $response->date;

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