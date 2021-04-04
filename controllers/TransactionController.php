<?php

namespace App\controllers;

use App\config\DotEnv;
use App\middleware\Auth;
use App\utils\Http;
use App\utils\Response;
use App\utils\Sanitize;
use App\utils\Session;


class TransactionController extends Controller
{
	protected function pk()
	{
		(new DotEnv(__DIR__ . '/../.env'))->load();
		return getenv("SECRET_KEY");
	}

	protected function sk()
	{
		return [
			"Authorization: Bearer " . $this->pk(),
			"Cache-Control: no-cache",
		];
	}

	protected function transferRecipient($name, $accountnumber, $bankcode)
	{
		Auth::checkAuth("userid");

		$fields = [
			'type' => "nuban",
			'name' => $name,
			'account_number' => $accountnumber,
			'bank_code' => $bankcode,
			'currency' => "NGN"
		];

		return Http::post("https://api.paystack.co/transferrecipient", $fields, $this->sk());
	}

	protected function resolveBankDetails($accountnumber, $bankcode)
	{
		Auth::checkAuth("userid");
		return Http::get("https://api.paystack.co/bank/resolve?account_number=" . $accountnumber . "&bank_code=" . $bankcode, $this->sk());
	}

	protected function registerTransaction($entityid, $type, $reference, $credit, $debit, $description, $initiator, $status = "complete")
	{
		return $this->create([
			"tablename" => "transactions",
			"fields" => " `entity_id`, `type`, `reference`, `credit`, `debit`, `description`,`initiator`,`status`",
			"values" => ":entityid,:type,:reference,:credit,:debit,:description,:initiator,:status",
			"bindparam" => [":entityid" => $entityid, ":type" => $type, ":reference" => $reference, ":credit" => $credit, ":debit" => $debit, ":description" => $description, ":initiator" => $initiator, ":status" => $status]
		]);
	}

	protected function updateTransactionStatus($reference, $status)
	{
		$transaction = $this->findOne([
			"tablename" => "transactions",
			"condition" => "reference = :reference",
			"bindparam" => [":reference" => $reference]
		]);

		if (!$transaction) throw new \Exception("Transaction not found");

		return $this->update([
			"tablename" => "transactions",
			"fields" => "`status`=:status",
			"condition" => "reference =:reference",
			"bindparam" => [":reference" => $reference, ":status" => $status]
		]);
	}

	protected function getRecipient($id)
	{
		Auth::checkAuth("userid");

		return $this->findOne([
			"tablename" => "recipients A",
			"condition" => "A.entity_id = :id AND C.status IN ('complete', 'verified')",
			"fields" => "A.bankcode, A.accountnumber, A.accountname, A.recipient_id, A.entity_id, B.bankname, (SUM(C.credit) - SUM(C.debit)) as balance",
			"joins" => "INNER JOIN banklist B ON A.bankcode = B.bankcode LEFT JOIN transactions C ON A.entity_id = C.entity_id",
			"bindparam" => [":id" => $id]
		]);
	}

	protected function initiateTransfer($amount, $recepient, $reason)
	{
		Auth::checkAuth("userid");

		$fields = [
			'source' => "balance",
			'amount' => $amount,
			'recipient' => $recepient,
			'reason' => $reason
		];

		return Http::post("https://api.paystack.co/transfer", $fields, $this->sk());
	}

	protected function finalizeTransfer($otp, $tcode)
	{
		Auth::checkAuth("userid");

		$fields = [
			"transfer_code" => $tcode,
			"otp" => $otp
		];

		return Http::post("https://api.paystack.co/transfer/finalize_transfer", $fields, $this->sk());
	}

	public function getBeneficiaries()
	{
		try {
			Auth::checkAuth("userid");
			return $this->exec_query("SELECT A.entity_id, A.recipient_id, A.accountnumber, A.accountname,A.bankcode,A.created_at, (SELECT bankname FROM banklist WHERE A.bankcode = banklist.bankcode) as bankname, (SELECT CONCAT(firstname,' ',lastname) FROM agents WHERE A.entity_id = agents.id) as agent, (SELECT companyname FROM client_profile WHERE A.entity_id = client_profile.client_id) as client, (SELECT CONCAT(firstname,' ',lastname) FROM users WHERE A.user_id = users.id) as creator  FROM `recipients` A");
		} catch (\Exception $e) {
			return $e->getMessage();
		}
	}

