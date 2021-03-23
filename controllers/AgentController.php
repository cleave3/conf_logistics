<?php

namespace App\controllers;

use App\middleware\Auth;
use App\services\MailService;
use App\utils\EmailTemplate;
use App\utils\File;
use App\utils\Helpers;
use App\utils\Response;
use App\utils\Sanitize;
use App\utils\Session;
use Exception;

class AgentController extends Controller
{
	public function getagentbyemail($email, $fields = "*")
	{
		return $this->findOne([
			"tablename" => "agents",
			"condition" => "email = :email",
			"bindparam" => [":email" => $email],
			"fields" => $fields,
		]);
	}

	public function getAgentbyId($id, $fields = "*")
	{
		return $this->findOne([
			"tablename" => "agents",
			"condition" => "id = :id",
			"bindparam" => [":id" => $id],
			"fields" => $fields,
		]);
	}

	public function getagentId()
	{
		Session::start();
		Auth::checkAuth("agentid");
		return Session::get("agentid");
	}

	public function getAllRoles()
	{
		return $this->findAll(["tablename" => "roles", "fields" => "id,role"]);
	}

	public function register()
	{
		try {
			$telephone = Sanitize::string($this->body["telephone"]);
			$email = Sanitize::string($this->body["email"]);
			$firstname = Sanitize::string($this->body["firstname"]);
			$lastname = Sanitize::string($this->body["lastname"]);
			$address = Sanitize::string($this->body["address"]);
			$bio = Sanitize::string($this->body["bio"]);
			$state = Sanitize::string($this->body["state"]);
			$lga = Sanitize::string($this->body["city"]);
			$id = uniqid("agent" . "-" . uniqid());

			$agent = $this->getagentbyemail($email, "email");

			if ($agent) throw new Exception("This email is already in use");

			$password = chr(rand(97, 122)) . rand(100, 999) . chr(rand(65, 90)) . rand(100, 999);
			$hash = Auth::genHash($password);

			$this->create([
				"tablename" => "agents",
				"fields" => "`id`,`email`, `telephone`, `password`,`firstname`, `lastname`, `address`, `bio`, `state`, `city`",
				"values" => ":id,:email,:telephone,:password,:firstname,:lastname,:address,:bio,:state,:lga",
				"bindparam" => [":id" => $id, ":email" => $email, ":telephone" => $telephone, ":password" => $hash, ":firstname" => $firstname, ":lastname" => $lastname, ":address" => $address, ":bio" => $bio, ":state" => $state, ":lga" => $lga]
			]);

			$template = EmailTemplate::welcomeagent($firstname, $email, $password);
			MailService::sendMail($email, "Welcome", $template);

			exit(Response::json(["status" => true, "message" => "Registration successfull, An email containing the login credentials has been sent to the agent"]));
		} catch (\Exception $error) {
			exit(Response::json(["status" => false, "message" => $error->getMessage()]));
		}
	}

	public function login()
	{
		try {
			$email = $this->body["email"];
			$password = $this->body["password"];

			$agent = $this->findOne([
				"tablename" => "agents",
				"condition" => "email = :email",
				"bindparam" => [":email" => $email],
			]);

			if (!$agent) throw new Exception("Invalid email or password");

			if ($agent["status"] !== "active") throw new Exception("Your account has been {$agent["status"]}. Kindly contact your Administrator");

			if (!Auth::verifyHash($password, $agent["password"])) throw new Exception("Invalid email or password");

			$token = Auth::genToken(["agentid" => $agent["id"], "role" => "agent", "email" => $email]);

			Session::destroy();
			Session::start();
			Session::set([
				"agentid" => $agent["id"],
				"image" => $agent["image"],
				"agentname" => $agent["firstname"] . " " . $agent["lastname"],
			]);

			exit(Response::json(["status" => true, "message" => "Login successful, Welcome " . $agent["firstname"], "token" => $token]));
		} catch (\Exception $error) {
			exit(Response::json(["status" => false, "message" => $error->getMessage()]));
		}
	}

