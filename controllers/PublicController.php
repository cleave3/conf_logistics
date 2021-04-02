<?php

namespace App\controllers;


use App\utils\Response;


class PublicController extends Controller
{
	public function states()
	{
		try {
			$states = $this->findAll(["tablename" => "states"]);

			return Response::send(["status" => true, "data" => $states]);
		} catch (\Exception $error) {
			return Response::send(["status" => false, "message" => $error->getMessage()]);
		}
	}

	public function allstates()
	{
		return $this->findAll(["tablename" => "states"]);
	}

	public function state()
	{
		try {
			$id = $this->query["stateid"];
			$states = $this->findOne(["tablename" => "states", "condition" => "id = :id", "bindparam" => [":id" => $id]]);

			return Response::json(["status" => true, "data" => $states]);
		} catch (\Exception $error) {
			return Response::json(["status" => false, "message" => $error->getMessage()]);
		}
	}

	public function getBankList()
	{
		try {
			$banks = $this->findAll(["tablename" => "banklist"]);

			return Response::send(["status" => true, "data" => $banks]);
		} catch (\Exception $error) {
			return Response::send(["status" => false, "message" => $error->getMessage()]);
		}
	}

	public function cities()
	{
		try {

			if (!isset($this->query["state"])) throw new \Exception("state not found in query param");

			$cities = $this->cityobject($this->query["state"]);

			exit(Response::json(["status" => true, "data" => $cities]));
		} catch (\Exception $error) {
			exit(Response::json(["status" => false, "message" => $error->getMessage()]));
		}
	}

	public function cityobject($state)
	{
		return $this->findAll([
			"tablename" => "cities",
			"condition" => "state = :state",
			"bindparam" => [":state" => $state]
		]);
	}

	public function getActiveLocations()
	{
		try {
			return $this->findAll(["tablename" => "locations", "condition" => "status = 'active'"]);
		} catch (\Exception $error) {
			return $error->getMessage();
		}
	}

	public function getAllLocations()
	{
		try {
			return $this->findAll(["tablename" => "locations"]);
		} catch (\Exception $error) {
			return $error->getMessage();
		}
	}

	public function getDeliveryPriceListByState($state)
	{
		return $this->findAll([
			"tablename" => "delivery_pricing",
			"condition" => "state_id = :state",
			"bindparam" => [":state" => $state]
		]);
	}

	public function deliverypricing()
	{
		try {
			$pricelist = $this->getDeliveryPriceListByState($this->query["stateid"]);
			return Response::json(["status" => true, "data" => $pricelist]);
		} catch (\Exception $error) {
			return Response::json(["status" => false, "message" => $error->getMessage()]);
		}
	}

	public function getStatesForDelivery()
	{
		return $this->findAll([
			"tablename" => "delivery_pricing A",
			"conditions" => "1 GROUP BY A.state_id ORDER BY B.state ASC",
			"fields" => "DISTINCT A.state_id, B.id, B.state",
			"joins" => "INNER JOIN states B ON A.state_id = B.id"
		]);
	}
}