	function paystackverify($ref)
	{
		return Http::get("https://api.paystack.co/transaction/verify/" . $ref, $this->sk());
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

	public function recipientdetail()
	{
		try {
			Auth::checkAuth("userid");
			$id = $this->query["id"];

			$account = $this->getRecipient($id);

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

	public function addtransaction()
	{
		try {
			Auth::checkAuth("userid");
			$entityid = Sanitize::string($this->body["entity"]);
			$types = $this->body["type"];
			$reference = "CONF/" . date("YmdHms");
			$amount = $this->body["amount"];
			$description = $this->body["description"];

			if (count($amount) < 1 || count($description) < 1 || count($types) < 1) throw new \Exception("incompleted parameters");

			for ($i = 0; $i < count($amount); $i++) {
				$debit = in_array($types[$i], ['delivery_charge', 'waybill_charge', 'other_debit', 'payment']) ? Sanitize::integer($amount[$i]) : 0;
				$credit = in_array($types[$i], ['delivered_order', 'other_credit']) ? Sanitize::integer($amount[$i]) : 0;
				$type = $types[$i];
				$desc = $description[$i];
				$ref = $reference . "/" . strtoupper($type);
				$initiator = Session::get("username");

				$this->registerTransaction($entityid, $type, $ref, $credit, $debit, $desc, $initiator);
			}

			exit(Response::json(["status" => true, "message" => "Transaction submitted successfully"]));
		} catch (\Exception $error) {
			exit(Response::json(["status" => false, "message" => $error->getMessage()]));
		}
	}

	public function getClientBalances()
	{
		Auth::checkAuth("userid");
		return $this->exec_query("SELECT DISTINCT A.entity_id, (SUM(A.credit)-SUM(A.debit)) as balance, B.telephone as sellertelephone, C.companyname as seller FROM `transactions` A INNER JOIN clients B ON A.entity_id = B.id INNER JOIN client_profile C ON B.id = C.client_id WHERE A.entity_id IN (SELECT id FROM clients) AND A.status IN ('complete', 'verified') GROUP BY A.entity_id");
	}

	public function getClienPayments()
	{
		Auth::checkAuth("clientid");
		$clientid = Session::get("clientid");
		return $this->exec_query("SELECT * FROM transactions WHERE type = 'payment' AND entity_id = '$clientid'");
	}

	public function getAgentBalances()
	{
		Auth::checkAuth("userid");
		return $this->exec_query("SELECT DISTINCT A.entity_id, (SUM(A.credit)-SUM(A.debit)) as balance, B.telephone, CONCAT(firstname, ' ', lastname) as agentname FROM `transactions` A INNER JOIN agents B ON A.entity_id = B.id  WHERE A.entity_id IN (SELECT id FROM agents) AND A.status IN ('complete', 'verified') GROUP BY A.entity_id");
	}

	public function verifypayment()
	{
		try {
			Auth::checkAuth("clientid");
			$reference = Sanitize::string($this->query["reference"]);

			$this->updateTransactionStatus($reference, "verified");

			exit(Response::json(["status" => true, "message" => "payment verified successfully"]));
		} catch (\Exception $error) {
			exit(Response::json(["status" => false, "message" => $error->getMessage()]));
		}
	}

	public function search()
	{
		try {
			Auth::checkAuth("userid");
			$status = $this->query["status"] === "all" ? "%" : Sanitize::string($this->query["status"]);
			$startdate = empty($this->query["startdate"]) ? "2000-01-01 00:00:00" : trim($this->query["startdate"]) . " 00:00:00";
			$enddate = empty($this->query["enddate"]) ? date("Y-m-d") . " 23:59:59" : trim($this->query["enddate"]) . " 23:59:59";
			$target = $this->query["target"];
			$type = $this->query["type"] === "all" ? "%" : Sanitize::string($this->query["type"]);

			if ($target === "all") {
				$transactions = $this->exec_query("SELECT A.*, (SELECT CONCAT(firstname,' ',lastname) FROM agents WHERE A.entity_id = agents.id) as agent, (SELECT companyname FROM client_profile WHERE A.entity_id = client_profile.client_id) as client FROM transactions A WHERE status LIKE '%$status%' AND type LIKE '%$type%' AND created_at BETWEEN '$startdate' AND '$enddate'");
			} else {
				$transactions = $this->exec_query("SELECT A.*, (SELECT CONCAT(firstname,' ',lastname) FROM agents WHERE A.entity_id = agents.id) as agent, (SELECT companyname FROM client_profile WHERE A.entity_id = client_profile.client_id) as client FROM transactions A WHERE status LIKE '%$status%' AND type LIKE '%$type%' AND created_at BETWEEN '$startdate' AND '$enddate' AND entity_id = '$target'");
			}
			exit(Response::json(["status" => true, "data" => $transactions, "message" => count($transactions) . " results found for search"]));
		} catch (\Exception $error) {
			exit(Response::json(["status" => false, "data" => [], "message" => $error->getMessage()]));
		}
	}

	public function payments()
	{
		try {
			Auth::checkAuth("userid");
			$status = $this->query["status"] === "all" ? "%" : Sanitize::string($this->query["status"]);
			$startdate = empty($this->query["startdate"]) ? "2000-01-01 00:00:00" : trim($this->query["startdate"]) . " 00:00:00";
			$enddate = empty($this->query["enddate"]) ? date("Y-m-d") . " 23:59:59" : trim($this->query["enddate"]) . " 23:59:59";
			$target = $this->query["target"];

			if ($target === "all") {
				$transactions = $this->exec_query("SELECT A.*, (SELECT CONCAT(firstname,' ',lastname) FROM agents WHERE A.entity_id = agents.id) as agent, (SELECT companyname FROM client_profile WHERE A.entity_id = client_profile.client_id) as client FROM transactions A WHERE status LIKE '%$status%' AND type = 'payment' AND created_at BETWEEN '$startdate' AND '$enddate'");
			} else {
				$transactions = $this->exec_query("SELECT A.*, (SELECT CONCAT(firstname,' ',lastname) FROM agents WHERE A.entity_id = agents.id) as agent, (SELECT companyname FROM client_profile WHERE A.entity_id = client_profile.client_id) as client FROM transactions A WHERE status LIKE '%$status%' AND type = 'payment' AND created_at BETWEEN '$startdate' AND '$enddate' AND entity_id = '$target'");
			}
			exit(Response::json(["status" => true, "data" => $transactions, "message" => count($transactions) . " results found for search"]));
		} catch (\Exception $error) {
			exit(Response::json(["status" => false, "data" => [], "message" => $error->getMessage()]));
		}
	}

	public function inittransfer()
	{
		try {
			Auth::checkAuth("userid");
			$id = Sanitize::string($this->body["entity"]);
			$amount = Sanitize::integer($this->body["amount"]);
			$initiator = Session::get("username");
			$purpose = Sanitize::string($this->body["purpose"]);

			$recepient = $this->getRecipient($id);

			if (!$recepient) throw new \Exception("Recipient not found");

			if ($amount > floatval($recepient["balance"])) throw new \Exception("Amount exceeds recipients outstanding balance");


			$transfer = json_decode($this->initiateTransfer($amount, $recepient["recipient_id"], $purpose));

			if (!$transfer->status) throw new \Exception("failed to initiate transfer");

			if (!in_array($transfer->data->status, ["success", "otp"])) throw new \Exception("transfer initiation unsuccessful");

			if ($transfer->data->status === "success") {
				$ref = $transfer->data->transfer_code;
				$this->registerTransaction($id, "payment", $ref, 0, $amount, $purpose, $initiator, "pending");
			}

			exit(Response::json($transfer));
		} catch (\Exception $error) {
			exit(Response::json(["status" => false, "data" => [], "message" => $error->getMessage()]));
		}
	}

	public function completetransfer()
	{
		try {
			Auth::checkAuth("userid");
			$otp = Sanitize::string($this->body["otp"]);
			$code = Sanitize::integer($this->body["transfercode"]);
			if (!$otp) throw new \Exception("otp is required");

			$transfer = json_decode($$this->finalizeTransfer($otp, $code));

			if ($transfer->data->status === "success") {
				$id = Sanitize::string($this->body["entity"]);
				$ref = $transfer->data->transfer_code;
				$amount = $transfer->data->amount;
				$initiator = Session::get("username");
				$purpose = Sanitize::string($this->body["purpose"]);

				$this->registerTransaction($id, "payment", $ref, 0, $amount, $purpose, $initiator, "pending");
			}

			exit(Response::json($transfer));
		} catch (\Exception $error) {
			exit(Response::json(["status" => false, "data" => [], "message" => $error->getMessage()]));
		}
	}

	function hook()
	{
		// only a post with paystack signature header gets our attention
		if ((strtoupper($_SERVER['REQUEST_METHOD']) != 'POST') || !array_key_exists('x-paystack-signature', $_SERVER)) exit();

		// Retrieve the request's body
		$input = @file_get_contents("php://input");

		define('PAYSTACK_SECRET_KEY', $this->pk());

		// validate event do all at once to avoid timing attack
		if ($_SERVER['HTTP_X_PAYSTACK_SIGNATURE'] !== hash_hmac('sha512', $input, PAYSTACK_SECRET_KEY)) exit();

		http_response_code(200);

		$event = json_decode($input);

		$status = "";

		if ($event->event === "transfer.success") $status = "complete";
		if ($event->$event == "transfer.failed") $status = "failed";
		if ($event->$event == "transfer.reversed") $status = "reversed";
		$this->updateTransactionStatus($event->data->transfer_code, $status);
		exit();
	}
}
