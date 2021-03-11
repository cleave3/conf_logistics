<?php

namespace App\controllers;

use App\config\DotEnv;

use App\middleware\Auth;
use App\services\MailService;
use App\utils\EmailTemplate;
use App\utils\File;
use App\utils\Helpers;
use App\utils\Response;
use App\utils\Sanitize;
use App\utils\Session;
use Exception;

class AuthController extends Controller
{
	public function getuserbyemail($email, $fields = "*")
	{
		return $this->findOne([
			"tablename" => "users",
			"condition" => "email = :email",
			"bindparam" => [":email" => $email],
			"fields" => $fields,
		]);
	}

	public function getuserbyId($id, $fields = "*")
	{
		return $this->findOne([
			"tablename" => "users",
			"condition" => "id = :id",
			"bindparam" => [":id" => $id],
			"fields" => $fields,
		]);
	}

	public function getUserId()
	{
		Session::start();
		Auth::checkAuth("userid");
		return Session::get("userid");
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
			$role = Sanitize::string($this->body["role"]);
			$firstname = Sanitize::string($this->body["firstname"]);
			$lastname = Sanitize::string($this->body["lastname"]);
			$address = Sanitize::string($this->body["address"]);
			$bio = Sanitize::string($this->body["bio"]);
			$state = Sanitize::string($this->body["state"]);
			$lga = Sanitize::string($this->body["city"]);
			$id = uniqid("user" . "-" . uniqid());

			$user = $this->getuserbyemail($email, "email");

			if ($user) throw new Exception("This email is already in use");

			$password = chr(rand(97, 122)) . rand(100, 999) . chr(rand(65, 90)) . rand(100, 999);
			$hash = Auth::genHash($password);

			$this->create([
				"tablename" => "users",
				"fields" => "`id`,`email`, `telephone`, `password`, `role`, `firstname`, `lastname`, `address`, `bio`, `state`, `city`",
				"values" => ":id,:email,:telephone,:password,:role,:firstname,:lastname,:address,:bio,:state,:lga",
				"bindparam" => [":id" => $id, ":email" => $email, ":telephone" => $telephone, ":password" => $hash, ":role" => $role, ":firstname" => $firstname, ":lastname" => $lastname, ":address" => $address, ":bio" => $bio, ":state" => $state, ":lga" => $lga]
			]);

			$template = EmailTemplate::welcomeuser($firstname, $email, $password);
			MailService::sendMail($email, "Welcome", $template);

			exit(Response::json(["status" => true, "message" => "Registration successfull, An email containing the login credentials has been sent to the user"]));
		} catch (\Exception $error) {
			exit(Response::json(["status" => false, "message" => $error->getMessage()]));
		}
	}

	public function login()
	{
		try {
			$email = $this->body["email"];
			$password = $this->body["password"];

			$user = $this->findOne([
				"tablename" => "users A",
				"condition" => "email = :email",
				"bindparam" => [":email" => $email],
				"fields" => "A.*, B.role as userrole,B.permissions",
				"joins" => "INNER JOIN roles B ON A.role = B.id"
			]);

			if (!$user) throw new Exception("Invalid email or password");

			if ($user["status"] !== "active") throw new Exception("Your account has been {$user["status"]}. Kindly contact your Administrator");

			if (!Auth::verifyHash($password, $user["password"])) throw new Exception("Invalid email or password");

			$token = Auth::genToken(["userid" => $user["id"], "role" => "user", "email" => $email]);

			Session::destroy();
			Session::start();
			Session::set([
				"userid" => $user["id"],
				"adminid" => $user["id"],
				"role" => $user["userrole"],
				"image" => $user["image"],
				"permissions" => $user["permissions"],
				"username" => $user["firstname"] . " " . $user["lastname"],
			]);

			exit(Response::json(["status" => true, "message" => "Login successful, Welcome " . $user["firstname"], "token" => $token]));
		} catch (\Exception $error) {
			exit(Response::json(["status" => false, "message" => $error->getMessage()]));
		}
	}

	public function forgotpassword()
	{
		try {
			$email = $this->body["email"];
			$user = $this->findOne([
				"tablename" => "users",
				"condition" => "email = :email",
				"bindparam" => [":email" => $email],
			]);

			if (!$user) throw new Exception("Email does not match any record");


			$authtoken = base64_encode($email);
			$resettoken = "CONF-" . rand(100000, 999999);
			$exp = strtotime("+20 minutes");

			$update = $this->update([
				"tablename" => "users",
				"fields" => "reset_token = :resettoken, token_expiration = :tokenexpiration",
				"condition" => "email = :email",
				"bindparam" => [":resettoken" => $resettoken, ":tokenexpiration" => $exp, ":email" => $email]
			]);

			if (!$update) throw new Exception("Oops !!!, Something went wrong, operation failed");

			$confirmationlink = getenv("BASE_URL") . "/admin/resetpassword?ce=$authtoken";

			$template = EmailTemplate::forgotpassword($user["firstname"], $resettoken, $confirmationlink);
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

			$user = $this->getuserbyemail($email, "reset_token, token_expiration");
			if (!$user) throw new Exception("user Account not found");

			if ($token != $user["reset_token"]) throw new Exception("invalid token");

			$now = strtotime("now");
			if (floatval($now) > floatval($user["token_expiration"])) throw new Exception("token expired");

			$password = Auth::genHash($this->body["password"]);

			$update = $this->update([
				"tablename" => "users",
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
			$id = $this->getUserId();
			$user = $this->getuserbyId($id, "password");

			if (!$user) throw new Exception("user Account not found");
			if (!Auth::verifyHash($this->body["currentpassword"], $user["password"])) throw new Exception("current password is invalid");

			$this->update([
				"tablename" => "users",
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
			$id = $this->getUserId();

			$user = $this->findOne([
				"tablename" => "users A",
				"condition" => "A.id = :id",
				"bindparam" => [":id" => $id],
				"fields" => "A.*, B.role as userrole,B.permissions",
				"joins" => "INNER JOIN roles B ON A.role = B.id"
			]);

			$user = Helpers::removefield($user, ["password", "reset_token", "token_expiration"]);

			return $user;
		} catch (\Exception $error) {
			return $error->getMessage();
		}
	}

	public function updateprofile()
	{
		try {
			$id = $this->getUserId();
			$user = $this->findOne([
				"tablename" => "users",
				"condition" => "id = :id",
				"bindparam" => [":id" => $id],
			]);

			if (!$user) throw new Exception("user Account not found");

			$telephone = $this->body["telephone"] ?? $user["telephone"];
			$firstname = $this->body["firstname"] ?? $user["firstname"];
			$lastname = $this->body["lastname"] ?? $user["lastname"];
			$address = $this->body["address"] ?? $user["address"];
			$bio = $this->body["bio"] ?? $user["bio"];
			$state = $this->body["state"] ?? $user["state"];
			$lga = $this->body["city"] ?? $user["city_town"];

			$this->update([
				"tablename" => "users",
				"fields" => "telephone = :telephone,firstname = :firstname, lastname = :lastname, address = :address, bio = :bio, state = :state, city = :lga",
				"condition" => "id = :id",
				"bindparam" => [":telephone" => $telephone, ":firstname" => $firstname, ":lastname" => $lastname, ":address" => $address, ":bio" => $bio, ":state" => $state, ":lga" => $lga, ":id" => $id]
			]);

			exit(Response::json(["status" => true, "message" => "profile updated successfully"]));
		} catch (\Exception $error) {
			exit(Response::json(["status" => false, "message" => $error->getMessage()]));
		}
	}

	public function updatephoto()
	{
		try {
			$id = $this->getUserId();
			$image = File::upload(["file" => $this->file["image"], "path" => __DIR__ . "/../files/photo/"]);

			$this->update([
				"tablename" => "users",
				"fields" => "image = :image",
				"condition" => "id = :id",
				"bindparam" => [":image" => $image, ":id" => $id]
			]);

			exit(Response::json(["status" => true, "message" => "photo updated successfully"]));
		} catch (\Exception $error) {
			exit(Response::json(["status" => false, "message" => $error->getMessage()]));
		}
	}

	public function getAllUsers()
	{
		return $this->findAll([
			"tablename" => "users A",
			"fields" => "A.id,A.email,A.telephone,A.role,A.firstname,A.lastname,A.address,A.bio,A.image,A.state,A.city,A.status,A.created_at,A.updated_at, B.role as userrole,B.permissions",
			"joins" => "INNER JOIN roles B ON A.role = B.id"
		]);
	}

	public function edit()
	{
		try {
			$userid = Sanitize::string($this->body["userid"]);

			$user = $this->getuserbyId($userid);

			$telephone = Sanitize::string($this->body["telephone"]) ?? $user["telephone"];
			$email = Sanitize::string($this->body["email"]) ?? $user["email"];
			$role = Sanitize::string($this->body["role"]) ?? $user["role"];
			$status = Sanitize::string($this->body["status"]) ?? $user["status"];
			$firstname = Sanitize::string($this->body["firstname"]) ?? $user["firstname"];
			$lastname = Sanitize::string($this->body["lastname"]) ?? $user["lastname"];
			$address = Sanitize::string($this->body["address"]) ?? $user["address"];
			$state = Sanitize::string($this->body["state"]) ?? $user["state"];
			$lga = Sanitize::string($this->body["city"]) ?? $user["city"];

			if (!$user) throw new Exception("user not found");

			$this->update([
				"tablename" => "users",
				"condition" => "id = :id",
				"fields" => "email = :email,telephone = :telephone,role = :role,firstname = :firstname,lastname = :lastname,address = :address,state = :state,city = :lga,status = :status",
				"bindparam" => [":id" => $userid, ":email" => $email, ":telephone" => $telephone, ":role" => $role, ":firstname" => $firstname, ":lastname" => $lastname, ":address" => $address, ":state" => $state, ":lga" => $lga, ":status" => $status]
			]);

			exit(Response::json(["status" => true, "message" => "Changes saved successfully"]));
		} catch (\Exception $error) {
			exit(Response::json(["status" => false, "message" => $error->getMessage()]));
		}
	}
}
