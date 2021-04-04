<?php

namespace App\controllers;

use App\utils\Response;
use App\utils\File;
use App\middleware\Auth;
use App\utils\Sanitize;

class ConfigController extends Controller
{
	public function getSettings()
	{
		Auth::checkAuth("userid");
		return $this->findOne(["tablename" => "settings"]);
	}

	public function updatesettings()
	{
		try {
			Auth::checkAuth("userid");
			$settings = $this->getSettings();

			$companyname = $this->body["companyname"] ?? $settings["companyname"];
			$email = $this->body["email"] ?? $settings["email"];
			$telephone = $this->body["telephone"] ?? $settings["telephone"];
			$slogan = $this->body["slogan"] ?? $settings["slogan"];

			$logo = isset($this->file["image"]) && !empty($this->file["image"]["name"]) ? File::upload(["file" => $this->file["image"], "path" => __DIR__ . "/../files/photo/"]) : $settings["logo"];

			$this->update([
				"tablename" => "settings",
				"fields" => "companyname = :companyname,logo = :logo,  email = :email, telephone = :telephone, slogan = :slogan",
				"condition" => "1",
				"bindparam" => [":companyname" => $companyname, ":logo" => $logo, ":email" => $email, ":telephone" => $telephone, ":slogan" => $slogan]
			]);

			exit(Response::json(["status" => true, "data" => $this->file["image"], "message" => "changes saved successfully"]));
		} catch (\Exception $error) {
			exit(Response::json(["status" => false, "message" => $error->getMessage()]));
		}
	}

	public function getConfigs()
	{
		return $this->findAll(["tablename" => "config"]);
	}

	public function getConfig($keyword)
	{
		return $this->findOne([
			"tablename" => "config",
			"condition" => "keyword = :keyword",
			"bindparam" => [":keyword" => $keyword]
		]);
	}

	public function getDeliveryFee()
	{
		try {
			$config = $this->getConfig("BASE DELIVERY FEE");
			exit(Response::json(["status" => true, "message" => "record retrieved", "data" => $config["value"]]));
		} catch (\Exception $error) {
			exit(Response::json(["status" => false, "message" => $error->getMessage()]));
		}
	}

	public function getDeliveryPricing()
	{
		return $this->findAll([
			"tablename" => "delivery_pricing A",
			"fields" => "A.id,A.city,A.extra_charge,B.state",
			"condition" => "1 ORDER BY B.state ASC",
			"joins" => "INNER JOIN states B ON A.state_id = B.id"
		]);
	}

	public function getPricingByCity($city)
	{
		return $this->findOne([
			"tablename" => "delivery_pricing A",
			"fields" => "A.city,A.extra_charge,B.state",
			"condition" => "city = :city",
			"bindparam" => [":city" => $city],
			"joins" => "INNER JOIN states B ON A.state_id = B.id"
		]);
	}

	public function getWallBilllocationBylocationName($location)
	{
		return $this->findOne([
			"tablename" => "locations A",
			"fields" => "A.*,B.state",
			"condition" => "location = :location",
			"bindparam" => [":location" => $location],
			"joins" => "INNER JOIN states B ON A.state_id = B.id"
		]);
	}

	public function getWallBilllocationById($id)
	{
		return $this->findOne([
			"tablename" => "locations A",
			"fields" => "A.*,B.state",
			"condition" => "A.id = :id",
			"bindparam" => [":id" => $id],
			"joins" => "INNER JOIN states B ON A.state_id = B.id"
		]);
	}

	public function getPricingById($id)
	{
		return $this->findOne([
			"tablename" => "delivery_pricing A",
			"fields" => "A.*,B.state",
			"condition" => "A.id = :id",
			"bindparam" => [":id" => $id],
			"joins" => "INNER JOIN states B ON A.state_id = B.id"
		]);
	}

	public function adddeliverypricing()
	{
		try {
			Auth::checkAuth("userid");
			$state = Sanitize::string($this->body["state"]);
			$city = Sanitize::string($this->body["city"]);
			$amount = Sanitize::integer($this->body["amount"]);

			if ($this->getPricingByCity($city)) throw new \Exception("You have already set pricing for this city");

			$this->create([
				"tablename" => "delivery_pricing",
				"fields" => "`state_id`, `city`, `extra_charge`",
				"values" => ":state,:city,:extra_charge",
				"bindparam" => [":state" => $state, ":city" => $city, ":extra_charge" => $amount]
			]);

			exit(Response::json(["status" => true, "message" => "pricing set successfully"]));
		} catch (\Exception $error) {
			exit(Response::json(["status" => false, "message" => $error->getMessage()]));
		}
	}

	public function deliverypricies()
	{
		try {
			$pricing = $this->getDeliveryPricing();
			exit(Response::json(["status" => true, "message" => "record fetched successfully", "data" => $pricing]));
		} catch (\Exception $error) {
			exit(Response::json(["status" => false, "message" => $error->getMessage()]));
		}
	}

	public function addwaybilllocation()
	{
		try {
			Auth::checkAuth("userid");
			$state = Sanitize::string($this->body["state"]);
			$location = Sanitize::string($this->body["location"]);
			$amount = Sanitize::integer($this->body["amount"]);

			if ($this->getWallBilllocationBylocationName($location)) throw new \Exception("You have already added this waybill location");

			$this->create([
				"tablename" => "locations",
				"fields" => "`state_id`, `location`, `amount`",
				"values" => ":state,:location,:amount",
				"bindparam" => [":state" => $state, ":location" => $location, ":amount" => $amount]
			]);

			exit(Response::json(["status" => true, "message" => "location set successfully"]));
		} catch (\Exception $error) {
			exit(Response::json(["status" => false, "message" => $error->getMessage()]));
		}
	}

