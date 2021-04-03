<?php

namespace App\controllers;

use App\middleware\Auth;
use App\utils\Response;
use App\utils\Sanitize;
use App\utils\Session;

class WaybillController extends Controller
{

	protected function insertwaybill($clientid, $fee, $description, $state, $destination, $driver, $transportcompany, $paymentsource)
	{
		return $this->create([
			"tablename" => "waybill",
			"fields" => "`client_id`, `fee`, `description`, `state_id`, `destination`,`driver_number`, `transport_company`,`payment_source`",
			"values" => ":clientid,:fee,:description,:state,:destination,:driver,:transportcompany,:source",
			"bindparam" => [
				":clientid" => $clientid, ":fee" => $fee, ":description" => $description, ":state" => $state, ":destination" => $destination, ":driver" => $driver, ":transportcompany" => $transportcompany, ":source" => $paymentsource
			]
		]);
	}

	protected function insertwaybillitems($waybillid, $itemid, $quantity)
	{
		return $this->create([
			"tablename" => "waybill_item",
			"fields" => "`waybill_id`, `item_id`, `quantity`",
			"values" => ":waybillid,:itemid,:quantity",
			"bindparam" => [":waybillid" => $waybillid, ":itemid" => $itemid, ":quantity" => $quantity]
		]);
	}

	public function add()
	{
		try {
			Auth::checkAuth("clientid");
			$clientid = Session::get("clientid");
			$state = Sanitize::string($this->body["state"]);
			$destination = Sanitize::string($this->body["destination"]);
			$description = Sanitize::string($this->body["description"]);
			$driver = Sanitize::string($this->body["driver"]);
			$transportcompany = Sanitize::string($this->body["transportcompany"]);
			$paymentsource = Sanitize::string($this->body["paymentsource"]);

			$fee = $this->findOne(["tablename" => "states", "condition" => "id = :state", "bindparam" => [":state" => $state]])["waybill_charge"];

			$this->insertwaybill($clientid, $fee, $description, $state, $destination, $driver, $transportcompany, $paymentsource);

			$waybillid = $this->lastId();

			$items = $this->body["item"];
			$quantity = $this->body["quantity"];

			if (count($items) > 0) {
				for ($i = 0; $i < count($items); $i++) {
					$itemid = $items[$i];
					$qty = $quantity[$i];
					$this->insertwaybillitems($waybillid, $itemid, $qty);
				}
			}

			//notify admin
			exit(Response::json(["status" => true, "message" => "waybill submitted successfully"]));
		} catch (\Exception $error) {
			exit(Response::json(["status" => false, "message" => $error->getMessage()]));
		}
	}

	public function additem()
	{
		try {
			Auth::checkAuth("clientid");

			$clientid = Session::get("clientid");

			$waybillid = Sanitize::string($this->body["waybillid"]);
			$itemid = Sanitize::string($this->body["item"]);
			$quantity = Sanitize::integer($this->body["quantity"]);

			$waybill = 	$this->findOne([
				"tablename" => "waybill",
				"condition" => "id =:id",
				"bindparam" => [":id" => $waybillid]
			]);

			if (!$waybill) throw new \Exception("waybill does not exist");

			if ($waybill["client_id"] !== $clientid) throw new \Exception("You are to allowed to perform this operation");

			$waybillitem = $this->findOne([
				"tablename" => "waybill_item",
				"condition" => "waybill_id =:waybillid AND item_id =:item",
				"bindparam" => [":waybillid" => $waybillid, ":item" => $itemid]
			]);

			if ($waybillitem) throw new \Exception("item already in waybill");

			$this->insertwaybillitems($waybillid, $itemid, $quantity);

			//notify admin
			exit(Response::json(["status" => true, "data" => $this->waybillitem($this->lastId()), "message" => "item added successfully"]));
		} catch (\Exception $error) {
			exit(Response::json(["status" => false, "message" => $error->getMessage()]));
		}
	}

	public function waybills()
	{
		Auth::checkAuth("userid");
		return $this->findAll([
			"tablename" => "waybill A",
			"fields" => "A.*, B.state, C.telephone, C.email, D.companyname",
			"joins" => "INNER JOIN states B ON A.state_id = B.id INNER JOIN clients C ON A.client_id = C.id INNER JOIN client_profile D ON C.id = D.client_id",
		]);
	}

