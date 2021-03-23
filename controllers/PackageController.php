<?php

namespace App\controllers;


use App\utils\Session;
use App\middleware\Auth;
use App\utils\File;
use App\utils\Response;
use App\utils\Sanitize;

class PackageController extends Controller
{

	protected function getClientId()
	{
		Session::start();
		Auth::checkAuth("clientid");
		return Session::get("clientid");
	}

	public function getPackagesByClientId()
	{
		try {
			$id = $this->getClientId();
			$package = $this->findAll([
				"tablename" => "package A",
				"condition" => "A.client_id = :id ORDER BY created_at DESC",
				"fields" => "A.*, B.location",
				"joins" => "LEFT JOIN locations B ON A.destination = B.id",
				"bindparam" => ["id" => $id]
			]);
			return Response::send(["status" => true, "data" => $package]);
		} catch (\Exception $error) {
			return Response::send(["status" => false, "message" => $error->getMessage()]);
		}
	}

	public function getAllWayBills()
	{
		Auth::checkAuth("userid");
		return $this->findAll([
			"tablename" => "package A",
			"condition" => "A.status IN ('sent', 'received') ORDER BY created_at DESC",
			"fields" => "A.*, B.location, c.companyname, D.telephone",
			"joins" => "LEFT JOIN locations B ON A.destination = B.id INNER JOIN client_profile C ON A.client_id = C.client_id INNER JOIN clients D ON A.client_id = D.id"
		]);
	}

	public function index()
	{
	}

	public function add()
	{
		try {
			$id = $this->getClientId();
			$title = $this->body["title"];
			$weight = $this->body["weight"];
			$description = $this->body["description"];
			$destination = $this->body["destination"];
			$instructions = $this->body["instructions"];
			$drivernumber = $this->body["drivernumber"];
			$transportcompany = $this->body["transportcompany"];
			$image = isset($this->file["image"]) && !empty($this->file["image"]["name"])  ? File::upload(["file" => $this->file["image"], "path" => __DIR__ . "/../files/document/"]) : null;

			// register package	
			$this->create([
				"tablename" => "package",
				"fields" => "`client_id`, `package_title`, `weight`, `description`, `image`,`destination`, `instructions`, `driver_number`, `transport_company`",
				"values" => ":id,:title,:weight,:description,:image,:destination,:instructions,:drivernumber,:transportcompany",
				"bindparam" => [":id" => $id, ":title" => $title, ":weight" => $weight, ":description" => $description, ":image" => $image, ":destination" => $destination, ":instructions" => $instructions, ":drivernumber" => $drivernumber, ":transportcompany" => $transportcompany]
			]);

			$packageid = $this->lastId();
			$items = $this->body["item"];
			$costs = $this->body["cost"];
			$quantities = $this->body["quantity"];

			for ($i = 0; $i < count($items); $i++) {
				//register package_item
				$this->create([
					"tablename" => "package_item",
					"fields" => "`item_id`, `package_id`, `unitcost`, `quantity`, `location`",
					"values" => ":id,:packageid,:cost,:quantity,:location",
					"bindparam" => [":id" => $items[$i], ":packageid" => $packageid, ":cost" => $costs[$i], ":quantity" => $quantities[$i], ":location" => $destination]
				]);
			}

			exit(Response::json(["status" => true, "message" => "package registered successfully", "data" => ["packageid" => $packageid]]));
		} catch (\Exception $error) {
			exit(Response::json(["status" => false, "message" => $error->getMessage()]));
		}
	}

	public function getPackage()
	{
		try {
			$package = ["package" => "", "packageitems" => [], "owner" => ""];
			$id = $this->query["packageid"];
			$package["package"] = $this->getPackageById($id);
			$package["packageitems"] = $this->getPackageDetailByPackageId($id);
			$package["owner"] = $this->getPackageOwner($package["package"]["client_id"]);
			return $package;
		} catch (\Exception $error) {
			return $error->getMessage();
		}
	}

	public function getPackageOwner($clientid)
	{
		return $this->findOne([
			"tablename" => "clients A",
			"condition" => "A.id = :id",
			"bindparam" => [":id" => $clientid],
			"fields" => "A.id,A.email,A.telephone,B.*",
			"joins" => "INNER JOIN client_profile B ON A.id = B.client_id"
		]);
	}

	public function getPackageById($id)
	{
		return $this->findOne(["tablename" => "package", "condition" => "id = :id", "bindparam" => [":id" => $id]]);
	}

	public function getPackageDetailByPackageId($id)
	{
		return $this->findAll([
			"tablename" => "package_item A",
			"condition" => "package_id = :id",
			"fields" => "A.*, B.name",
			"joins" => "INNER JOIN catalog B ON A.item_id = B.id",
			"bindparam" => [":id" => $id]
		]);
	}

