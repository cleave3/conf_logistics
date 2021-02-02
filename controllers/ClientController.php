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

	public function getclientbyemail($email, $fields = "*")
	{
		return $this->findOne([
			"tablename" => "clients",
			"condition" => "email = :email",
			"bindparam" => [":email" => $email],
			"fields" => $fields,
		]);
	}

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

		$client = $this->getclientbyemail($email);

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

		$token = Auth::genToken(["clientid" => $client["id"], "role" => "client", "email" => $email]);

		Session::destroy();
		Session::start();
		Session::set([
			"clientid" => $client["id"],
			"auth" => $token,
			"username" => $client["firstname"],
			"companyname" => $client["companyname"],
			"emailverified" => $client["email_verified"],
			"profileverified" => $client["profile_complete"]
		]);

		exit(Response::json(["status" => true, "message" => "Login successful, Welcome " . $client["firstname"], "token" => $token]));
	}

	public function forgotpassword()
	{
		$email = $this->body["email"];
		$client = $this->findOne([
			"tablename" => "clients A",
			"condition" => "email = :email",
			"bindparam" => [":email" => $email],
			"fields" => "A.email, B.firstname,B.companyname",
			"joins" => "INNER JOIN client_profile B ON A.id = B.client_id"
		]);

		if (!$client) exit(Response::json(["status" => false, "message" => "Email does not match any record"]));


		$authtoken = base64_encode($email);
		$resettoken = "CONF-" . rand(100000, 999999);
		$exp = strtotime("+20 minutes");

		$update = $this->update([
			"tablename" => "clients",
			"fields" => "reset_token = :resettoken, token_expiration = :tokenexpiration",
			"condition" => "email = :email",
			"bindparam" => [":resettoken" => $resettoken, ":tokenexpiration" => $exp, ":email" => $email]
		]);

		if (!$update) {
			exit(Response::send(["status" => true, "message" => "Oops !!!, Something went wrong, operation failed"]));
		}

		$confirmationlink = getenv("BASE_URL") . "/clients/resetpassword?ce=$authtoken";

		$template = EmailTemplate::forgotpassword($client["firstname"], $resettoken, $confirmationlink);
		MailService::sendMail($email, "Reset Password", $template);

		Session::destroy();
		Session::start();
		Session::set(["ce" => $authtoken]);

		exit(Response::json(["status" => true, "message" => "An check your email for the password reset link"]));
	}

	public function resetpassword()
	{
		$email = $this->body["email"];
		$token = $this->body["token"];

		$client = $this->getclientbyemail($email, "reset_token, token_expiration");
		if (!$client) exit(Response::json(["status" => false, "message" => "client Account not found"]));

		if ($token != $client["reset_token"]) exit(Response::json(["status" => false, "message" => "invalid token"]));

		$now = strtotime("now");
		if (floatval($now) > floatval($client["token_expiration"])) exit(Response::json(["status" => false, "message" => "token expired"]));

		$password = Auth::genHash($this->body["password"]);

		$update = $this->update([
			"tablename" => "clients",
			"fields" => "password = :password, reset_token = :resettoken, token_expiration = :tokenexpiration",
			"condition" => "email = :email",
			"bindparam" => [":password" => $password, ":resettoken" => "", ":tokenexpiration" => "",  ":email" => $email]
		]);

		if (!$update) exit(Response::send(["status" => true, "message" => "Oops !!!, Something went wrong, operation failed"]));

		exit(Response::json(["status" => true, "message" => "Password reset successful, Proceed to login"]));
	}

	public function edit()
	{
	}

	public function delete()
	{
	}
}