	public function waybill()
	{
		Auth::checkAuth("userid");
		$waybillid = Sanitize::string($this->query["id"]);
		$waybill = 	$this->findOne([
			"tablename" => "waybill A",
			"fields" => "A.*, B.state, C.telephone, C.email, D.companyname",
			"joins" => "INNER JOIN states B ON A.state_id = B.id INNER JOIN clients C ON A.client_id = C.id INNER JOIN client_profile D ON C.id = D.client_id",
			"condition" => "A.id =:id",
			"bindparam" => [":id" => $waybillid]
		]);

		$waybillItems = $this->waybillitems($waybillid);
		return ["waybill" => $waybill, "waybillitems" => $waybillItems];
	}

	protected function waybillitems($waybillid)
	{
		return $this->findAll([
			"tablename" => "waybill_item A",
			"fields" => "A.*, B.name",
			"condition" => "A.waybill_id = :waybillid",
			"joins" => "INNER JOIN catalog B ON A.item_id = B.id",
			"bindparam" => [":waybillid" => $waybillid]
		]);
	}

	protected function waybillitem($id)
	{
		return $this->findOne([
			"tablename" => "waybill_item A",
			"fields" => "A.*, B.name",
			"condition" => "A.id = :id",
			"joins" => "INNER JOIN catalog B ON A.item_id = B.id",
			"bindparam" => [":id" => $id]
		]);
	}

	public function clientwaybills()
	{
		Auth::checkAuth("clientid");
		$clientid = Session::get("clientid");
		return $this->findAll([
			"tablename" => "waybill",
			"condition" => "client_id = :clientid ORDER BY updated_at",
			"bindparam" => [":clientid" => $clientid]
		]);
	}

	protected function clientwaybillrequest($clientid, $waybillid)
	{
		return $this->findOne([
			"tablename" => "waybill A",
			"fields" => "A.*, B.state",
			"condition" => "A.id =:id AND A.client_id = :clientid",
			"joins" => "INNER JOIN states B ON A.state_id = B.id",
			"bindparam" => [":id" => $waybillid, ":clientid" => $clientid]
		]);
	}

	public function clientwaybill()
	{
		Auth::checkAuth("clientid");
		$clientid = Session::get("clientid");
		$waybillid = Sanitize::string($this->query["id"]);

		$waybill = 	$this->clientwaybillrequest($clientid, $waybillid);
		$waybillItems = $this->waybillitems($waybillid);

		return ["waybill" => $waybill, "waybillitems" => $waybillItems];
	}

	protected function updatewaybill($id, $data)
	{
		$waybill = $this->findOne(["tablename" => "waybill", "condition" => "id =:id", "bindparam" => [":id" => $id]]);
		if (!$waybill) throw new \Exception("waybill not found");

		$fee = $data["fee"] ?? $waybill["fee"];
		$description = $data["description"] ?? $waybill["description"];
		$state = $data["state"] ?? $waybill["state_id"];
		$destination = $data["destination"] ?? $waybill["destination"];
		$status = $data["status"] ?? $waybill["status"];
		$driver = $data["drivernumber"] ?? $waybill["driver_number"];
		$source = $data["paymentsource"] ?? $waybill["payment_source"];
		$transportcompany = $data["transportcompany"] ?? $waybill["transport_company"];

		return $this->update([
			"tablename" => "waybill",
			"fields" => "`fee`=:fee,`description`=:description,`state_id`=:state,`destination`=:destination,`status`=:status,`driver_number`=:driver,`transport_company`=:transportcompany,`payment_source`=:source",
			"condition" => "id =:id",
			"bindparam" => [":id" => $id, ":fee" => $fee, ":description" => $description, ":state" => $state, ":destination" => $destination, ":status" => $status, ":driver" => $driver, ":transportcompany" => $transportcompany, ":source" => $source]
		]);
	}

	public function deletewaybillitem()
	{
		try {
			$clientid = Session::get("clientid");
			$userid = Session::get("userid");
			$id = Sanitize::string($this->body["id"]);
			$waybillitem = null;

			if (!$clientid && !$userid) throw new \Exception("you are not allowed to perform this operation");

			if ($clientid) {
				$waybillitem = $this->findOne([
					"tablename" => "waybill_item A",
					"condition" => "A.id =:id AND B.client_id =:clientid",
					"joins" => "INNER JOIN waybill B ON A.waybill_id = B.id",
					"bindparam" => [":id" => $id, ":clientid" => $clientid]
				]);

				if (!$waybillitem) throw new \Exception("Item not found");
			}

			if ($userid) {
				$waybillitem = $this->findOne(["tablename" => "waybill_item", "condition" => "id =:id", "bindparam" => [":id" => $id]]);

				if (!$waybillitem) throw new \Exception("Item not found");
			}

			$this->destroy(["tablename" => "waybill_item", "condition" => "id =:id", "bindparam" => [":id" => $id]]);
			exit(Response::json(["status" => true, "message" => "item deleted successfully"]));
		} catch (\Exception $error) {
			exit(Response::json(["status" => false, "message" => $error->getMessage()]));
		}
	}

