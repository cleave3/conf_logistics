<?php

namespace App\controllers;

use App\middleware\Auth;
use App\utils\Http;

class TransactionController extends Controller
{
	function paystackverify($ref)
	{
		$headers = array(
			"Authorization: Bearer sk_test_50b66536190e93b64a7ce87e9b6f80e98650c89c",
			"Cache-Control: no-cache",
		);
		return Http::get("https://api.paystack.co/transaction/verify/" . $ref, $headers);
	}

	function resolveBankDetails($accountnumber, $bankcode)
	{
		Auth::checkAuth("userid");
		$headers = array(
			"Authorization: Bearer sk_test_50b66536190e93b64a7ce87e9b6f80e98650c89c",
			"Cache-Control: no-cache",
		);
		return Http::get("https://api.paystack.co/bank/resolve?account_number=" . $accountnumber . "&bank_code=" . $bankcode, $headers);
	}

	function transferRecipient($name, $accountnumber, $bankcode)
	{
		Auth::checkAuth("userid");
		$headers = array(
			"Authorization: Bearer sk_test_50b66536190e93b64a7ce87e9b6f80e98650c89c",
			"Cache-Control: no-cache",
		);

		$fields = [
			'type' => "nuban",
			'name' => $name,
			'account_number' => $accountnumber,
			'bank_code' => $bankcode,
			'currency' => "NGN"
		];

		return Http::post("https://api.paystack.co/transferrecipient", $fields, $headers);
	}

	function initiateTransfer($amount, $recepient, $reason)
	{
		Auth::checkAuth("userid");
		$headers = array(
			"Authorization: Bearer sk_test_50b66536190e93b64a7ce87e9b6f80e98650c89c",
			"Cache-Control: no-cache",
		);

		$fields = [
			'source' => "balance",
			'amount' => $amount,
			'recipient' => $recepient,
			'reason' => $reason
		];

		return Http::post("https://api.paystack.co/transfer", $fields, $headers);
	}

	function finalizeTransfer($otp)
	{
		Auth::checkAuth("userid");
		$headers = array(
			"Authorization: Bearer sk_test_50b66536190e93b64a7ce87e9b6f80e98650c89c",
			"Cache-Control: no-cache",
		);

		$fields = [
			"transfer_code" => "TRF_vsyqdmlzble3uii",
			"otp" => $otp
		];

		return Http::post("https://api.paystack.co/transfer/finalize_transfer", $fields, $headers);
	}

	function webhook()
	{
		// only a post with paystack signature header gets our attention

		if ((strtoupper($_SERVER['REQUEST_METHOD']) != 'POST') || !array_key_exists('x-paystack-signature', $_SERVER))

			exit();

		// Retrieve the request's body

		$input = @file_get_contents("php://input");

		define('PAYSTACK_SECRET_KEY', 'sk_test_50b66536190e93b64a7ce87e9b6f80e98650c89c');

		// validate event do all at once to avoid timing attack

		if ($_SERVER['HTTP_X_PAYSTACK_SIGNATURE'] !== hash_hmac('sha512', $input, PAYSTACK_SECRET_KEY))

			exit();

		http_response_code(200);

		// parse event (which is json string) as object

		// Do something - that will not take long - with $event

		$event = json_decode($input);

		if ($event->event === "transfer.success") {
			// mark payment as paid
			// notify admin 
			// notify reciepient
		}



		exit();
	}
}
