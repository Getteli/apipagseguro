let cardPayment = document.querySelector('.card-payment');
let image_brand = document.querySelector('#image_brand');
let containerPayment = document.querySelector('#container_form_payment');
let form_payment = document.querySelectorAll('.form_payment');
let fp_boleto = document.querySelector('#Boleto');
let fp_cartao = document.querySelector('#Cartao');
let gateway_payment = document.querySelectorAll('.gateway-type-payment');
let hash_card = document.querySelector('#hash_card');
let token_card = document.querySelector('#token_card');
let data_financial = hash_card?.dataset.financial ?? null;
let container_card_payment = document.querySelector('#container_card_payment');
let container_pagseguro = document.querySelector('.cg-pagseguro');
let amount = document.querySelector('#amount-to-pay').value;
let idObjectCampaign = document.querySelector('#id-object-campaign')?.value;
let btn_pay_backend = document.querySelector('#btn_pay_backend');
// inputs do cartao
let num_card = document.querySelector('#num_card');
let brand_card = document.querySelector('#brand_card');
let cvv_card = document.querySelector('#cvv_card');
let month_card = document.querySelector('#month_card');
let year_card = document.querySelector('#year_card');

const Methods_Payment =
{
	setGatewayPayment: (type) =>
	{
		// pagseguro
		if (type == 1)
		{
			container_pagseguro.classList.remove('d-none');
			// se selecionou o pagseguro, cria a sessao
			// Payment_Pagseguro.createSession();
			Payment_Pagseguro.toggleButtonPay();

			// verifica qual a forma de pagamento foi escolhida
			form_payment.forEach(function(radio)
			{
				if (radio.checked)
				{
					Methods_Payment.setTypePayment(radio.value);
				}
			});
		}

	},
	/**
	 * metodo que vai verificar qual a forma de pagamento escolhida, e fazer o que tiver de fazer
	 * @param {*} type
	 */
	setTypePayment: (type) =>
	{
		if (!Payment_Pagseguro.sessionHasBeenCreated)
		{
			// Payment_Pagseguro.createSession();
		}

		setTimeout(() => {
			// é cartao
			if (type == 1)
			{
				container_card_payment.classList.remove('d-none');
				container_card_payment.removeAttribute("disabled");
				// se ja tiver os valores preenchidos, cria o token
				Payment_Pagseguro.createTokenCard(data_financial);	
			}
			else // boleto
			{
				Payment_Pagseguro.toggleButtonPay();
				container_card_payment?.classList.add('d-none');
				// bloqueia
				container_card_payment?.setAttribute("disabled","disabled");
			}
		}, 1000);
	},
	/**
	 * metodo que verifica se os dados necessarios para buscar dados sobre o cartão, foram preenchidos
	 * @returns boolean
	 */
	checkCardFull: () =>
	{
		// só vai passar se todos os campos citados estao preenchidos
		if ((num_card.value != "" && num_card.value != null && num_card.value.length > 13) &&
		(brand_card.value != "" && brand_card.value != null) &&
		(cvv_card.value != "" && cvv_card.value != null && cvv_card.value.length == 3) &&
		(month_card.value != "" && month_card.value != null && month_card.value.length == 2) &&
		(year_card.value != "" && year_card.value != null && year_card.value.length == 4))
		{
			return true;
		}

		return false;
	},
	/**
	 * exibe ou remove o card de pagamento
	 * @param {boolean} on 
	 */
	toggleCard: (on = true) =>
	{
		if (on) // exibe
		{
			cardPayment.style.display = "block";
			containerPayment.setAttribute('required','required');
			containerPayment.removeAttribute('disabled');
		}
		else // remove
		{
			cardPayment.style.display = "none";
			containerPayment.removeAttribute('required');
			containerPayment.setAttribute('disabled','disabled');
		}
	},
	/**
	 * Metodo que vai remover ou adicionar o required dos campos que possuem
	 * @param {bool} show
	 */
	toggleRequired: (show = false) =>
	 {
		let tagNames = ['input'];  // Insert other tag names here
		let elements = [];
	
		for (var i in tagNames)
		{
			elements = elements.concat([].slice.call(containerPayment.getElementsByTagName(tagNames[i])));
		}
	
		// inputs que podemos pular
		elements.forEach(input =>
		{
			// se sim, exibe
			if(show)
			{
				input.setAttribute('required','required');
			}
			else // se nao, remove
			{
				input.removeAttribute('required');	
			}
		});
	},
};

