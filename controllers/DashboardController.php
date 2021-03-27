<?php

namespace App\controllers;

use App\middleware\Auth;
use App\utils\Response;
use CURLFile;

class DashboardController extends Controller
{
	public function clienttotalordercount($clientid)
	{
		return $this->getCount([
			"tablename" => "orders",
			"condition" => "client_id =:clientid",
			"bindparam" => [":clientid" => $clientid]
		]);
	}

	public function clientorderscountbystaus($clientid, $status)
	{
		return $this->getCount([
			"tablename" => "orders",
			"condition" => "client_id =:clientid AND status =:status",
			"bindparam" => [":clientid" => $clientid, ":status" => $status]
		]);
	}

	public function totalordercount()
	{
		return $this->getCount(["tablename" => "orders"]);
	}

	public function totalunassignedordercount()
	{
		return $this->getCount(["tablename" => "orders", "condition" => "id NOT IN (SELECT order_id FROM tasks)"]);
	}

	public function orderscountbystaus($status)
	{
		return $this->getCount(["tablename" => "orders", "condition" => "status =:status", "bindparam" => [":status" => $status]]);
	}

	public function clienttotalwaybillcount($clientid)
	{
		return $this->getCount([
			"tablename" => "package",
			"condition" => "client_id =:clientid",
			"bindparam" => [":clientid" => $clientid]
		]);
	}

	public function clientwaybillcountbystaus($clientid, $status)
	{
		return $this->getCount([
			"tablename" => "package",
			"condition" => "client_id =:clientid AND status =:status",
			"bindparam" => [":clientid" => $clientid, ":status" => $status]
		]);
	}

	public function clienttotalpayments($clientid)
	{
		return $this->getCount([
			"tablename" => "orders",
			"condition" => "client_id =:clientid AND status =:status",
			"bindparam" => [":clientid" => $clientid, ":status" => "delivered"]
		]);
	}

	public function clientpaymentscountbystatus($clientid, $status)
	{
		return $this->getCount([
			"tablename" => "orders",
			"condition" => "client_id =:clientid AND status =:status AND payment_status =:paymentstatus",
			"bindparam" => [":clientid" => $clientid, ":status" => "delivered", ":paymentstatus" => $status]
		]);
	}

	public function clientpaymentsamountbystatus($clientid, $status)
	{
		$payment = $this->findAll([
			"tablename" => "orders",
			"fields" => "(SUM(totalamount) - SUM(delivery_fee)) as amount",
			"condition" => "client_id =:clientid AND status =:status AND payment_status =:paymentstatus",
			"bindparam" => [":clientid" => $clientid, ":status" => "delivered", ":paymentstatus" => $status]
		]);

		return floatval($payment[0]["amount"]);
	}


	public function ordersStats()
	{
		Auth::checkAuth("userid");
		return [
			"totalorders" => $this->totalordercount(),
			"pendingorders" => $this->orderscountbystaus("pending"),
			"cancelledorders" => $this->orderscountbystaus("cancelled"),
			"deliveredorders" => $this->orderscountbystaus("delivered"),
			"rescheduledorders" => $this->orderscountbystaus("rescheduled"),
			"intransitorders" => $this->orderscountbystaus("intransit"),
			"noresponseorders" => $this->orderscountbystaus("noresponse"),
			"confirmedorders" => $this->orderscountbystaus("confirmed"),
			"unassigned" => $this->totalunassignedordercount()
		];
	}

	public function clientDashboardStats()
	{
		$client = new ClientController();
		$clientid = $client->getClientId();
		return [
			"totalorders" => $this->clienttotalordercount($clientid),
			"pendingorders" => $this->clientorderscountbystaus($clientid, "pending"),
			"cancelledorders" => $this->clientorderscountbystaus($clientid, "cancelled"),
			"deliveredorders" => $this->clientorderscountbystaus($clientid, "delivered"),
			"rescheduledorders" => $this->clientorderscountbystaus($clientid, "rescheduled"),
			"intransitorders" => $this->clientorderscountbystaus($clientid, "intransit"),
			"onresponseorders" => $this->clientorderscountbystaus($clientid, "noresponse"),
			"totalwaybills" => $this->clienttotalwaybillcount($clientid),
			"unsentwaybills" => $this->clientwaybillcountbystaus($clientid, "onhand"),
			"intransitwaybills" => $this->clientwaybillcountbystaus($clientid, "sent"),
			"pendingwaybills" => $this->clientwaybillcountbystaus($clientid, "pending"),
			"recievedwaybills" => $this->clientwaybillcountbystaus($clientid, "received"),
			"unpaidpayments" => $this->clientpaymentsamountbystatus($clientid, "unpaid"),
			"paidpayments" => $this->clientpaymentsamountbystatus($clientid, "paid"),
			"verifiedpayments" => $this->clientpaymentsamountbystatus($clientid, "verified"),
			"unpaidpaymentscount" => $this->clientpaymentscountbystatus($clientid, "unpaid"),
			"paidpaymentscount" => $this->clientpaymentscountbystatus($clientid, "paid"),
			"verifiedpaymentscount" => $this->clientpaymentscountbystatus($clientid, "verified"),
		];
	}

