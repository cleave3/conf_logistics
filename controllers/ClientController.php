<?php

namespace App\controllers;

use App\config\DotEnv;
use App\controllers\Controller;
use App\middleware\Auth;
use App\services\MailService;
use App\utils\EmailTemplate;
use App\utils\Response;
use App\utils\Sanitize;
use App\utils\Session;

(new DotEnv(__DIR__ . '/../.env'))->load();

class ClientController extends Controller
{

	public function index()
	{
	}

	public function register()
	{
		$id = uniqid("conf-" . rand(10000, 99999) . "-");
		$email = Sanitize::string($this->body["email"]);
		$telephone = Sanitize::string($this->body["telephone"]);
		$password = Sanitize::string($this->body["password"]);
		$companyname = isset($this->body["companyname"]) ? Sanitize::string($this->body["companyname"]) : "";
		$firstname = Sanitize::string($this->body["firstname"]);
		$lastname = Sanitize::string($this->body["lastname"]);

		$clientcount = $this->getCount([
			"tablename" => "clients",
			"condition" => "email = :email",
			"bindparam" => [":email" => $email]
		]);

		if ($clientcount > 0) exit(Response::json(["status" => false, "message" => "An Account with these email alread exist"]));

		$client = $this->create([
			"tablename" => "clients",
			"fields" => "`id`,`email`, `telephone`, `password`",
			"values" => ":id,:email,:telephone,:password",
			"bindparam" => [":id" => $id, ":email" => $email, ":telephone" => $telephone, ":password" => Auth::genHash($password)]
		]);

		$profile = $this->create([
			"tablename" => "client_profile",
			"fields" => "`client_id`, `companyname`, `firstname`, `lastname`",
			"values" => ":client_id,:companyname,:firstname,:lastname",
			"bindparam" => [":client_id" => $id, ":companyname" => $companyname, ":firstname" => $firstname, ":lastname" => $lastname]
		]);

		if ($client && $profile) {
			echo Response::json(["status" => true, "message" => "Registration successful, Check your email for for a verification link"]);

			$token = Auth::genToken(["id" => $id, "email" => $email]);

			$confirmationlink = getenv("BASE_URL") . "/clients/verifyemail?token=$token";

			$template = EmailTemplate::welcome($email, $confirmationlink);
			MailService::sendMail($email, "Email Verification", $template);
		} else {
			echo Response::json(["status" => false, "message" => "Registration unsuccessful"]);
		}
	}

	public function verifyEmail()
	{
		$token = $this->query["token"];

		$decode = Auth::decodeToken($token);
		$email = $decode["email"];

		$client = $this->findOne([
			"tablename" => "clients",
			"condition" => "email = :email",
			"bindparam" => [":email" => $email]
		]);

		if ($client["email_verified"] === "YES") {
			return Response::send(["status" => true, "message" => "Email is already verified"]);
		}

		$update = $this->update([
			"tablename" => "clients",
			"fields" => "email_verified = :status",
			"condition" => "email = :email",
			"bindparam" => [":status" => "YES", ":email" => $email]
		]);

		if ($update) {
			return Response::send(["status" => true, "message" => "Email verification successful"]);
		}

		return Response::send(["status" => false, "message" => "Email verification failed, try again"]);
	}

	public function login()
	{
		$email = $this->body["email"];
		$password = $this->body["password"];

		$client = $this->findOne([
			"tablename" => "clients A",
			"condition" => "email = :email",
			"bindparam" => [":email" => $email],
			"fields" => "A.*, B.firstname,B.companyname",
			"joins" => "INNER JOIN client_profile B ON A.id = B.client_id"
		]);

		if (!$client) exit(Response::json(["status" => false, "message" => "Invalid email or password"]));

		if (!Auth::verifyHash($password, $client["password"])) exit(Response::json(["status" => false, "message" => "Invalid email or password"]));

		Session::destroy();
		Session::start();
		$val = Session::set([
			"clientid" => $client["id"],
			"auth" => Auth::genToken(["clientid" => $client["id"], "role" => "client", "email" => $email]),
			"username" => $client["firstname"],
			"companyname" => $client["companyname"],
			"emailverified" => $client["email_verified"],
			"profileverified" => $client["profile_complete"]
		]);

		exit(Response::json(["status" => true, "message" => "login successful", "sesss" => $val]));
	}

	public function edit()
	{
	}

	public function delete()
	{
	}
}
