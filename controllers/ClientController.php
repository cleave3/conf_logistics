<?php

namespace App\controllers;

use App\config\DotEnv;

use App\middleware\Auth;
use App\services\MailService;
use App\utils\EmailTemplate;
use App\utils\File;
use App\utils\Response;
use App\utils\Sanitize;
use App\utils\Session;
use Exception;

(new DotEnv(__DIR__ . '/../.env'))->load();

class ClientController extends Controller
{

	public function getClientId()
	{
		Session::start();
		Auth::checkAuth("clientid");
		return Session::get("clientid");
	}

	public function getclientbyemail($email, $fields = "*")
	{
		return $this->findOne([
			"tablename" => "clients",
			"condition" => "email = :email",
			"bindparam" => [":email" => $email],
			"fields" => $fields,
		]);
	}

	public function getclientbyId($id, $fields = "*")
	{
		return $this->findOne([
			"tablename" => "clients",
			"condition" => "id = :id",
			"bindparam" => [":id" => $id],
			"fields" => $fields,
		]);
	}

	public function getallclients()
	{
		Session::start();
		Auth::checkAuth("userid");

		return $this->findAll([
			"tablename" => "clients A",
			"fields" => "A.id,A.email,A.telephone,A.profile_complete,A.email_verified,A.created_at,A.updated_at, A.status, B.*",
			"joins" => "INNER JOIN client_profile B ON A.id = B.client_id"
		]);
	}

	public function getClientDetails($id)
	{
		Session::start();
		Auth::checkAuth("userid");
		return $this->findOne([
			"tablename" => "clients A",
			"condition" => "A.id = :id",
			"bindparam" => [":id" => $id],
			"fields" => "A.id,A.email,A.telephone,A.profile_complete,A.email_verified,A.status,A.created_at,A.updated_at, B.*,C.bankcode,C.accountnumber,C.accountname, D.bankname ",
			"joins" => "INNER JOIN client_profile B ON A.id = B.client_id LEFT JOIN client_account C ON A.id = C.client_id INNER JOIN banklist D ON C.bankcode = D.bankcode"
		]);
	}

	public function index()
	{
		try {
			$clients = $this->getallclients();
			return Response::json(["status" => true, "data" => $clients]);
		} catch (\Exception $error) {
			return Response::json(["status" => false, "message" => $error->getMessage()]);
		}
	}

	public function register()
	{
		try {
			$id = uniqid("conf-" . rand(10000, 99999) . "-");
			$email = Sanitize::string($this->body["email"]);
			$telephone = Sanitize::string($this->body["telephone"]);
			$password = Sanitize::string($this->body["password"]);
			$companyname = Sanitize::string($this->body["companyname"]);
			$firstname = Sanitize::string($this->body["firstname"]);
			$lastname = Sanitize::string($this->body["lastname"]);
			$state = Sanitize::string($this->body["state"]);
			$city = Sanitize::string($this->body["city"]);
			$address = Sanitize::string($this->body["address"]);

			$clientcount = $this->getCount([
				"tablename" => "clients",
				"condition" => "email = :email",
				"bindparam" => [":email" => $email]
			]);

			if ($clientcount > 0) throw new Exception("An Account with these email alread exist");

			$client = $this->create([
				"tablename" => "clients",
				"fields" => "`id`,`email`, `telephone`, `password`",
				"values" => ":id,:email,:telephone,:password",
				"bindparam" => [":id" => $id, ":email" => $email, ":telephone" => $telephone, ":password" => Auth::genHash($password)]
			]);

			$profile = $this->create([
				"tablename" => "client_profile",
				"fields" => "`client_id`, `companyname`, `firstname`, `lastname`,state, city_town,address",
				"values" => ":client_id,:companyname,:firstname,:lastname,:state,:city,:address",
				"bindparam" => [":client_id" => $id, ":companyname" => $companyname, ":firstname" => $firstname, ":lastname" => $lastname, ":state" => $state, ":city" => $city, ":address" => $address]
			]);

			if (!$client && !$profile) throw new Exception("Registration unsuccessful");

			$token = Auth::genToken(["id" => $id, "email" => $email]);
			$confirmationlink = getenv("BASE_URL") . "/clients/verifyemail?token=$token";
			$template = EmailTemplate::welcome($email, $confirmationlink);

			MailService::sendMail($email, "Email Verification", $template);

			exit(Response::json(["status" => true, "message" => "Registration successful, Check your email for for a verification link"]));
		} catch (\Exception $error) {
			exit(Response::json(["status" => false, "message" => $error->getMessage()]));
		}
	}

