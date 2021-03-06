<?php

namespace App\controllers;

use App\middleware\Auth;
use App\services\MailService;
use App\utils\EmailTemplate;
use App\utils\Response;
use App\utils\Sanitize;

class OrderController extends Controller
{
	protected $client;
	protected $config;

	public function __construct()
	{
		parent::__construct();
		$this->client = new ClientController();
		$this->config = new ConfigController();
	}

	public function getClientOrders()
	{
		return $this->findAll([
			"tablename" => "orders A",
			"condition" => "A.client_id = :id ORDER BY A.created_at DESC",
			"bindparam" => [":id" => $this->client->getClientId()],
			"fields" => "A.*, B.state, C.city",
			"joins" => "INNER JOIN states B ON A.state_id = B.id INNER JOIN delivery_pricing C ON A.city_id = C.id"
		]);
	}

	public function getAllOrders()
	{
		Auth::checkAuth("userid");
		// return $this->paginate([
		// 	"tablename" => "orders A",
		// 	"condition" => "1 ORDER BY A.created_at DESC",
		// 	"fields" => "A.*, B.state, C.city, D.telephone, F.companyname",
		// 	"joins" => "INNER JOIN states B ON A.state_id = B.id INNER JOIN delivery_pricing C ON A.city_id = C.id INNER JOIN clients D ON A.client_id = D.id INNER JOIN client_profile F ON A.client_id = F.client_id",
		// 	"pageno" => $this->query["page"] ?? 1,
		// 	"limit" => 10
		// ]);
		return $this->findAll([
			"tablename" => "orders A",
			"condition" => "1 ORDER BY A.created_at DESC",
			"fields" => "A.*, B.state, C.city, D.telephone, F.companyname",
			"joins" => "INNER JOIN states B ON A.state_id = B.id INNER JOIN delivery_pricing C ON A.city_id = C.id INNER JOIN clients D ON A.client_id = D.id INNER JOIN client_profile F ON A.client_id = F.client_id",
			// "pageno" => $this->query["page"] ?? 1,
			// "limit" => 10
		]);
	}

	public function getAllOrdersByStatus($status)
	{
		Auth::checkAuth("userid");
		if ($status == "unassigned") {
			return $this->findAll([
				"tablename" => "orders A",
				"condition" => "A.id NOT IN (SELECT order_id FROM tasks) ORDER BY A.created_at DESC",
				"fields" => "A.*, B.state, C.city, D.telephone, F.companyname",
				"joins" => "INNER JOIN states B ON A.state_id = B.id INNER JOIN delivery_pricing C ON A.city_id = C.id INNER JOIN clients D ON A.client_id = D.id INNER JOIN client_profile F ON A.client_id = F.client_id",
				// "pageno" => $this->query["page"] ?? 1,
				// "limit" => 10 
			]);
		} else {
			return $this->findAll([
				"tablename" => "orders A",
				"condition" => "A.status =:status ORDER BY A.created_at DESC",
				"fields" => "A.*, B.state, C.city, D.telephone, F.companyname",
				"joins" => "INNER JOIN states B ON A.state_id = B.id INNER JOIN delivery_pricing C ON A.city_id = C.id INNER JOIN clients D ON A.client_id = D.id INNER JOIN client_profile F ON A.client_id = F.client_id",
				"bindparam" => [":status" => $status]
				// "pageno" => $this->query["page"] ?? 1,
				// "limit" => 10 
			]);
		}
	}

	public function getOrder($orderid)
	{
		return $this->findOne([
			"tablename" => "orders A",
			"condition" => "A.id = :id ORDER BY A.created_at DESC",
			"bindparam" => [":id" => $orderid],
			"fields" => "A.*, B.state, C.city, D.companyname, E.email as companyemail, E.telephone as companytelephone",
			"joins" => "INNER JOIN states B ON A.state_id = B.id INNER JOIN delivery_pricing C ON A.city_id = C.id INNER JOIN client_profile D ON A.client_id = D.client_id INNER JOIN clients E ON A.client_id = E.id"
		]);
	}

