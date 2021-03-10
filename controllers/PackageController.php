<?php

namespace App\controllers;


use App\utils\Session;
use App\middleware\Auth;
use App\utils\File;
use App\utils\Response;

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
				"tablename" => "package",
				"condition" => "client_id = :id ORDER BY created_at DESC",
				"bindparam" => ["id" => $id]
			]);
			return Response::send(["status" => true, "data" => $package]);
		} catch (\Exception $error) {
			return Response::send(["status" => false, "message" => $error->getMessage()]);
		}
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
			$image = isset($this->file["image"]) ? File::upload(["file" => $this->file["image"], "path" => __DIR__ . "/../files/photo/"]) : null;

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
			"joins" => "INNER JOIN inventory B ON A.item_id = B.id",
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
		$image = isset($this->file["image"]) && !empty($this->file["image"]["name"]) ? File::upload(["file" => $this->file["image"], "path" => __DIR__ . "/../files/photo/"]) : $package["image"];

		// register package	
		$this->update([
			"tablename" => "package",
			"fields" => "package_title = :title,weight = :weight,description = :description,image = :image,destination = :destination,instructions = :instructions,driver_number = :drivernumber,transport_company = :transportcompany,status = :status",
			"condition" => "id = :id",
			"bindparam" => [":title" => $title, ":weight" => $weight, ":description" => $description, ":image" => $image, ":destination" => $destination, ":instructions" => $instructions, ":drivernumber" => $drivernumber, ":transportcompany" => $transportcompany, ":status" => $status, ":id" => $packageid]
		]);

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

	public function delete()
	{
	}
}
