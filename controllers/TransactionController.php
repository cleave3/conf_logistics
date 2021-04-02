<?php

namespace App\controllers;

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
}