	public function getOrderItems($orderid)
	{
		return $this->findAll([
			"tablename" => "order_items A",
			"condition" => "A.order_id = :id",
			"bindparam" => [":id" => $orderid],
			"fields" => "A.*, B.name",
			"joins" => "INNER JOIN catalog B ON A.item_id = B.id"
		]);
	}

	public function getOrderHistory($orderid)
	{
		return $this->findAll([
			"tablename" => "order_history",
			"condition" => "order_id = :id",
			"bindparam" => [":id" => $orderid],
		]);
	}

	public function getOrderDetails($orderid)
	{
		return [
			"order" => $this->getOrder($orderid),
			"orderitems" => $this->getOrderItems($orderid),
			"orderhistory" => $this->getOrderHistory($orderid)
		];
	}

	public function addOrderItems($orderid, $itemid, $cost, $quantity)
	{
		return $this->create([
			"tablename" => "order_items",
			"fields" => "`order_id`, `item_id`, `cost`, `quantity`",
			"values" => ":order_id,:item_id,:cost,:quantity",
			"bindparam" => [":order_id" => $orderid, ":item_id" => $itemid, ":cost" => $cost, ":quantity" => $quantity]
		]);
	}

	public function addOrderHistory($orderid, $status, $description)
	{
		return $this->create([
			"tablename" => "order_history",
			"fields" => "`order_id`, `status`, `description`",
			"values" => ":order_id,:status,:description",
			"bindparam" => [":order_id" => $orderid, ":status" => $status, ":description" => $description]
		]);
	}

	public function submit()
	{
		try {
			$clientid = $this->client->getClientId();
			$basefee = $this->config->getConfig("BASE DELIVERY FEE")["value"];
			$customer = Sanitize::string($this->body["customer"]);
			$telephone = Sanitize::string($this->body["telephone"]);
			$state = Sanitize::string($this->body["state"]);
			$city = Sanitize::string($this->body["city"]);
			$address = Sanitize::string($this->body["address"]);
			$description = Sanitize::string($this->body["description"]);
			$extracharge = Sanitize::integer($this->body["conf_ec"]);
			$waybillfee = Sanitize::integer($this->body["conf_wbf"]);
			$deliveryfee = floatval($basefee) + $extracharge + $waybillfee;
			$items = json_decode($this->body["items"], true);
			$totalamount = 0;
			for ($i = 0; $i < count($items); $i++) {
				$totalamount += (floatval($items[$i]["price"]) * floatval($items[$i]["quantity"]));
			}
			//register order
			$this->create([
				"tablename" => "orders",
				"fields" => "`client_id`, `customer`, `telephone`, `address`, `state_id`, `city_id`, `totalamount`, `delivery_fee`, `description`",
				"values" => ":client_id,:customer,:telephone,:address,:state_id,:city_id,:totalamount,:delivery_fee,:description",
				"bindparam" => [":client_id" => $clientid, ":customer" => $customer, ":telephone" => $telephone, ":address" => $address, ":state_id" => $state, ":city_id" => $city, ":totalamount" => $totalamount, ":delivery_fee" => $deliveryfee, ":description" => $description]
			]);

			$orderid = $this->lastId();

			//register order items
			for ($i = 0; $i < count($items); $i++) {
				$totalamount += (floatval($items[$i]["price"]) * floatval($items[$i]["quantity"]));
				$itemid = Sanitize::integer($items[$i]["itemid"]);
				$cost = Sanitize::integer($items[$i]["price"]);
				$quantity = Sanitize::integer($items[$i]["quantity"]);
				$this->addOrderItems($orderid, $itemid, $cost, $quantity);
			}

			//register order history
			$this->addOrderHistory($orderid, "sent", "Order sent");

			echo Response::json(["status" => true, "message" => "Order sent successfully"]);

			//send email here
			$emails = explode(",", $this->config->getConfig("NOTIFICATION EMAILS"));

			if (count($emails) > 0) {
				for ($i = 0; $i < count($emails); $i++) {
					$email = trim($emails[$i]);
					$template = EmailTemplate::neworder();
					MailService::sendMail($email, "New Order Notification", $template);
				}
			}
		} catch (\Exception $error) {
			exit(Response::json(["status" => false, "message" => $error->getMessage()]));
		}
	}

