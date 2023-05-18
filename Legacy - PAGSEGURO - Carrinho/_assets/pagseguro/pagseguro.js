$(document).ready(function(){
	//var
	var preco_preview = document.getElementById("preco_preview");
	var btn_pagseguro = document.getElementById("btn_pagseguro");
	var btn0 = $('#radio_1');
	var btn1 = $('#radio_2');
	var btn2 = $('#radio_3');
	var input_code = $('#code_prod');
	// se clicar no radio #bm1
	btn0.click(function(){
		preco_preview.innerHTML = "10,00"; // altera o valor apresentado
		create_prod('premium', 1); // cria o produto
	});

	btn1.click(function(){
		preco_preview.innerHTML = "20,00";
		create_prod('premium', 2);
	});

	btn2.click(function(){
		preco_preview.innerHTML = "40,00";
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
				type: type, // tipo de pacote
				n: n // numero = tempo de pacote, conforme o configurado
			}),
			//SUCESS é referente a função que será executada caso
			//ele consiga ler a fonte de dados com sucesso.
			//O parâmetro dentro da função se refere ao nome da variável
			//que você vai dar para ler esse objeto.
			success: function(resposta){
				//Confere se houve erro e o imprime
				// alert(resposta.status);
				if(resposta == 'unauthorized' || resposta == "erro_dados" ){
					// trate o erro
					alert('erro faz o que tu quiser');
					return false;
				}else{
					//Agora basta definir os valores que você deseja preencher
					input_code.val(resposta); // popula o input do form do pagseguro, com o codigo do produto criado, com todos os seus atributos
					btn_pagseguro.removeAttribute('disabled');
				}
			}
		}); // fim ajax
	}
});