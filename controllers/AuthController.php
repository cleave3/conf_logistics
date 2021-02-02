<?php

namespace App\controllers;

use App\controllers\Controller;
use App\middleware\Auth;
use App\utils\Response;

class AuthController extends Controller
{

	public function index()
	{
	}

	public function add()
	{

		$insert = $this->create([
			"tablename" => "cleave",
			"fields" => "",
			"values" => "",
			"bindparam" => ""
		]);

		return Response::json($insert);
	}

	public function edit()
	{
	}

	public function delete()
	{
	}
}