	public function cancelorder()
	{
		try {
			$clientid = $this->client->getClientId();
			$orderid = $this->query["orderid"];
			$order = $this->getOrder($orderid);
			if (!$order) throw new \Exception("Order not found");

			if ($clientid !== $order["client_id"]) throw new \Exception("you are not allowed to perform this operation");
			if (in_array($order["status"], ["delivered", "intransit", "cancelled"])) throw new \Exception("You can no longer cancel this order");
			if (in_array($order["payment_status"], ["paid", "verified"])) throw new \Exception("You can cancel an order that has been paid for, Contact Admin");

			$this->update([
				"tablename" => "orders",
				"fields" => "status =:status",
				"condition" => "id =:orderid",
				"bindparam" => [":status" => "cancelled", ":orderid" => $orderid]
			]);

			$this->addOrderHistory($orderid, "cancelled", "Order was cancelled by " . $order["companyname"]);

			echo Response::json(["status" => true, "message" => "Order cancelled successfully"]);
			//send email here
			$emails = explode(",", $this->config->getConfig("NOTIFICATION EMAILS"));

			if (count($emails) > 0) {
				for ($i = 0; $i < count($emails); $i++) {
					$email = trim($emails[$i]);
					$template = EmailTemplate::cancelorder($orderid);
					MailService::sendMail($email, "New Order Notification", $template);
				}
			}
		} catch (\Exception $error) {
			exit(Response::json(["status" => false, "message" => $error->getMessage()]));
		}
	}

	public function verifypayment()
	{
		try {
			$clientid = $this->client->getClientId();
			$orderid = $this->query["orderid"];
			$order = $this->getOrder($orderid);
			if (!$order) throw new \Exception("Order not found");

			if ($clientid !== $order["client_id"]) throw new \Exception("you are not allowed to perform this operation");
			if (in_array($order["payment_status"], ["verified"])) throw new \Exception("You have alread verified this payment");
			if ($order["payment_status"] !== "paid") throw new \Exception("You can not verify an unpaid payment");

			$this->update([
				"tablename" => "orders",
				"fields" => "payment_status =:status",
				"condition" => "id =:orderid",
				"bindparam" => [":status" => "verified", ":orderid" => $orderid]
			]);

			$this->addOrderHistory($orderid, "verified", "Payment for this order was verified" . $order["companyname"]);

			//send email here
			exit(Response::json(["status" => true, "message" => "Payment verified successfully"]));
		} catch (\Exception $error) {
			exit(Response::json(["status" => false, "message" => $error->getMessage()]));
		}
	}

	public function getClientPayments()
	{
		$clientid = $this->client->getClientId();

		return $this->findAll([
			"tablename" => "orders",
			"condition" => "client_id =:clientid AND status =:status ORDER BY updated_at DESC",
			"bindparam" => [":clientid" => $clientid, ":status" => "delivered"]
		]);
	}

	public function getAllPayments()
	{
		Auth::checkAuth("userid");

		if (isset($this->query["status"])) {
			return $this->findAll([
				"tablename" => "orders A",
				"condition" => "A.status =:status AND A.payment_status = :paystatus ORDER BY A.updated_at DESC",
				"joins" => "INNER JOIN tasks B ON A.id = B.order_id INNER JOIN agents C ON B.agent_id = C.id",
				"fields" => "A.*, B.id as taskid, B.sendpayment,B.sendpayment_status,B.payment_method,B.proof,B.agentpayment, CONCAT(C.firstname,' ',C.lastname) as agent",
				"bindparam" => [":status" => "delivered", ":paystatus" => $this->query["status"]]
			]);
		}

		return $this->findAll([
			"tablename" => "orders A",
			"condition" => "A.status =:status ORDER BY A.updated_at DESC",
			"joins" => "INNER JOIN tasks B ON A.id = B.order_id INNER JOIN agents C ON B.agent_id = C.id",
			"fields" => "A.*, B.id as taskid,B.sendpayment,B.sendpayment_status,B.payment_method,B.proof,B.agentpayment, CONCAT(C.firstname,' ',C.lastname) as agent",
			"bindparam" => [":status" => "delivered"]
		]);
	}
}
