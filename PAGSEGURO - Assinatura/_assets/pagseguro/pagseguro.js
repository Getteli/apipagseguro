$(document).ready(function(){
	// se clicar no radio #bm1
	$('#radio_1').click(function(){
		document.getElementById("precom").innerHTML = "10,00"; // altera o valor apresentado
		create_prod('premium', 1); // cria o produto
	});

	$('#radio_2').click(function(){
		document.getElementById("precom").innerHTML = "20,00";
		create_prod('premium', 2);
	});

	$('#radio_3').click(function(){
		document.getElementById("precom").innerHTML = "40,00";
		create_prod('premium', 3);
	});

	// funcao que gera o produto via pagseguro, parametro type informando qual o tipo e o numero com o tempo
	function create_prod(type, n){
		$.ajax({
			//O campo URL diz o caminho de onde virá os dados
			//É importante concatenar o valor digitado no CNPJ
			url: '_assets/pagseguro/getprod_pagseguro.php',
			// Aqui você deve preencher o tipo de dados que será lido,
			type:'POST',
			data: ({
				type: type,
				n: n
			}),
			//SUCESS é referente a função que será executada caso
			//ele consiga ler a fonte de dados com sucesso.
			//O parâmetro dentro da função se refere ao nome da variável
			//que você vai dar para ler esse objeto.
			success: function(resposta){
				//Confere se houve erro e o imprime
				// alert(resposta.status);
				if(resposta == 'unauthorized' || resposta == "erro_dados" ){
					// resposta.message
					alert('erro faz o que tu quiser');
					return false;
				}else{
					//Agora basta definir os valores que você deseja preencher
					$('#code_prod').val(resposta);
				}
			}
		}); // fim ajax
	}
});