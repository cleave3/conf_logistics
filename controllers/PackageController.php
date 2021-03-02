<?php

namespace App\controllers;


use App\utils\Session;
use App\middleware\Auth;
use App\utils\Response;

class PackageController extends Controller
{

	protected function getClientId()
	{
		// Session::start();
		Auth::checkAuth("clientid");
		return Session::get("clientid");
	}

	public function getPackagesByClientId()
	{
		try {
			$id = $this->getClientId();
			$package = $this->findAll([
				"tablename" => "package",
				"condition" => "client_id = :id",
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
		// {
		// 	"title": "Package one",
		// 	"weight": "3",
		// 	"destination": "Benin",
		// 	"description": "PAckage one",
		// 	"instructions": "test",
		//drivernumber
		//transportcompany
		//image
		// 	"item": [
		// 		"21",
		// 		"32"
		// 	],
		// 	"cost": [
		// 		"2000",
		// 		"2000"
		// 	],
		// 	"quantity": [
		// 		"5",
		// 		"4"
		// 	]
		// }
		exit(Response::json($this->body));
	}

	public function edit()
	{
	}

	public function delete()
	{
	}
}