const Payment_Pagseguro =
{
	sessionHasBeenCreated: false, // variavel que guarda se a sessao foi criada
	/**
	 * metodo que cria a sessão no pagseguro
	 * @param {string} url_payment_session 
	 */
	createSession : () =>
	{
		let request = new Request(url_payment_session,
		{
			method: "POST",
			contentType: "application/xml; charset=utf-8",
			crossDomain: true,
			headers: {
				"Access-Control-Allow-Origin": "*",
				"Access-Control-Allow-Methods": "GET,POST,OPTIONS,DELETE,PUT"
			},
		});

		fetch(request)
		.then(function(response)
		{
			response.json()
			.then(function(result)
			{
				if (result)
				{
					if (typeof result.id == "undefined") return;
					if (typeof PagSeguroDirectPayment == "undefined") return;

					PagSeguroDirectPayment.setSessionId(result.id);

					Payment_Pagseguro.sessionHasBeenCreated = true;

					Payment_Pagseguro.createHashCard();

					if(!hash_card.dataset.viadmin)
						Payment_Pagseguro.createTokenCard(data_financial);
				}
				else
				{
					// refaz
					// Payment_Pagseguro.createSession();
					// Payment_Pagseguro.catchError(result.error, result.errors);
					// alert("Erro ao abrir sessão, caso for adicionar um cartão de crédito, reinicia a página. Se necessário entre em contato com nosso suporte, abrindo um chamado por favor.", "error");
				}
			})
		}).catch(function(err)
		{
			// Payment_Pagseguro.createSession();
			// Payment_Pagseguro.catchError(err.error, err.errors);
		});
	},
	/**
	 * metodo que cria o token do cartao, necessario que os dados do cartao estejam preenchidos
	 * @param {*} data_financial 
	 * @returns 
	 */
	createTokenCard : (data_financial = null) =>
	{
		// variaveis
		let vnum_card, vbrand_card, vcvv_card, vmonth_card, vyear_card;

		// se algum dos dados necessarios, nao for preenchido, volta
		if (!Methods_Payment.checkCardFull()) return;

		if (data_financial !== null && data_financial !== "" && data_financial !== undefined)
		{
			console.log(data_financial);
			// decode base64 data
			let data = window.atob(data_financial);
			data = JSON.parse(data.replace(/&quot;/g,'"'));
			vnum_card = data.num_card;
			vbrand_card = data.brand_card;
			vcvv_card = data.cvv_card;
			vmonth_card = data.month_card;
			vyear_card = data.year_card;
		}
		else
		{
			vnum_card = num_card.value;
			vbrand_card = brand_card.value;
			vcvv_card = cvv_card.value;
			vmonth_card = month_card.value;
			vyear_card = year_card.value;
		}

		PagSeguroDirectPayment.createCardToken({
			cardNumber: vnum_card, // Número do cartão de crédito
			brand: vbrand_card, // Bandeira do cartão
			cvv: vcvv_card, // CVV do cartão
			expirationMonth: vmonth_card, // Mês da expiração do cartão
			expirationYear: vyear_card, // Ano da expiração do cartão, é necessário os 4 dígitos.
			success: function (result)
			{
				token_card.value = result.card.token;
				Payment_Pagseguro.toggleButtonPay();
			},
			error: function (result)
			{
				Payment_Pagseguro.createTokenCard(data_financial);
				// Payment_Pagseguro.catchError(result.error, result.errors);
				// alert("erro ao gerar token para o seu cartão. tente novamente ou verifique com o suporte. É indicado não continuar a criar a campanha. Salve o rascunho.", "error");
			},
		});
	},
	/**
	 * metodo que cria o hash para pagamento. É necessário ja ter uma sessão em aberto e etc..
	 */
	createHashCard : () =>
	{
		PagSeguroDirectPayment.onSenderHashReady(function (result)
		{
			if (typeof result == "undefined" || typeof result == undefined || !result)
			{
				Payment_Pagseguro.createHashCard();
			}
			else if (result.status == "success")
			{
				if(hash_card)
				{
					hash_card.value = result.senderHash;
				}
				Payment_Pagseguro.toggleButtonPay();
			}
			else
			{
				Payment_Pagseguro.createHashCard();
			}
		});
	},
	/**
	 * metodo que busca a bandeira do cartao, mas é necessario no minimo 6 digitos do cartao
	 * @param {string} num_card 
	 */
	getBrand : (num_card) =>
	{
		//Instanciar a API do PagSeguro para validar o cartão
		PagSeguroDirectPayment.getBrand({

			cardBin: num_card,
			success: function(result)
			{
				// preenche o input
				let brand = result.brand.name;
				brand_card.value = brand;
				// adiciona a imagem para o usuario
				let image = "https://stc.pagseguro.uol.com.br/public/img/payment-methods-flags/42x20/" + brand + ".png";
				image_brand.setAttribute("src",image);
				image_brand.classList.remove('d-none');
			},
			error: function(response)
			{
				Payment_Pagseguro.catchError(response.error, response.errors);
				alert("erro ao buscar a bandeira do cartão, recarregue a página e tente novamente, se o error persistir abra um chamado por favor.", "error");
				Payment_Pagseguro.clearBrand();
			},
			complete: function(response)
			{
				return;
			}
		});
	},
	/**
	 * metodo que ao digitar no campo numero do cartao, verifica se pode buscar a bandeira, ou se remove a bandeira
	 * @param {*} e 
	 */
	checkBrand: (e) =>
	{
		let num_card = e.target.value;
		let qtd = num_card.length;
		if (qtd >= 7 && brand_card != null)
		{
			Payment_Pagseguro.getBrand(num_card);
		}
		else if (qtd >= 9 && qtd <= 14)
		{
			Payment_Pagseguro.clearBrand();
		}
	},
	/**
	 * metodo que limpa os campos da bandeira do cartão
	 */
	clearBrand: () =>
	{
		if (brand_card !== null && brand_card !== undefined)
		{
			brand_card.value = "";
			image_brand.setAttribute("src","");
			image_brand.classList.add('d-none');
			return;
		}
	},
	/**
	 * metodo que verifica se pode liberar o botao de pagar
	 */
	toggleButtonPay: () =>
	{
		// se tiver sessao
		// if (Payment_Pagseguro.sessionHasBeenCreated)
		// {
			// se for boleto
			if (fp_boleto.checked)
			{
				if(hash_card.value)
				{
					btn_pay_backend.removeAttribute('disabled');
					btn_pay_backend.classList.remove('disabled');
					btn_pay_backend.innerHTML = btn_pay_backend.dataset.textpay;
				}
				else
				{
					btn_pay_backend.innerHTML = btn_pay_backend.dataset.textwaiting;
				}
			}

			// se for cartao
			if (fp_cartao.checked)
			{
				if (hash_card.value && token_card.value)
				{
					btn_pay_backend.removeAttribute('disabled');
					btn_pay_backend.classList.remove('disabled');
					btn_pay_backend.innerHTML = btn_pay_backend.dataset.textpay;
				}
				else
				{
					btn_pay_backend.innerHTML = btn_pay_backend.dataset.textwaiting;
				}
			}
		// }
		// else
		// {
		// 	btn_pay_backend.setAttribute('disabled', 'disabled');
		// 	btn_pay_backend.classList.add('disabled');
		// 	btn_pay_backend.innerHTML = btn_pay_backend.dataset.textselect;

		// 	// Payment_Pagseguro.createSession();

		// 	setTimeout(() => {
		// 		Payment_Pagseguro.toggleButtonPay();
		// 	}, 1500);
		// }
	},
	/**
	 * metodo que cria o token do cartão, mas é necessario enviar os dados financeiro
	 * @param {*} data_financial 
	 * @returns 
	 */
	/**
	 * metodo que pega o erro que vem do pagseguro e exibe para o front
	 * @param {*} isError 
	 * @param {*} errors 
	 */
	catchError: (isError, errors) =>
	{
		// se tiver sido erro msm
		if (isError)
		{
			// array para preencher os erros
			let erros = [];

			for(var i in errors)
			{
				erros.push([i,errors[i]]);
			}

			erros.forEach(err =>
			{
				alert("erro ("+err[0]+"): " + err[1] + ". Se necessário consulte o suporte para mais informação", "error");
			});
		}
	},
};

