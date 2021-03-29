<?php

namespace App\controllers;

use App\utils\Http;

class TransactionController extends Controller
{

	public function index()
	{
	}

	public function add()
	{
	}

	public function edit()
	{
	}

	public function delete()
	{
	}

	function paystackverify($ref)
	{
		$headers = array(
			"Authorization: Bearer sk_test_fbaf59877da4249e8bb7cdb429400c2f117aca6b",
			"Cache-Control: no-cache",
		);
		return Http::get("https://api.paystack.co/transaction/verify/" . $ref, $headers);
	}
}
