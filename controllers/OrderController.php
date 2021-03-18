<?php

namespace App\controllers;

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
			//send email here
			exit(Response::json(["status" => true, "message" => "Order sent successfully"]));
		} catch (\Exception $error) {
			exit(Response::json(["status" => false, "message" => $error->getMessage()]));
		}
	}
}
