<?php

namespace App\Payment;

use App\Assistant\Codes;
use App\Models\Financial\Financial;
use App\Models\Financial\MonthlyPayment;
use App\Models\Financial\Plan;
use App\Models\Usability\Campaign;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * Classe abstrata q vai gerar o boleto via pagseguro
 */
abstract class Boleto
{
	const Monthly = "monthly";
	const Method = "boleto";
	const Mode = "DEFAULT";

	/**
	 * gera o boleto ao criar uma campanha
	 *
	 * @param Financial $financial
	 * @param User $user
	 * @param Campaign $campaign
	 * @param Request $request
	 * @return stdClass
	 */
	public static function campaign(Financial $financial, User $user, Campaign $campaign, Request $request)
	{
		if (empty($financial) || empty($user) || empty($campaign))
		{
			return false;
		}

		$result = new \stdClass();

		try
		{
			$data = array();

			// via config
			$data["email"] = config('app.payment_email');
			$data["token"] = config('app.payment_token');
			$data['receiverEmail'] =  config('app.payment_email_shop');
			$data['currency'] = config('app.payment_currency');
			$data['itemQuantity1'] = config('app.payment_qtt');
			$data['notificationURL'] = config('app.payment_notification');
			// via code
			$data['reference'] = Codes::generateCode();

			// via request
			$data['itemDescription1'] = substr("Campan. {$campaign->name}, R$ {$request->amount},ate {$campaign->end_broadcast_at}", 0, 95);
			$data['senderHash'] = $request->hash_card;
			$data['itemAmount1'] = Codes::Currency(Codes::formatCurrencyNoSpace($request->amount));

			// via financial
			if ($user->Person()->exists())
			{
				$data['senderCPF'] = Codes::clean( $user->PersonOrLegal->cpf );
			}
			else
			{
				$data['senderCNPJ'] = Codes::clean( $user->PersonOrLegal->cnpj );
			}

			$data['shippingAddressRequired'] = $financial->address;

			// via constante
			$data['paymentMode'] = self::Mode;
			$data['paymentMethod'] = self::Method;

			// via campaign
			$data['itemId1'] = $campaign->id;

			// via user
			if (!empty($user->PersonOrLegal->cnpj) && !is_null($user->PersonOrLegal->cnpj))
			{
				$data['senderName'] = urldecode($user->PersonOrLegal->corporate_name);
			}
			else
			{
				$data['senderName'] = urldecode($user->first_name . " " . $user->last_name);
			}
			
			$data['senderAreaCode'] = $user->ddd;
			$data['senderPhone'] = $user->phone;
			$data['senderEmail'] = urldecode($user->email);

			$buildQuery = http_build_query($data);
			$url = config('app.payment_url')."transactions";

			$curl = curl_init($url);
			curl_setopt($curl, CURLOPT_HTTPHEADER, Array("Content-Type: application/x-www-form-urlencoded; charset=ISO-8859-1"));
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, !config('app.debug') );
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $buildQuery);
			$return = curl_exec($curl);
			curl_close($curl);
			$xml = @simplexml_load_string($return);

			if (isset($xml->error->message)) 
			{
				throw new \Exception($xml->error->message);
			}
			// se der false, força novamente
			elseif($xml == false)
			{
				return self::campaign($financial,$user,$campaign,$request);
			}

			$result->result = $xml;
			$result->data = $data;
			$result->status = Response::HTTP_CREATED;
			$result->is_done = true;

		}
		catch (\Throwable $e)
		{
			$result->result = $e->getMessage();
			$result->data = [];
			$result->status = Response::HTTP_INTERNAL_SERVER_ERROR;
			$result->is_done = false;
		}

		return $result;
	}

	/**
	 * gerar o boleto para pagar a mensalidade
	 *
	 * @param Plan $plan
	 * @param User $user
	 * @param Financial $financial
	 * @param Request $request
	 * @param MonthlyPayment $mp
	 * @return stdClass
	 */
	public static function monthly_payment(Plan $plan, User $user, Financial $financial, Request $request, MonthlyPayment $mp)
	{
		if (empty($financial) || empty($user) || empty($plan) || empty($mp) || empty($request))
		{
			return false;
		}

		$result = new \stdClass();

		try
		{
			$data = array();

			// via config
			$data["email"] = config('app.payment_email');
			$data["token"] = config('app.payment_token');
			$data['receiverEmail'] =  config('app.payment_email_shop');
			$data['currency'] = config('app.payment_currency');
			$data['itemQuantity1'] = config('app.payment_qtt');
			$data['notificationURL'] = config('app.payment_notification');
			// via code
			$data['reference'] = Codes::generateCode();

			// via request
			$data['itemDescription1'] = "Mensal. {$plan->name}, valor R$ {$plan->amount}";
			$data['senderHash'] = $request->hash_card;
			$data['itemAmount1'] = $plan->amount;

			// via financial
			if ($user->Person()->exists())
			{
				$data['senderCPF'] = Codes::clean( $user->PersonOrLegal->cpf );
			}
			else
			{
				$data['senderCNPJ'] = Codes::clean( $user->PersonOrLegal->cnpj );
			}

			$data['shippingAddressRequired'] = $financial->address;

			// via constante
			$data['paymentMode'] = self::Mode;
			$data['paymentMethod'] = self::Method;

			// via campaign
			$data['itemId1'] = $mp->id;

			// via user
			$data['senderName'] = urldecode($user->first_name . " " . $user->last_name);
			$data['senderAreaCode'] = $user->ddd;
			$data['senderPhone'] = $user->phone;
			$data['senderEmail'] = urldecode($user->email);

			$buildQuery = http_build_query($data);
			$url = config('app.payment_url')."transactions";

			$curl = curl_init($url);
			curl_setopt($curl, CURLOPT_HTTPHEADER, Array("Content-Type: application/x-www-form-urlencoded; charset=ISO-8859-1"));
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, !config('app.debug') );
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $buildQuery);
			$return = curl_exec($curl);
			curl_close($curl);
			$xml = @simplexml_load_string($return);

			if (isset($xml->error->message)) 
			{
				throw new \Exception($xml->error->message);
			}

			$result->result = $xml;
			$result->data = $data;
			$result->status = Response::HTTP_CREATED;
			$result->is_done = true;

		}
		catch (\Throwable $e)
		{
			$result->result = $e->getMessage();
			$result->data = [];
			$result->status = Response::HTTP_INTERNAL_SERVER_ERROR;
			$result->is_done = false;
		}

		return $result;
	}
}