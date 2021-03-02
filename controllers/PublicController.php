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

			$cities = $this->findAll([
				"tablename" => "cities",
				"condition" => "state = :state",
				"bindparam" => [":state" => $this->query["state"]]
			]);

			exit(Response::json(["status" => true, "data" => $cities]));
		} catch (\Exception $error) {
			exit(Response::json(["status" => false, "message" => $error->getMessage()]));
		}
	}

	public function cityobject($state)
	{
		try {

			if (empty($state)) throw new \Exception("state is required");

			$cities = $this->findAll([
				"tablename" => "cities",
				"condition" => "state = :state",
				"bindparam" => [":state" => $this->query["state"] ?? $state]
			]);

			return Response::send(["status" => true, "data" => $cities]);
		} catch (\Exception $error) {
			return Response::send(["status" => false, "message" => $error->getMessage()]);
		}
	}
}