	public function verifyEmail()
	{
		try {
			$token = $this->query["token"];

			$decode = Auth::decodeToken($token);
			$email = $decode["email"];

			$client = $this->getclientbyemail($email);

			if ($client["email_verified"] === "YES") throw new Exception("Email is already verified");

			$update = $this->update([
				"tablename" => "clients",
				"fields" => "email_verified = :status",
				"condition" => "email = :email",
				"bindparam" => [":status" => "YES", ":email" => $email]
			]);

			if (!$update) throw new Exception("Email verification failed, try again");

			return Response::send(["status" => true, "message" => "Email verification successful"]);
		} catch (\Exception $error) {
			return Response::send(["status" => false, "message" => $error->getMessage()]);
		}
	}

	public function login()
	{
		try {
			$email = $this->body["email"];
			$password = $this->body["password"];

			$client = $this->findOne([
				"tablename" => "clients A",
				"condition" => "email = :email",
				"bindparam" => [":email" => $email],
				"fields" => "A.*, B.firstname,B.lastname,B.companyname,B.image",
				"joins" => "INNER JOIN client_profile B ON A.id = B.client_id"
			]);

			if (!$client) throw new Exception("Invalid email or password");
			if ($client["email_verified"] != "YES") throw new Exception("Your account is unverified, check your email for your verification link");
			if ($client["status"] !== "active") throw new \Exception("You account has been deactivated, Contact Admin");

			if (!Auth::verifyHash($password, $client["password"])) throw new Exception("Invalid email or password");

			$token = Auth::genToken(["clientid" => $client["id"], "role" => "client", "email" => $email]);

			Session::destroy();
			Session::start();
			Session::set([
				"clientid" => $client["id"],
				"auth" => $token,
				"username" => $client["firstname"] . " " . $client["lastname"],
				"companyname" => $client["companyname"],
				"image" => $client["image"],
				"emailverified" => $client["email_verified"],
				"profileverified" => $client["profile_complete"]
			]);

			exit(Response::json(["status" => true, "message" => "Login successful, Welcome " . $client["firstname"], "token" => $token]));
		} catch (\Exception $error) {
			exit(Response::json(["status" => false, "message" => $error->getMessage()]));
		}
	}

	public function forgotpassword()
	{
		try {
			$email = $this->body["email"];
			$client = $this->findOne([
				"tablename" => "clients A",
				"condition" => "email = :email",
				"bindparam" => [":email" => $email],
				"fields" => "A.email, B.firstname,B.companyname",
				"joins" => "INNER JOIN client_profile B ON A.id = B.client_id"
			]);

			if (!$client) throw new Exception("Email does not match any record");


			$authtoken = base64_encode($email);
			$resettoken = "CONF-" . rand(100000, 999999);
			$exp = strtotime("+20 minutes");

			$update = $this->update([
				"tablename" => "clients",
				"fields" => "reset_token = :resettoken, token_expiration = :tokenexpiration",
				"condition" => "email = :email",
				"bindparam" => [":resettoken" => $resettoken, ":tokenexpiration" => $exp, ":email" => $email]
			]);

			if (!$update) throw new Exception("Oops !!!, Something went wrong, operation failed");

			$confirmationlink = getenv("BASE_URL") . "/clients/resetpassword?ce=$authtoken";

			$template = EmailTemplate::forgotpassword($client["firstname"], $resettoken, $confirmationlink);
			MailService::sendMail($email, "Reset Password", $template);

			Session::destroy();
			Session::start();
			Session::set(["ce" => $authtoken]);

			exit(Response::json(["status" => true, "message" => "An check your email for the password reset link"]));
		} catch (\Exception $error) {
			exit(Response::json(["status" => false, "message" => $error->getMessage()]));
		}
	}

