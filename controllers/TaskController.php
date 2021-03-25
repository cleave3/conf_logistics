<?php

namespace App\controllers;

use App\middleware\Auth;
use App\utils\Response;
use App\utils\Sanitize;
use App\utils\Session;

class TaskController extends Controller
{
	protected $config;

	public function __construct()
	{
		parent::__construct();
		$this->config = new ConfigController();
	}

	public function search()
	{
		try {
			Auth::checkAuth("userid");
			$status = $this->query["status"] === "all" ? "%" : Sanitize::string($this->query["status"]);
			$startdate = empty($this->query["startdate"]) ? "2000-01-01 00:00:00" : trim($this->query["startdate"]) . " 00:00:00";
			$enddate = empty($this->query["enddate"]) ? date("Y-m-d") . " 23:59:59" : trim($this->query["enddate"]) . " 23:59:59";
			$agent = $this->query["agent"];

			if ($agent === "all") {
				$tasks = $this->exec_query("SELECT A.customer,A.telephone as customertelephone,A.address as deliveryaddress, A.totalamount,A.delivery_fee,A.status as orderstatus,A.description,A.id as order_id,B.agentfee,B.sendpayment,B.agentpayment,B.created_at,B.updated_at, C.state, D.city, E.telephone as sellertelephone, F.companyname as seller, (SELECT CONCAT(firstname, ' ', lastname) FROM users WHERE id = B.user_id) assigner, (SELECT CONCAT(firstname,' ', lastname) FROM agents WHERE id = B.agent_id) as assignee FROM `orders` A INNER JOIN tasks B ON A.id = B.order_id INNER JOIN states C ON A.state_id = C.id INNER JOIN cities D ON A.city_id = D.id INNER JOIN clients E ON A.client_id = E.id INNER JOIN client_profile F ON A.client_id = F.client_id WHERE A.status LIKE '%$status%' AND B.created_at BETWEEN '$startdate' AND '$enddate'");
			} else {
				$tasks = $this->exec_query("SELECT A.customer,A.telephone as customertelephone,A.address as deliveryaddress, A.totalamount,A.delivery_fee,A.status as orderstatus,A.description,A.id as order_id,B.agentfee,B.sendpayment,B.agentpayment,B.created_at,B.updated_at, C.state, D.city, E.telephone as sellertelephone, F.companyname as seller, (SELECT CONCAT(firstname, ' ', lastname) FROM users WHERE id = B.user_id) assigner, (SELECT CONCAT(firstname,' ', lastname) FROM agents WHERE id = B.agent_id) as assignee FROM `orders` A INNER JOIN tasks B ON A.id = B.order_id INNER JOIN states C ON A.state_id = C.id INNER JOIN cities D ON A.city_id = D.id INNER JOIN clients E ON A.client_id = E.id INNER JOIN client_profile F ON A.client_id = F.client_id WHERE A.status LIKE '%$status%' AND B.agent_id = '$agent' AND B.created_at BETWEEN '$startdate' AND '$enddate'");
			}
			exit(Response::json(["status" => true, "data" => $tasks, "message" => count($tasks) . " results found for search"]));
		} catch (\Exception $error) {
			exit(Response::json(["status" => false, "data" => [], "message" => $error->getMessage()]));
		}
	}

	public function getAllTasks()
	{
		try {
			$tasks = $this->exec_query("SELECT A.customer,A.telephone as customertelephone,A.address as deliveryaddress, A.totalamount,A.delivery_fee,A.status as orderstatus,A.description,B.*, C.state, D.city, E.telephone as sellertelephone, F.companyname as seller, (SELECT CONCAT(firstname, ' ', lastname) FROM users WHERE id = B.user_id) assigner, (SELECT CONCAT(firstname,' ', lastname) FROM agents WHERE id = B.agent_id) as assignee FROM `orders` A INNER JOIN tasks B ON A.id = B.order_id INNER JOIN states C ON A.state_id = C.id INNER JOIN cities D ON A.city_id = D.id INNER JOIN clients E ON A.client_id = E.id INNER JOIN client_profile F ON A.client_id = F.client_id");
			exit(Response::json(["status" => true, "data" => $tasks]));
		} catch (\Exception $error) {
			exit(Response::json(["status" => false, "message" => $error->getMessage()]));
		}
	}