	public function updatepackage($packageid)
	{
		$package = $this->findOne(["tablename" => "package", "condition" => "id = :id", "bindparam" => [":id" => $packageid]]);

		if (!$package) throw new \Exception("Package not found");

		$title = $this->body["title"] ?? $package["package_title"];
		$weight = $this->body["weight"] ?? $package["weight"];
		$description = $this->body["description"] ?? $package["description"];
		$destination = $this->body["destination"] ?? $package["destination"];
		$instructions = $this->body["instructions"] ?? $package["instructions"];
		$drivernumber = $this->body["drivernumber"] ?? $package["driver_number"];
		$transportcompany = $this->body["transportcompany"] ?? $package["transport_company"];
		$status = $this->body["status"] ?? $package["status"];
		$image = isset($this->file["image"]) && !empty($this->file["image"]["name"]) ? File::upload(["file" => $this->file["image"], "path" => __DIR__ . "/../files/document/"]) : $package["image"];

		// update package	
		$this->update([
			"tablename" => "package",
			"fields" => "package_title = :title,weight = :weight,description = :description,image = :image,destination = :destination,instructions = :instructions,driver_number = :drivernumber,transport_company = :transportcompany,status = :status",
			"condition" => "id = :id",
			"bindparam" => [":title" => $title, ":weight" => $weight, ":description" => $description, ":image" => $image, ":destination" => $destination, ":instructions" => $instructions, ":drivernumber" => $drivernumber, ":transportcompany" => $transportcompany, ":status" => $status, ":id" => $packageid]
		]);

		if (isset($this->body["ids"])) {
			$items = $this->body["item"];
			$ids = $this->body["ids"];
			$costs = $this->body["cost"];
			$quantities = $this->body["quantity"];

			for ($i = 0; $i < count($items); $i++) {
				$packageitem = $this->findOne([
					"tablename" => "package_item",
					"condition" => "item_id =:itemid AND package_id =:packageid",
					"bindparam" => [":itemid" => $items[$i], ":packageid" => $packageid]
				]);


				if ($packageitem) {
					$id = $ids[$i];
					$item = $items[$i] ?? $packageitem["item_id"];
					$cost = $costs[$i] ?? $packageitem["unitcost"];
					$quantity = $quantities[$i] ?? $packageitem["quantity"];
					//update package_item
					$this->update([
						"tablename" => "package_item",
						"fields" => "item_id=:id, unitcost=:cost, quantity =:quantity",
						"condition" => "id = :id",
						"bindparam" => [":id" => $id, ":itemid" => $item, ":cost" => $cost, ":quantity" => $quantity]
					]);
				} else {
					$this->create([
						"tablename" => "package_item",
						"fields" => "`item_id`, `package_id`, `unitcost`, `quantity`, `location`",
						"values" => ":id,:packageid,:cost,:quantity,:location",
						"bindparam" => [":id" => $items[$i], ":packageid" => $packageid, ":cost" => $costs[$i], ":quantity" => $quantities[$i], ":location" => $destination]
					]);
				}
			}
		}

		return "package updated successfully";
	}

	public function edit()
	{
		try {
			$packageid = $this->body["packageid"];
			$package = $this->updatepackage($packageid);
			exit(Response::json(["status" => true, "message" => $package]));
		} catch (\Exception $error) {
			exit(Response::json(["status" => false, "message" => $error->getMessage()]));
		}
	}

	public function updatewaybill()
	{
		try {
			Auth::checkAuth("userid");
			$packageid = $this->body["packageid"];
			$package = $this->findOne(["tablename" => "package", "condition" => "id = :id", "bindparam" => [":id" => $packageid]]);

			if (!$package) throw new \Exception("Package not found");
			$destination = $this->body["destination"] ?? $package["destination"];
			$status = $this->body["status"] ?? $package["status"];

			// update package	
			$this->update([
				"tablename" => "package",
				"fields" => "destination = :destination,status = :status",
				"condition" => "id = :id",
				"bindparam" => [":destination" => $destination, ":status" => $status, ":id" => $packageid]
			]);

			if (isset($this->body["ids"])) {
				$items = $this->body["item"];
				$ids = $this->body["ids"];
				$quantities = $this->body["quantity"];

				for ($i = 0; $i < count($items); $i++) {
					$packageitem = $this->findOne([
						"tablename" => "package_item",
						"condition" => "item_id =:itemid AND package_id =:packageid",
						"bindparam" => [":itemid" => $items[$i], ":packageid" => $packageid]
					]);

					if ($packageitem) {
						$quantity = Sanitize::integer($quantities[$i]) ?? $packageitem["quantity"];
						//update package_item
						$q = $this->update([
							"tablename" => "package_item",
							"fields" => "quantity =:quantity",
							"condition" => "id = :id",
							"bindparam" => [":id" => $ids[$i], ":quantity" => $quantity]
						]);
					}
				}
			}
			// notify client of changes
			// notify admin of changes
			exit(Response::json(["status" => true, "message" => "changes saved successfully"]));
		} catch (\Exception $error) {
			exit(Response::json(["status" => false, "message" => $error->getMessage()]));
		}
	}