// inputs para ao digitar tentar criar o token
num_card?.addEventListener('keydown', function (e)
{
	Payment_Pagseguro.checkBrand(e);
	Payment_Pagseguro.createTokenCard(data_financial);
});

cvv_card?.addEventListener('keyup', function(e)
{
	Payment_Pagseguro.createTokenCard(data_financial);
});

month_card?.addEventListener('keyup', function(e)
{
	Payment_Pagseguro.createTokenCard(data_financial);
});

year_card?.addEventListener('keyup', function(e)
{
	Payment_Pagseguro.createTokenCard(data_financial);
});

// no click, mudar o valor e o conteudo do gateway de pagamento escolhido
gateway_payment.forEach(function(radio_gwp)
{
	radio_gwp.addEventListener('change', function(e)
	{
		e.preventDefault();
		Methods_Payment.setGatewayPayment(e.target.value);
	})

	// dispara no evento em que o elemento esta marcado, ao entrar na pagina.
	if(radio_gwp.checked)
	{
		radio_gwp.dispatchEvent(new Event('change'));
	}
});

// no click, mudar o valor e o conteudo da forma de pagamento
form_payment.forEach(function(radio)
{
	radio.addEventListener('change', function(e)
	{
		e.preventDefault();
		Methods_Payment.setTypePayment(e.target.value);
	})

	// dispara no evento em que o elemento esta marcado, ao entrar na pagina.
	if(radio.checked)
	{
		radio.dispatchEvent(new Event('change'));
	}
});

setTimeout(() =>
{
	// se depois de 2s o botao ainda nao ta liberado, tenta novamente
	if (btn_pay_backend.classList.contains('disabled'))
	{
		Payment_Pagseguro.toggleButtonPay();
	}
}, 2000);