	public function resetpassword()
	{
		try {
			$email = $this->body["email"];
			$token = $this->body["token"];

			$client = $this->getclientbyemail($email, "reset_token, token_expiration");
			if (!$client) throw new Exception("client Account not found");

			if ($token != $client["reset_token"]) throw new Exception("invalid token");

			$now = strtotime("now");
			if (floatval($now) > floatval($client["token_expiration"])) throw new Exception("token expired");

			$password = Auth::genHash($this->body["password"]);

			$update = $this->update([
				"tablename" => "clients",
				"fields" => "password = :password, reset_token = :resettoken, token_expiration = :tokenexpiration",
				"condition" => "email = :email",
				"bindparam" => [":password" => $password, ":resettoken" => "", ":tokenexpiration" => "",  ":email" => $email]
			]);

			if (!$update) throw new Exception("Oops !!!, Something went wrong, operation failed");

			exit(Response::json(["status" => true, "message" => "Password reset successful, Proceed to login"]));
		} catch (\Exception $error) {
			exit(Response::json(["status" => false, "message" => $error->getMessage()]));
		}
	}

	public function changepassword()
	{
		try {
			$id = $this->getClientId();
			$client = $this->getclientbyId($id, "password");

			if (!$client) throw new Exception("client Account not found");
			if (!Auth::verifyHash($this->body["currentpassword"], $client["password"])) throw new Exception("current password is invalid");

			$this->update([
				"tablename" => "clients",
				"fields" => "password = :password",
				"condition" => "id = :id",
				"bindparam" => [":password" => Auth::genHash($this->body["newpassword"]), ":id" => $id]
			]);


			exit(Response::json(["status" => true, "message" => "Password changed successfully"]));
		} catch (\Exception $error) {
			exit(Response::json(["status" => false, "message" => $error->getMessage()]));
		}
	}

	public function profile()
	{
		try {
			Auth::checkAuth("clientid");
			$id = Session::get("clientid");

			$client = $this->findOne([
				"tablename" => "clients A",
				"condition" => "A.id = :id",
				"bindparam" => [":id" => $id],
				"fields" => "A.id,A.email,A.telephone,A.profile_complete,A.email_verified,A.created_at,A.updated_at, B.*,C.bankcode,C.accountnumber,C.accountname ",
				"joins" => "INNER JOIN client_profile B ON A.id = B.client_id LEFT JOIN client_account C ON A.id = C.client_id"
			]);
			return Response::send(["status" => true, "data" => $client]);
		} catch (\Exception $error) {
			return Response::send(["status" => false, "message" => $error->getMessage()]);
		}
	}