	public function recievewaybill()
	{
		try {
			Auth::checkAuth("userid");
			$packageid = $this->body["packageid"];
			$package = $this->findOne(["tablename" => "package", "condition" => "id = :id", "bindparam" => [":id" => $packageid]]);

			if (!$package) throw new \Exception("Package not found");

			// update package	
			$this->update([
				"tablename" => "package",
				"fields" => "status = :status",
				"condition" => "id = :id",
				"bindparam" => [":status" => "received", ":id" => $packageid]
			]);

			// notify client of changes
			// notify admin of changes
			exit(Response::json(["status" => true, "message" => "waybill marked as received successfully"]));
		} catch (\Exception $error) {
			exit(Response::json(["status" => false, "message" => $error->getMessage()]));
		}
	}

	public function getitems()
	{
		try {
			$this->getClientId();
			$package = $this->getPackageItemsWithDetails();
			exit(Response::json(["status" => true, "message" => "record retrieved", "data" => $package]));
		} catch (\Exception $error) {
			exit(Response::json(["status" => false, "message" => $error->getMessage()]));
		}
	}

	public function delete()
	{
		try {
			$packageid = $this->body["packageid"];
			$clientid = $this->getClientId();

			$package = $this->findOne(["tablename" => "package", "condition" => "id = :id", "bindparam" => [":id" => $packageid]]);
			if ($package["client_id"] !== $clientid) throw new \Exception("You are not alllowed to perform this operation");

			$this->destroy([
				"tablename" => "package",
				"condition" => "id = :id AND client_id =:clientid",
				"bindparam" => [":id" => $packageid, ":clientid" => $clientid]
			]);
			exit(Response::json(["status" => true, "message" => "Package deleted successfully"]));
		} catch (\Exception $error) {
			exit(Response::json(["status" => false, "message" => $error->getMessage()]));
		}
	}
	public function getClientPackageItemsWithDetails()
	{
		$id = $this->getClientId();
		return $this->findAll([
			"tablename" => "package_item A",
			"condition" => "B.client_id =:id GROUP BY A.item_id",
			"joins" => "INNER JOIN package B ON A.package_id = B.id INNER JOIN catalog C ON A.item_id = C.id INNER JOIN client_profile D ON C.client_id = D.client_id INNER JOIN locations E ON A.location = E.id",
			"fields" => "DISTINCT A.item_id, A.location, SUM(A.quantity) as quantity, B.package_title,B.weight,B.description,B.image,B.status,B.destination,B.driver_number,B.transport_company,B.instructions,C.name,C.unit_cost,C.unit_measure,C.description,D.*, E.state_id as itemstate_id, E.location,E.status, E.amount as waybillfee",
			"bindparam" => [":id" => $id]
		]);
	}

	public function getPackageItemsWithDetails()
	{
		Auth::checkAuth("userid");
		return $this->findAll([
			"tablename" => "package_item A",
			"condition" => "1 GROUP BY A.item_id",
			"joins" => "INNER JOIN package B ON A.package_id = B.id INNER JOIN catalog C ON A.item_id = C.id INNER JOIN client_profile D ON C.client_id = D.client_id INNER JOIN locations E ON A.location = E.id INNER JOIN clients F ON F.id = B.client_id",
			"fields" => "DISTINCT A.item_id, A.location, SUM(A.quantity) as quantity, B.package_title,B.weight,B.description,B.image,B.status,B.destination,B.driver_number,B.transport_company,B.instructions,C.name,C.unit_cost,C.unit_measure,C.description,D.*, E.state_id as itemstate_id, E.location,E.status, E.amount as waybillfee, F.telephone"
		]);
	}

	public function getPackageItemDetails($itemid)
	{
		return $this->findOne([
			"tablename" => "package_item A",
			"condition" => "A.item_id =:itemid GROUP BY A.item_id",
			"bindparam" => [":itemid" => $itemid],
			"joins" => "INNER JOIN package B ON A.package_id = B.id INNER JOIN catalog C ON A.item_id = C.id INNER JOIN client_profile D ON C.client_id = D.client_id INNER JOIN locations E ON A.location = E.id",
			"fields" => "DISTINCT A.item_id, A.location, SUM(A.quantity) as quantity, B.package_title,B.weight,B.description,B.image,B.status,B.destination,B.driver_number,B.transport_company,B.instructions,C.name,C.unit_cost,C.unit_measure,C.description,D.*, E.state_id as itemstate_id, E.location,E.status, E.amount as waybillfee"
		]);
	}
}
