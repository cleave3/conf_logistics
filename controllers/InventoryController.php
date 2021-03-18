<?php

namespace App\controllers;

use App\utils\Response;
use App\utils\Validator;
use App\utils\Sanitize;
use App\middleware\Auth;
use App\utils\Session;

class InventoryController extends Controller
{
	protected function addinventory($name, $unit_cost, $unit_measure, $description)
	{
		$client = new ClientController();
		$clientid = $client->getClientId();

		$itemcount = $this->getCount([
			"tablename" => "catalog",
			"condition" => "name = :name AND client_id = :clientid",
			"bindparam" => [":name" => $name, "clientid" => $clientid]
		]);

		if ($itemcount > 0) throw new \Exception("You already have an item with the same name, consider updating it instead");

		$this->create([
			"tablename" => "catalog",
			"fields" => "`client_id`,`name`, `unit_cost`, `unit_measure`, `description`",
			"values" => ":clientid,:name,:cost,:measure,:description",
			"bindparam" => [":clientid" => $clientid, ":name" => $name, ":cost" => $unit_cost, ":measure" => $unit_measure, ":description" => $description]
		]);
	}

	protected function updateinventory($itemid, $name, $unit_cost, $unit_measure, $description)
	{
		$item = $this->findOne(["tablename" => "catalog", "condition" => "id = :id", "bindparam" => [":id" => $itemid]]);

		if (!$item) throw new \Exception("Item not found in inventory " . $itemid);

		$name = $name ?? $item["name"];
		$unit_cost = $unit_cost ?? $item["unit_cost"];
		$unit_measure = $unit_measure ?? $item["unit_measure"];
		$description = $description ?? $item["description"];

		$this->update([
			"tablename" => "catalog",
			"fields" => "name = :name, unit_cost = :cost, unit_measure = :measure, description = :description",
			"condition" => "id = :id",
			"bindparam" => [":id" => $itemid, ":name" => $name, ":cost" => $unit_cost, ":measure" => $unit_measure, ":description" => $description]
		]);
	}

	public function getClientInventory()
	{
		$client = new ClientController();
		$clientid = $client->getClientId();
		$data = $this->findAll([
			"tablename" => "catalog",
			"condition" => "client_id = :clientid AND status = :status",
			"bindparam" => [":clientid" => $clientid, ":status" => 1],
		]);
		return $data;
	}

	public function index()
	{
		try {
			$data = $this->getClientInventory();
			return Response::json(["status" => true, "data" => $data]);
		} catch (\Exception $error) {
			return Response::json(["status" => false, "message" => $error->getMessage()]);
		}
	}

	public function getInventoryItem($id)
	{
		return $this->findOne([
			"tablename" => "catalog",
			"condition" => "id = :id",
			"bindparam" => [":id" => $id],
		]);
	}

	public function item()
	{

		try {
			$itemid = $this->query["itemid"];

			$item = $this->getInventoryItem($itemid);

			exit(Response::json(["status" => true, "message" => "item retrieved", "data" => $item]));
		} catch (\Exception $error) {
			exit(Response::json(["status" => false, "message" => $error->getMessage()]));
		}
	}


	public function add()
	{
		try {
			$name = Sanitize::string($this->body["name"]);
			$unit_cost = Sanitize::integer($this->body["cost"]);
			$unit_measure = Sanitize::string($this->body["measure"]);
			$description = Sanitize::string($this->body["description"]);

			$this->addinventory($name, $unit_cost, $unit_measure, $description);

			exit(Response::json(["status" => true, "message" => "Item registered successfully"]));
		} catch (\Exception $error) {
			exit(Response::json(["status" => false, "message" => $error->getMessage()]));
		}
	}

	public function edit()
	{
		try {
			$itemid = $this->body["itemid"];
			$name = Sanitize::string($this->body["name"]);
			$unit_cost = Sanitize::integer($this->body["cost"]);
			$unit_measure = Sanitize::string($this->body["measure"]);
			$description = Sanitize::string($this->body["description"]);

			$this->updateinventory($itemid, $name, $unit_cost, $unit_measure, $description);

			exit(Response::json(["status" => true, "message" => "Item updated successfully", "data" => $this->query]));
		} catch (\Exception $error) {
			exit(Response::json(["status" => false, "message" => $error->getMessage()]));
		}
	}

	public function delete()
	{
		try {
			Session::start();
			Auth::checkAuth("clientid");

			$clientid = Session::get("clientid");
			$ids = explode(",", $this->body["items"]);

			for ($i = 0; $i < count($ids); $i++) {
				$data = $this->getInventoryItem($ids[$i]);
				if ($data["client_id"] !== $clientid) throw new \Exception("You are to allowed to perform this operation");

				// $this->destroy([
				// 	"tablename" => "catalog",
				// 	"condition" => "id = :id",
				// 	"bindparam" => [":id" => $ids[$i]]
				// ]);
				$this->update([
					"tablename" => "catalog",
					"fields" => "status = :status",
					"condition" => "id = :id",
					"bindparam" => [":id" => $ids[$i], ":status" => 0]
				]);
			}

			return Response::json(["status" => true, "message" => "Item deleted successfully"]);
		} catch (\Exception $error) {
			return Response::json(["status" => false, "message" => $error->getMessage()]);
		}
	}
}