	public function getAssignableTasks()
	{
		return $this->exec_query("SELECT A.customer,A.telephone as customertelephone,A.address as deliveryaddress, A.totalamount,A.delivery_fee,A.status as orderstatus,A.description,A.id as order_id,B.agentfee,B.sendpayment,B.agentpayment,B.created_at,B.updated_at, C.state, D.city, E.telephone as sellertelephone, F.companyname as seller, (SELECT CONCAT(firstname, ' ', lastname) FROM users WHERE id = B.user_id) assigner, (SELECT CONCAT(firstname,' ', lastname) FROM agents WHERE id = B.agent_id) as assignee FROM `orders` A LEFT JOIN tasks B ON A.id = B.order_id INNER JOIN states C ON A.state_id = C.id INNER JOIN cities D ON A.city_id = D.id INNER JOIN clients E ON A.client_id = E.id INNER JOIN client_profile F ON A.client_id = F.client_id WHERE A.status NOT IN ('cancelled','delivered', 'intransit')
			");
	}

	public function add($agentid, $orderid)
	{
		Auth::checkAuth("userid");
		$userid = Session::get("userid");
		$agentfee = Sanitize::integer($this->config->getConfig("AGENT FEE")["value"]);
		return $this->create([
			"tablename" => "tasks",
			"fields" => "`agent_id`, `order_id`, `user_id`, `agentfee`",
			"values" => ":agentid,:orderid,:userid,:agentfee",
			"bindparam" => ["agentid" => $agentid, ":orderid" => $orderid, ":userid" => $userid, "agentfee" => $agentfee]
		]);
	}

	public function edit($taskid, $agentid, $orderid, $sendpayment, $agentpayment)
	{
		Auth::checkAuth("userid");
		$userid = Session::get("userid");
		$agentfee = Sanitize::integer($this->config->getConfig("AGENT FEE")["value"]);
		return $this->update([
			"tablename" => "tasks",
			"fields" => "`agent_id`=:agentid, `order_id`=:orderid, `user_id`=:userid, `agentfee`=:agentfee,`sendpayment`=:sendpayment,`agentpayment`=:agentpayment",
			"condition" => "id =:taskid",
			"bindparam" => [":taskid" => $taskid, "agentid" => $agentid, ":orderid" => $orderid, ":userid" => $userid, "agentfee" => $agentfee, ":sendpayment" => $sendpayment, "agentpayment" => $agentpayment]
		]);
	}

	public function submit()
	{
		try {
			$agentid = Sanitize::string($this->body["agentid"]);

			$agent = $this->findOne(["tablename" => "agents", "condition" => "id = :agentid", "bindparam" => [":agentid" => $agentid]]);

			if (!$agent) throw new \Exception("Agent not found");
			if ($agent["status"] !== "active") throw new \Exception("unable to assign as this agent as been deactivated");

			$items = explode(",", $this->body["items"]);
			$ordercount = count($items);
			if ($ordercount < 1) throw new \Exception("No order was selected");

			$submitted = 0;

			for ($i = 0; $i < $ordercount; $i++) {
				$orderid = $items[$i];
				$order = $this->findOne(["tablename" => "orders", "condition" => "id = :orderid", "bindparam" => [":orderid" => $orderid]]);
				if (!$order) continue;
				if (in_array($order["status"], ['cancelled', 'delivered', 'intransit'])) continue;

				$task = $this->findOne(["tablename" => "tasks", "condition" => "order_id = :orderid", "bindparam" => [":orderid" => $orderid]]);
				if ($task) {
					//update order
					if ($this->edit($task["id"], $agentid, $orderid, $task["sendpayment"], $task["agentpayment"])) $submitted++;;
				} else {
					//add task
					if ($this->add($agentid, $orderid)) $submitted++;
				}
			}

			if ($submitted === 0) throw new \Exception("No tasks was assigned to agent");

			//send notifcation to agent


			$message = "$submitted orders were assigned to agent " . $agent["firstname"] . " " . $agent["lastname"] . " successfully";
			exit(Response::json(["status" => true, "message" => $message]));
		} catch (\Exception $error) {
			exit(Response::json(["status" => false, "message" => $error->getMessage()]));
		}
	}

	public function agentTask()
	{
		Auth::checkAuth("agentid");
		$agentid = Session::get("agentid");

		return $this->exec_query("SELECT A.customer,A.telephone as customertelephone,A.address as deliveryaddress, A.totalamount,A.delivery_fee,A.status as orderstatus,A.description,A.id as order_id,B.id,B.agentfee,B.sendpayment,B.agentpayment,B.created_at,B.updated_at, C.state, D.city, E.telephone as sellertelephone, F.companyname as seller, (SELECT CONCAT(firstname, ' ', lastname) FROM users WHERE id = B.user_id) assigner, (SELECT CONCAT(firstname,' ', lastname) FROM agents WHERE id = B.agent_id) as assignee FROM `orders` A INNER JOIN tasks B ON A.id = B.order_id INNER JOIN states C ON A.state_id = C.id INNER JOIN cities D ON A.city_id = D.id INNER JOIN clients E ON A.client_id = E.id INNER JOIN client_profile F ON A.client_id = F.client_id WHERE B.agent_id = '$agentid' ORDER BY B.created_at DESC");
	}
}
