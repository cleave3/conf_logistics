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
	}

	public function edit()
	{
	}

	public function delete()
	{
	}
}
