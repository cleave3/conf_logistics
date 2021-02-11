<?php

namespace App\controllers;

use App\utils\Response;
use App\utils\Validator;
use App\utils\Sanitize;
use App\middleware\Auth;
use App\utils\Session;

class InventoryController extends Controller
{
	protected function addinventory($name, $unit_cost, $unit_measure, $low_stock, $reorder, $description)
	{
		$client = new ClientController();
		$clientid = $client->getClientId();

		$itemcount = $this->getCount([
			"tablename" => "inventory",
			"condition" => "name = :name AND client_id = :clientid",
			"bindparam" => [":name" => $name, "clientid" => $clientid]
		]);

		if ($itemcount > 0) throw new \Exception("You already have an item with the same name, consider updating it instead");

		$this->create([
			"tablename" => "inventory",
			"fields" => "`client_id`,`name`, `unit_cost`, `unit_measure`, `low_stock`, `reorder`, `description`",
			"values" => ":clientid,:name,:cost,:measure,:lowstock,:reorder,:description",
			"bindparam" => [":clientid" => $clientid, ":name" => $name, ":cost" => $unit_cost, ":measure" => $unit_measure, ":lowstock" => $low_stock, ":reorder" => $reorder, ":description" => $description]
		]);
	}

	protected function updateinventory($itemid, $name, $unit_cost, $unit_measure, $low_stock, $reorder, $description)
	{
		$item = $this->findOne(["tablename" => "inventory", "condition" => "id = :id", "bindparam" => [":id" => $itemid]]);

		if (!$item) throw new \Exception("Item not found in inventory " . $itemid);

		$name = $name ?? $item["name"];
		$unit_cost = $unit_cost ?? $item["unit_cost"];
		$unit_measure = $unit_measure ?? $item["unit_measure"];
		$low_stock = $low_stock ?? $item["low_stock"];
		$reorder = $reorder ?? $item["reorder"];
		$description = $description ?? $item["description"];

		$this->update([
			"tablename" => "inventory",
			"fields" => "name = :name, unit_cost = :cost, unit_measure = :measure, low_stock = :lowstock, reorder = :reorder, description = :description",
			"condition" => "id = :id",
			"bindparam" => [":id" => $itemid, ":name" => $name, ":cost" => $unit_cost, ":measure" => $unit_measure, ":lowstock" => $low_stock, ":reorder" => $reorder, ":description" => $description]
		]);
	}

	protected function addinventoryitem($itemid, $in, $out)
	{
		$this->create([
			"tablename" => "inventory_item",
			"fields" => "`inventory_id`, `qty_in`, `qty_out`",
			"values" => ":itemid,:in,:out",
			"bindparam" => [":itemid" => $itemid, ":in" => $in, ":out" => $out]
		]);
	}

	public function getClientInventory()
	{
		$client = new ClientController();
		$clientid = $client->getClientId();
		$data = $this->findAll([
			"tablename" => "inventory",
			"condition" => "client_id = :clientid",
			"bindparam" => [":clientid" => $clientid],
			"fields" => "*,(SELECT SUM(qty_in) - SUM(qty_out) FROM inventory_item WHERE inventory.id = inventory_item.inventory_id) as quantity",
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
			"tablename" => "inventory",
			"condition" => "id = :id",
			"bindparam" => [":id" => $id],
			"fields" => "*,(SELECT SUM(qty_in) - SUM(qty_out) FROM inventory_item WHERE inventory.id = inventory_item.inventory_id) as quantity",
		]);
	}

	public function add()
	{
		try {
			$name = Sanitize::string($this->body["name"]);
			$unit_cost = Sanitize::integer($this->body["cost"]);
			$unit_measure = Sanitize::string($this->body["measure"]);
			$low_stock = Sanitize::integer($this->body["lowstock"]);
			$reorder = Sanitize::integer($this->body["reorder"]);
			$quantity = Sanitize::integer($this->body["quantity"]);
			$description = Sanitize::string($this->body["description"]);

			$this->addinventory($name, $unit_cost, $unit_measure, $low_stock, $reorder, $description);

			$itemid = $this->lastId();
			$this->addinventoryitem($itemid, $quantity, 0);

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
			$low_stock = Sanitize::integer($this->body["lowstock"]);
			$reorder = Sanitize::integer($this->body["reorder"]);
			$in = Sanitize::integer($this->body["in"]);
			$out = Sanitize::integer($this->body["out"]);
			$description = Sanitize::string($this->body["description"]);

			$this->updateinventory($itemid, $name, $unit_cost, $unit_measure, $low_stock, $reorder, $description);
			if ($in > 0 || $out > 0) $this->addinventoryitem($itemid, $in, $out);

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

				$this->destroy([
					"tablename" => "inventory",
					"condition" => "id = :id",
					"bindparam" => [":id" => $ids[$i]]
				]);
			}

			return Response::json(["status" => true, "message" => "Item deleted successfully"]);
		} catch (\Exception $error) {
			return Response::json(["status" => false, "message" => $error->getMessage()]);
		}
	}
}
