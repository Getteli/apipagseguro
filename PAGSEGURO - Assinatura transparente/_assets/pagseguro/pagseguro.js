$(document).ready(function(){

	var Amount = 30.00; // preco

	// Iniciar a seção de pagamento
	function session_start(){
		$.ajax({
			url: "_assets/pagseguro/conection_session.php",
			type: 'POST',
			dataType: 'json',
			success:function(data){
				console.log(data.id);
				PagSeguroDirectPayment.setSessionId(data.id);
			},
			complete: function(data) {
				listaMeiosPagamento();
			}
		});
	}

	// Lista os pagamentos disponíveis no Pagseguro
	function listaMeiosPagamento(){
		PagSeguroDirectPayment.getPaymentMethods({
			amount: Amount,
			success: function(data) {
				$.each(data.paymentMethods.CREDIT_CARD.options, function(i, obj){
					$('.CartaoCredito').append("<div><img src='https://stc.pagseguro.uol.com.br/"+obj.images.SMALL.path+"'>"+obj.name+"</div>");
				});

				$('.Boleto').append("<div><img src='https://stc.pagseguro.uol.com.br/"+data.paymentMethods.BOLETO.options.BOLETO.images.SMALL.path+"'>"+data.paymentMethods.BOLETO.name+"</div>");

				$.each(data.paymentMethods.ONLINE_DEBIT.options, function(i, obj){
					$('.Debito').append("<div><img src='https://stc.pagseguro.uol.com.br/"+obj.images.SMALL.path+"'>"+obj.name+"</div>");
				});
			}
		});
	}

	// pega o numero do cartao, pelo digito do usuario
	$('#NumeroCartao').on('keyup',function(){
		var NumeroCartao = $(this).val(); // pega o input
		var QtdCaracteres = NumeroCartao.length; // pega o numero de caracter digitado

		// comeca a pesquisar no 6 digito
		if(QtdCaracteres == 6){
			PagSeguroDirectPayment.getBrand({ // funcao do pagseguro
				cardBin: NumeroCartao,
				success: function(response) {
					var BandeiraImg=response.brand.name;
					$('.BandeiraCartao').html("<img src='https://stc.pagseguro.uol.com.br/public/img/payment-methods-flags/42x20/"+BandeiraImg+".png'>")
					// chama a funcao para pegar as parcelas e valor
					getParcelas(BandeiraImg);
					getTokenCard();
				},
				error: function (response) {
					alert('Cartão não reconhecido');
					$('.BandeiraCartao').empty();
				}
			});
		}
	});

	//Exibe a quantidade e valores das parcelas
	function getParcelas(Bandeira){
		PagSeguroDirectPayment.getInstallments({
			amount: Amount, // preco
			maxInstallmentNoInterest: 2, // quantidade de parcelas sem juros
			brand: Bandeira, // bandeira do cartao
			success: function(response){
				$.each( response.installments,function( i,obj ){
					$.each( obj,function( i2,obj2 ){
						var NumberValue = obj2.installmentAmount;
						var Number = "R$ " + NumberValue.toFixed(2).replace(".",",");
						var NumberParcelas = NumberValue.toFixed(2);
						$('#QtdParcelas').show().append("<option value='"+ obj2.quantity +"' name='"+ NumberParcelas +"'>" + obj2.quantity + " parcela(s) de " + Number + "</option>");
					});
				});
			}
		});
	}

	// ao selecionar a parcela, pega o valor
	$("#QtdParcelas").on('change',function(){
	    value = $(this).find('option:selected').attr("name");
	    $("#ValorParcelas").val(value);
	});

	// Obter o token do cartao de credito
	function getTokenCard(){
		PagSeguroDirectPayment.createCardToken({
			cardNumber: '4111111111111111',
			brand: 'visa',
			cvv: '123',
			expirationMonth: '12',
			expirationYear: '2030',
			success: function(response){
				$('#TokenCard').val(response.card.token);
			}
		});
	}

// Chamar a funcao de getTokenCard

	// Pega o hash do cartao
	$("#btn_buy").on('click',function(event){
		event.preventDefault();
		PagSeguroDirectPayment.onSenderHashReady(function(response){
			$("#HashCard").val(response.senderHash);
			$('#Form1').submit();
		});
	});

	// funcoes executadas
	session_start(); // inicia a sessao do pedido no pagseguro
});