	public function waybilllocations()
	{
		try {
			$location = $this->getAllWayBillLocations();

			exit(Response::json(["status" => true, "message" => "records fetched successfully", "data" => $location]));
		} catch (\Exception $error) {
			exit(Response::json(["status" => false, "message" => $error->getMessage()]));
		}
	}
	public function getAllWayBillLocations()
	{
		Auth::checkAuth("userid");
		return $this->findAll([
			"tablename" => "locations A",
			"fields" => "A.id,A.status,A.location,A.amount,B.state",
			"condition" => "1 ORDER BY B.state ASC",
			"joins" => "INNER JOIN states B ON A.state_id = B.id"
		]);
	}

	public function getActiveWayBillLocations()
	{
		Auth::checkAuth("userid");
		return $this->findAll([
			"tablename" => "locations A",
			"fields" => "A.*,B.state",
			"condition" => "status ='active' ORDER BY B.state ASC",
			"joins" => "INNER JOIN states B ON A.state_id = B.id"
		]);
	}

	public function price()
	{
		try {
			$price = $this->getPricingById($this->query["id"]);
			exit(Response::json(["status" => true, "message" => "records fetched successfully", "data" => $price]));
		} catch (\Exception $error) {
			exit(Response::json(["status" => false, "message" => $error->getMessage()]));
		}
	}

	public function location()
	{
		try {
			$location = $this->getWallBilllocationById($this->query["id"]);
			exit(Response::json(["status" => true, "message" => "records fetched successfully", "data" => $location]));
		} catch (\Exception $error) {
			exit(Response::json(["status" => false, "message" => $error->getMessage()]));
		}
	}

	public function updatepricing()
	{
		try {
			Auth::checkAuth("userid");

			$id = $this->body["priceid"];
			$price = $this->getPricingById($id);

			if (!$price) throw new \Exception("price not found");

			$state = Sanitize::integer($this->body["state"]) ?? $price["state_id"];
			$city = Sanitize::string($this->body["city"]) ?? $price["city"];
			$amount = Sanitize::integer($this->body["amount"]) ?? $price["extra_charge"];

			$this->update([
				"tablename" => "delivery_pricing",
				"fields" => "state_id = :state, city = :city, extra_charge = :extra_charge",
				"condition" => "id = :id",
				"bindparam" => [":state" => $state, ":city" => $city, ":extra_charge" => $amount, ":id" => $id]
			]);

			exit(Response::json(["status" => true, "message" => "pricing updated successfully"]));
		} catch (\Exception $error) {
			exit(Response::json(["status" => false, "message" => $error->getMessage()]));
		}
	}

	public function updatelocation()
	{
		try {
			Auth::checkAuth("userid");

			$id = $this->body["locationid"];
			$location = $this->getWallBilllocationById($id);

			if (!$location) throw new \Exception("location not found");

			$state = Sanitize::integer($this->body["state"]) ?? $location["state_id"];
			$location = Sanitize::string($this->body["location"]) ?? $location["location"];
			$amount = Sanitize::integer($this->body["amount"]) ?? $location["amount"];
			$status = Sanitize::string($this->body["status"]) ?? $location["status"];

			$this->update([
				"tablename" => "locations",
				"fields" => "state_id = :state, location = :location, amount = :amount, status = :status",
				"condition" => "id = :id",
				"bindparam" => [":state" => $state, ":location" => $location, ":amount" => $amount, ":status" => $status, ":id" => $id]
			]);

			exit(Response::json(["status" => true, "message" => "location updated successfully"]));
		} catch (\Exception $error) {
			exit(Response::json(["status" => false, "message" => $error->getMessage()]));
		}
	}

	public function updateconfig()
	{
		try {
			Auth::checkAuth("userid");

			$id = $this->body["id"];
			$config = $this->findOne(["tablename" => "config", "condition" => "id = :id", "bindparam" => [":id" => $id]]);

			if (!$config) throw new \Exception("config not found");

			$value = Sanitize::string($this->body["value"]) ?? $config["value"];

			$this->update([
				"tablename" => "config",
				"fields" => "value = :value",
				"condition" => "id = :id",
				"bindparam" => [":value" => $value, ":id" => $id]
			]);

			exit(Response::json(["status" => true, "message" => $config["keyword"] . " updated successfully"]));
		} catch (\Exception $error) {
			exit(Response::json(["status" => false, "message" => $error->getMessage()]));
		}
	}

	public function waybillfee()
	{
		try {
			Auth::checkAuth("userid");
			$id = $this->body["id"];
			$fee = Sanitize::integer($this->body["fee"]);
			$this->update([
				"tablename" => "states",
				"fields" => "`waybill_charge`=:fee",
				"condition" => "id =:id",
				"bindparam" => [":id" => $id, ":fee" => $fee]
			]);
			exit(Response::json(["status" => true, "message" => "waybill fee updated successfully"]));
		} catch (\Exception $error) {
			exit(Response::json(["status" => false, "message" => $error->getMessage()]));
		}
	}
}
