<?php

namespace App\controllers;

use App\middleware\Auth;
use App\utils\Http;
use App\utils\Response;
use App\utils\Sanitize;
use App\utils\Session;

class TransactionController extends Controller
{

	protected function transferRecipient($name, $accountnumber, $bankcode)
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

	protected function resolveBankDetails($accountnumber, $bankcode)
	{
		Auth::checkAuth("userid");
		$headers = array(
			"Authorization: Bearer sk_test_50b66536190e93b64a7ce87e9b6f80e98650c89c",
			"Cache-Control: no-cache",
		);
		return Http::get("https://api.paystack.co/bank/resolve?account_number=" . $accountnumber . "&bank_code=" . $bankcode, $headers);
	}

	public function getBeneficiaries()
	{
		try {
			Auth::checkAuth("userid");
			return $this->exec_query("SELECT A.recipient_id, A.accountnumber, A.accountname,A.bankcode,A.created_at, (SELECT bankname FROM banklist WHERE A.bankcode = banklist.bankcode) as bankname, (SELECT CONCAT(firstname,' ',lastname) FROM agents WHERE A.entity_id = agents.id) as agent, (SELECT companyname FROM client_profile WHERE A.entity_id = client_profile.client_id) as client, (SELECT CONCAT(firstname,' ',lastname) FROM users WHERE A.user_id = users.id) as creator  FROM `recipients` A");
		} catch (\Exception $e) {
			return $e->getMessage();
		}
	}

	function paystackverify($ref)
	{
		$headers = array(
			"Authorization: Bearer sk_test_50b66536190e93b64a7ce87e9b6f80e98650c89c",
			"Cache-Control: no-cache",
		);
		return Http::get("https://api.paystack.co/transaction/verify/" . $ref, $headers);
	}

	public function entities()
	{
		try {
			Auth::checkAuth("userid");
			$type = $this->query["type"];
			$data = [];
			switch ($type) {
				case "agents":
					$data = $this->findAll(["tablename" => "agents", "fields" => "id, CONCAT(firstname, ' ',lastname) as name"]);
					break;
				case "clients":;
					$data = $this->findAll(["tablename" => "client_profile", "fields" => "client_id as id, CONCAT(firstname, ' ',lastname) as name"]);
					break;
				default:
					$data = $this->findAll(["tablename" => "client_profile", "fields" => "client_id as id, CONCAT(firstname, ' ',lastname) as name"]);
					break;
			}
			exit(Response::json(["status" => true, "data" => $data]));
		} catch (\Exception $error) {
			exit(Response::json(["status" => false, "message" => $error->getMessage()]));
		}
	}

	public function account()
	{
		try {
			Auth::checkAuth("userid");
			$id = $this->query["id"];

			$account = $this->findOne([
				"tablename" => "client_account",
				"fields" => "bankcode, accountnumber",
				"condition" => "client_id =:id",
				"bindparam" => [":id" => $id]
			]);

			if (!$account) $account =  $this->findOne([
				"tablename" => "agent_account",
				"fields" => "bankcode, accountnumber",
				"condition" => "agent_id =:id",
				"bindparam" => [":id" => $id]
			]);

			if (!$account) throw new \Exception("account record not found");


			exit(Response::json(["status" => true, "data" => $account]));
		} catch (\Exception $error) {
			exit(Response::json(["status" => false, "message" => $error->getMessage()]));
		}
	}

	public function verifybankdetails()
	{
		try {
			$accountnumber = $this->body["accountnumber"];
			$bankcode = $this->body["bankcode"];

			$account = $this->resolveBankDetails($accountnumber, $bankcode);

			exit($account);
		} catch (\Exception $error) {
			exit(Response::json(["status" => false, "message" => $error->getMessage()]));
		}
	}

	public function savebeneficiary()
	{
		try {
			Auth::checkAuth("userid");
			$userid = Session::get("userid");
			$accountnumber = Sanitize::string($this->body["accountnumber"]);
			$bankcode = Sanitize::string($this->body["bankcode"]);
			$accountname = Sanitize::string($this->body["accountname"]);
			$entity = Sanitize::string($this->body["entity"]);

			$recepient = $this->findOne([
				"tablename" => "recipients",
				"condition" => "accountnumber = :accountnumber",
				"bindparam" => [":accountnumber" => $accountnumber]
			]);

			if ($recepient) throw new \Exception("Beneficiary with this accountnumber already exists");

			$beneficiary = json_decode($this->transferRecipient($accountname, $accountnumber, $bankcode));

			if (!$beneficiary->status) throw new \Exception("unable to save beneficiary");

			$recepientid = $beneficiary->data->recipient_code;
			$this->create([
				"tablename" => "recipients",
				"fields" => "`entity_id`, `recipient_id`, `bankcode`, `accountnumber`, `accountname`, `user_id`",
				"values" => ":entity,:recipientid,:bankcode,:accountnumber,:accountname,:userid",
				"bindparam" => [":entity" => $entity, ":recipientid" => $recepientid, ":bankcode" => $bankcode, ":accountnumber" => $accountnumber, ":accountname" => $accountname, ":userid" => $userid]
			]);

			exit(Response::json(["status" => true, "message" => $beneficiary->message]));
		} catch (\Exception $error) {
			exit(Response::json(["status" => false, "message" => $error->getMessage()]));
		}
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