	public function updateprofile()
	{
		try {
			$id = $this->getClientId();
			$client = $this->findOne([
				"tablename" => "clients A",
				"condition" => "id = :id",
				"bindparam" => [":id" => $id],
				"fields" => "A.id,A.email,A.telephone,A.profile_complete,A.email_verified,A.created_at,A.updated_at, B.*",
				"joins" => "INNER JOIN client_profile B ON A.id = B.client_id"
			]);

			if (!$client) throw new Exception("client Account not found");

			$telephone = $this->body["telephone"] ?? $client["telephone"];
			$firstname = $this->body["firstname"] ?? $client["firstname"];
			$lastname = $this->body["lastname"] ?? $client["lastname"];
			$address = $this->body["address"] ?? $client["address"];
			$bio = $this->body["bio"] ?? $client["bio"];
			$state = $this->body["state"] ?? $client["state"];
			$lga = $this->body["city"] ?? $client["city_town"];


			$this->update([
				"tablename" => "clients",
				"fields" => "telephone = :telephone, profile_complete = :complete",
				"condition" => "id = :id",
				"bindparam" => [":telephone" => $telephone, ":complete" => "YES", ":id" => $id]
			]);

			$this->update([
				"tablename" => "client_profile",
				"fields" => "firstname = :firstname, lastname = :lastname, address = :address, bio = :bio, state = :state, city_town = :lga",
				"condition" => "client_id = :id",
				"bindparam" => [":firstname" => $firstname, ":lastname" => $lastname, ":address" => $address, ":bio" => $bio, ":state" => $state, ":lga" => $lga, ":id" => $id]
			]);

			exit(Response::json(["status" => true, "message" => "profile updated successfully"]));
		} catch (\Exception $error) {
			exit(Response::json(["status" => false, "message" => $error->getMessage()]));
		}
	}

	public function updatephoto()
	{
		try {
			$id = $this->getClientId();
			$image = File::upload(["file" => $this->file["image"], "path" => __DIR__ . "/../files/photo/"]);

			$this->update([
				"tablename" => "client_profile",
				"fields" => "image = :image",
				"condition" => "client_id = :id",
				"bindparam" => [":image" => $image, ":id" => $id]
			]);

			exit(Response::json(["status" => true, "message" => "photo updated successfully"]));
		} catch (\Exception $error) {
			exit(Response::json(["status" => false, "message" => $error->getMessage()]));
		}
	}

	public function submitbankdetails()
	{
		try {
			$id = $this->getClientId();


			$accountdetails = $this->findOne([
				"tablename" => "client_account",
				"condition" => "client_id = :id",
				"bindparam" => [":id" => $id]
			]);

			$bankcode = $this->body["bankcode"] ?? $accountdetails["bankcode"];
			$accountnumber = $this->body["accountnumber"] ?? $accountdetails["accountnumber"];
			$accountname = $this->body["accountname"] ?? $accountdetails["accountname"];

			if (!$accountdetails) {
				$this->create([
					"tablename" => "client_account",
					"fields" => "`client_id`,`bankcode`, `accountnumber`, `accountname`",
					"values" => ":id,:bankcode,:accountnumber,:accountname",
					"bindparam" => [":id" => $id, ":bankcode" => $bankcode, ":accountnumber" => $accountnumber, "accountname" => $accountname]
				]);

				exit(Response::json(["status" => true, "message" => "Account details submitted successfully"]));
			}

			$this->update([
				"tablename" => "client_account",
				"fields" => "bankcode = :bankcode,accountnumber = :accountnumber,accountname = :accountname",
				"condition" => "client_id = :id",
				"bindparam" => [":bankcode" => $bankcode, ":accountnumber" => $accountnumber, "accountname" => $accountname, ":id" => $id]
			]);

			exit(Response::json(["status" => true, "message" => "Account details updated successfully"]));
		} catch (\Exception $error) {
			exit(Response::json(["status" => false, "message" => $error->getMessage()]));
		}
	}

	public function status()
	{
		try {
			Auth::checkAuth("userid");
			$id = Sanitize::string($this->body["clientid"]);
			$status = Sanitize::string($this->body["status"]);

			if (!$this->getclientbyId($id)) throw new \Exception("Client not found");

			$this->update([
				"tablename" => "clients",
				"fields" => "status = :status",
				"condition" => "id = :id",
				"bindparam" => [":status" => $status, ":id" => $id]
			]);

			exit(Response::json(["status" => true, "message" => "client status updated successfully"]));
		} catch (\Exception $error) {
			exit(Response::json(["status" => false, "message" => $error->getMessage()]));
		}
	}
}
