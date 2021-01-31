<?php

namespace App\controllers;

use App\controllers\Controller;
use App\middleware\Auth;

class AuthController extends Controller
{

	public function index()
	{

		echo Auth::genToken(["name" => "cleave", "age" =>  27]);
	}

	public function add()
	{

		echo json_encode(Auth::decodeToken("eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJuYW1lIjoiY2xlYXZlIiwiYWdlIjoyN30.GsQH2VRsRbd3X38qMVmtgBOS6aPIFfbGAoKMh26crfM"));
	}

	public function edit()
	{
	}

	public function delete()
	{
	}
}