	public function forgotpassword()
	{
		try {
			$email = $this->body["email"];
			$agent = $this->findOne([
				"tablename" => "agents",
				"condition" => "email = :email",
				"bindparam" => [":email" => $email],
			]);

			if (!$agent) throw new Exception("Email does not match any record");


			$authtoken = base64_encode($email);
			$resettoken = "CONF-" . rand(100000, 999999);
			$exp = strtotime("+20 minutes");

			$update = $this->update([
				"tablename" => "agents",
				"fields" => "reset_token = :resettoken, token_expiration = :tokenexpiration",
				"condition" => "email = :email",
				"bindparam" => [":resettoken" => $resettoken, ":tokenexpiration" => $exp, ":email" => $email]
			]);

			if (!$update) throw new Exception("Oops !!!, Something went wrong, operation failed");

			$confirmationlink = getenv("BASE_URL") . "/admin/resetpassword?ce=$authtoken";

			$template = EmailTemplate::forgotpassword($agent["firstname"], $resettoken, $confirmationlink);
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

			$agent = $this->getagentbyemail($email, "reset_token, token_expiration");
			if (!$agent) throw new Exception("agent Account not found");

			if ($token != $agent["reset_token"]) throw new Exception("invalid token");

			$now = strtotime("now");
			if (floatval($now) > floatval($agent["token_expiration"])) throw new Exception("token expired");

			$password = Auth::genHash($this->body["password"]);

			$update = $this->update([
				"tablename" => "agents",
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
			$id = $this->getagentId();
			$agent = $this->getAgentbyId($id, "password");

			if (!$agent) throw new Exception("agent Account not found");
			if (!Auth::verifyHash($this->body["currentpassword"], $agent["password"])) throw new Exception("current password is invalid");

			$this->update([
				"tablename" => "agents",
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
		$id = $this->getagentId();

		$agent = $this->findOne([
			"tablename" => "agents A",
			"condition" => "A.id = :id",
			"fields" => "A.*,B.bankcode,B.accountnumber,B.accountname",
			"bindparam" => [":id" => $id],
			"joins" => "INNER JOIN agent_account B ON A.id = B.agent_id"
		]);

		$agent = Helpers::removefield($agent, ["password", "reset_token", "token_expiration"]);

		return $agent;
	}

	public function updateprofile()
	{
		try {
			$id = $this->getagentId();
			$agent = $this->findOne(["tablename" => "agents", "condition" => "id = :id", "bindparam" => [":id" => $id]]);

			if (!$agent) throw new Exception("agent Account not found");

			$telephone = $this->body["telephone"] ?? $agent["telephone"];
			$firstname = $this->body["firstname"] ?? $agent["firstname"];
			$lastname = $this->body["lastname"] ?? $agent["lastname"];
			$address = $this->body["address"] ?? $agent["address"];
			$bio = $this->body["bio"] ?? $agent["bio"];
			$state = $this->body["state"] ?? $agent["state"];
			$lga = $this->body["city"] ?? $agent["city_town"];

			$this->update([
				"tablename" => "agents",
				"fields" => "telephone = :telephone,firstname = :firstname, lastname = :lastname, address = :address, bio = :bio, state = :state, city = :lga",
				"condition" => "id = :id",
				"bindparam" => [":telephone" => $telephone, ":firstname" => $firstname, ":lastname" => $lastname, ":address" => $address, ":bio" => $bio, ":state" => $state, ":lga" => $lga, ":id" => $id]
			]);

			$agent = $this->findOne(["tablename" => "agents", "condition" => "id = :id", "bindparam" => [":id" => $id]]);

			Session::start();
			Session::set([
				"image" => $agent["image"],
				"agentname" => $agent["firstname"] . " " . $agent["lastname"],
			]);

			exit(Response::json(["status" => true, "message" => "profile updated successfully"]));
		} catch (\Exception $error) {
			exit(Response::json(["status" => false, "message" => $error->getMessage()]));
		}
	}

	public function updatephoto()
	{
		try {
			$id = $this->getagentId();
			$image = File::upload(["file" => $this->file["image"], "path" => __DIR__ . "/../files/photo/"]);

			$this->update([
				"tablename" => "agents",
				"fields" => "image = :image",
				"condition" => "id = :id",
				"bindparam" => [":image" => $image, ":id" => $id]
			]);

			exit(Response::json(["status" => true, "message" => "photo updated successfully"]));
		} catch (\Exception $error) {
			exit(Response::json(["status" => false, "message" => $error->getMessage()]));
		}
	}

	public function getAllAgents()
	{
		return $this->findAll([
			"tablename" => "agents A",
			"fields" => "A.id,A.email,A.telephone,A.firstname,A.lastname,A.address,A.bio,A.image,A.state,A.city,A.status,A.created_at,A.updated_at",
		]);
	}

	public function edit()
	{
		try {
			$agentid = Sanitize::string($this->body["agentid"]);

			$agent = $this->getAgentbyId($agentid);

			$telephone = Sanitize::string($this->body["telephone"]) ?? $agent["telephone"];
			$email = Sanitize::string($this->body["email"]) ?? $agent["email"];
			$status = Sanitize::string($this->body["status"]) ?? $agent["status"];
			$firstname = Sanitize::string($this->body["firstname"]) ?? $agent["firstname"];
			$lastname = Sanitize::string($this->body["lastname"]) ?? $agent["lastname"];
			$address = Sanitize::string($this->body["address"]) ?? $agent["address"];
			$state = Sanitize::string($this->body["state"]) ?? $agent["state"];
			$lga = Sanitize::string($this->body["city"]) ?? $agent["city"];

			if (!$agent) throw new Exception("agent not found");

			$this->update([
				"tablename" => "agents",
				"condition" => "id = :id",
				"fields" => "email = :email,telephone = :telephone,firstname = :firstname,lastname = :lastname,address = :address,state = :state,city = :lga,status = :status",
				"bindparam" => [":id" => $agentid, ":email" => $email, ":telephone" => $telephone, ":firstname" => $firstname, ":lastname" => $lastname, ":address" => $address, ":state" => $state, ":lga" => $lga, ":status" => $status]
			]);

			exit(Response::json(["status" => true, "message" => "Changes saved successfully"]));
		} catch (\Exception $error) {
			exit(Response::json(["status" => false, "message" => $error->getMessage()]));
		}
	}

	public function submitbankdetails()
	{
		try {
			$id = $this->getagentId();

			$accountdetails = $this->findOne([
				"tablename" => "agent_account",
				"condition" => "agent_id = :id",
				"bindparam" => [":id" => $id]
			]);

			$bankcode = $this->body["bankcode"] ?? $accountdetails["bankcode"];
			$accountnumber = $this->body["accountnumber"] ?? $accountdetails["accountnumber"];
			$accountname = $this->body["accountname"] ?? $accountdetails["accountname"];

			if (!$accountdetails) {
				$this->create([
					"tablename" => "agent_account",
					"fields" => "`agent_id`,`bankcode`, `accountnumber`, `accountname`",
					"values" => ":id,:bankcode,:accountnumber,:accountname",
					"bindparam" => [":id" => $id, ":bankcode" => $bankcode, ":accountnumber" => $accountnumber, "accountname" => $accountname]
				]);

				exit(Response::json(["status" => true, "message" => "Account details submitted successfully"]));
			}

			$this->update([
				"tablename" => "agent_account",
				"fields" => "bankcode = :bankcode,accountnumber = :accountnumber,accountname = :accountname",
				"condition" => "agent_id = :id",
				"bindparam" => [":bankcode" => $bankcode, ":accountnumber" => $accountnumber, "accountname" => $accountname, ":id" => $id]
			]);

			exit(Response::json(["status" => true, "message" => "Account details updated successfully"]));
		} catch (\Exception $error) {
			exit(Response::json(["status" => false, "message" => $error->getMessage()]));
		}
	}
}