	public function clientupdatewaybillitem()
	{
		try {
			Auth::checkAuth("clientid");
			$clientid = Session::get("clientid");
			$waybillid = Sanitize::string($this->body["waybillid"]);
			$state = Sanitize::string($this->body["state"]);
			$destination = Sanitize::string($this->body["destination"]);
			$description = Sanitize::string($this->body["description"]);
			$source = Sanitize::string($this->body["paymentsource"]);

			$waybill = 	$this->clientwaybillrequest($clientid, $waybillid);
			if (!$waybill) throw new \Exception("waybill not found");

			if ($waybill["status"] !== "pending") throw new \Exception("unabled to update, item has already been " . $waybill["status"]);

			$fee = $this->findOne(["tablename" => "states", "condition" => "id = :state", "bindparam" => [":state" => $state]])["waybill_charge"];

			$this->updatewaybill($waybillid, ["state" => $state, "fee" => $fee, "destination" => $destination, "description" => $description, "paymentsource" => $source]);

			exit(Response::json(["status" => true, "message" => "waybill updated successfully"]));
		} catch (\Exception $error) {
			exit(Response::json(["status" => false, "message" => $error->getMessage()]));
		}
	}

	public function cancelwaybill()
	{
		try {
			Auth::checkAuth("clientid");
			$clientid = Session::get("clientid");
			$waybillid = Sanitize::string($this->query["id"]);

			$waybill = 	$this->clientwaybillrequest($clientid, $waybillid);
			if (!$waybill) throw new \Exception("waybill not found");

			if ($waybill["status"] !== "pending") throw new \Exception("unabled to update, item has already been " . $waybill["status"]);

			$this->updatewaybill($waybillid, ["status" => "cancelled"]);

			// notify admin

			exit(Response::json(["status" => true, "message" => "waybill cancelled successfully"]));
		} catch (\Exception $error) {
			exit(Response::json(["status" => false, "message" => $error->getMessage()]));
		}
	}

	public function processwaybill()
	{
		try {
			Auth::checkAuth("userid");
			$id = Sanitize::string($this->body["waybillid"]);
			$drivernumber = Sanitize::string($this->body["drivernumber"]);
			$transportcompany = Sanitize::string($this->body["transportcompany"]);
			$status = Sanitize::string($this->body["status"]);
			$extra = Sanitize::integer($this->body["extra"]);

			$waybill = 	$this->findOne(["tablename" => "waybill", "condition" => "id = :id", "bindparam" => [":id" => $id]]);
			if (!$waybill) throw new \Exception("waybill not found");

			if ($waybill["status"] !== "pending") throw new \Exception("unabled to update, item has already been " . $waybill["status"]);

			$fee = floatval($waybill["fee"]) + $extra;

			$this->updatewaybill($id, ["status" => $status, "drivernumber" => $drivernumber, "transportcompany" => $transportcompany, "fee" => $fee]);

			if ($status === "sent") {
				// reduce the items
				$waybillItems = $this->findAll(["tablename" => "waybill_item", "condition" => "waybill_id =:id", "bindparam" => [":id" => $id]]);

				for ($i = 0; $i < count($waybillItems); $i++) {
					$itemid = $waybillItems[$i]["item_id"];
					$quantity = $waybillItems[$i]["quantity"];

					$this->create([
						"tablename" => "package_item",
						"fields" => "`item_id`,`quantity`",
						"values" => ":itemid,:quantity",
						"bindparam" => [":itemid" => $itemid, ":quantity" => "-" . $quantity]
					]);
				}
				//notify client

				if ($waybill["payment_source"] === "SENDER") {
					$description = "debit of " . number_format($fee) . " for items waybill to " . $waybill["destination"];
					//if payment source == sender, debit client
				}
			}

			exit(Response::json(["status" => true, "message" => "waybill updated successfully"]));
		} catch (\Exception $error) {
			exit(Response::json(["status" => false, "message" => $error->getMessage()]));
		}
	}
}