	public function agenttotaltask($agentid)
	{
		return $this->getCount([
			"tablename" => "tasks",
			"condition" => "agent_id = :agentid",
			"bindparam" => [":agentid" => $agentid]
		]);
	}

	public function agentcompletedtaskcount($agentid)
	{
		return $this->getCount([
			"tablename" => "tasks A",
			"condition" => "agent_id = :agentid AND B.status = 'delivered'",
			"joins" => "INNER JOIN orders B ON A.order_id = B.id",
			"bindparam" => [":agentid" => $agentid]
		]);
	}

	public function agentuncompletedtaskcount($agentid)
	{
		return $this->getCount([
			"tablename" => "tasks A",
			"condition" => "agent_id = :agentid AND B.status <> 'delivered'",
			"joins" => "INNER JOIN orders B ON A.order_id = B.id",
			"bindparam" => [":agentid" => $agentid]
		]);
	}

	public function agentuncompletedtask($agentid)
	{
		return $this->exec_query("SELECT A.customer,A.telephone as customertelephone,A.address as deliveryaddress, A.totalamount,A.delivery_fee,A.status as orderstatus,A.description,A.id as order_id,B.id,B.agentfee,B.sendpayment,B.agentpayment,B.sendpayment_status,B.payment_method,B.created_at,B.updated_at, C.state, D.city, E.telephone as sellertelephone, F.companyname as seller, (SELECT CONCAT(firstname, ' ', lastname) FROM users WHERE id = B.user_id) assigner, (SELECT CONCAT(firstname,' ', lastname) FROM agents WHERE id = B.agent_id) as assignee FROM `orders` A INNER JOIN tasks B ON A.id = B.order_id INNER JOIN states C ON A.state_id = C.id INNER JOIN cities D ON A.city_id = D.id INNER JOIN clients E ON A.client_id = E.id INNER JOIN client_profile F ON A.client_id = F.client_id WHERE B.agent_id = '$agentid' AND A.status <> 'delivered' ORDER BY B.created_at DESC");
	}

	public function agentmonthlycompleted($agentid)
	{
		return $this->exec_query("SELECT DISTINCT MONTH(A.created_at) AS month, MONTHNAME(A.created_at) as monthname, COUNT(A.id) as deliveries FROM tasks A INNER JOIN orders B WHERE B.status ='delivered' AND YEAR(NOW()) = YEAR(A.created_at) AND A.order_id = B.id AND A.agent_id = '$agentid' GROUP BY month ORDER BY A.created_at");
	}

	public function pendingdeliveries()
	{
		$agent = new AgentController();
		$agentid = $agent->getagentId();
		return $this->findAll([
			"tablename" => "tasks A",
			"condition" => "A.agent_id = :agentid AND B.status = 'pending' ORDER BY A.created_at",
			"joins" => "INNER JOIN orders B ON A.order_id = B.id",
			"bindparam" => [":agentid" => $agentid]
		]);
	}

	public function agentDashboardStats()
	{
		$agent = new AgentController();
		$agentid = $agent->getagentId();
		return [
			"total" => $this->agenttotaltask($agentid),
			"totalcompleted" => $this->agentcompletedtaskcount($agentid),
			"totaluncompleted" => $this->agentuncompletedtaskcount($agentid),
			"uncompleted" => $this->agentuncompletedtask($agentid)
		];
	}

	public function agentstats()
	{
		try {
			$agent = new AgentController();
			$agentid = $agent->getagentId();
			$labels = [];
			$data = [];

			$completed = $this->agentmonthlycompleted($agentid);

			if (count($completed) > 0) {
				foreach ($completed as $c) {
					$labels[] = $c["monthname"];
					$data[] = intval($c["deliveries"]);
				}
			}

			exit(Response::json(["status" => true, "data" => ["labels" => $labels, "data" => $data]]));
		} catch (\Exception $error) {
			exit(Response::json(["status" => false, "message" => $error->getMessage()]));
		}
	}
}
