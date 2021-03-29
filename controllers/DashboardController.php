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

	public function totalwaybillcount()
	{
		return $this->getCount(["tablename" => "package"]);
	}

	public function waybillcountbystaus($status)
	{
		return $this->getCount(["tablename" => "package", "condition" => "status =:status", "bindparam" => [":status" => $status]]);
	}

	public function totalpayments()
	{
		return $this->getCount(["tablename" => "orders", "condition" => "status =:status", "bindparam" => [":status" => "delivered"]]);
	}

	public function paymentscountbystatus($status)
	{
		return $this->getCount([
			"tablename" => "orders",
			"condition" => "status =:status AND payment_status =:paymentstatus",
			"bindparam" => [":status" => "delivered", ":paymentstatus" => $status]
		]);
	}

	public function paymentsamountbystatus($status)
	{
		$payment = $this->findAll([
			"tablename" => "orders",
			"fields" => "(SUM(totalamount) - SUM(delivery_fee)) as amount",
			"condition" => "status =:status AND payment_status =:paymentstatus",
			"bindparam" => [":status" => "delivered", ":paymentstatus" => $status]
		]);

		return floatval($payment[0]["amount"]);
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
			"period" => $this->periodrange()
		];
	}

	public function DashboardStats()
	{
		Auth::checkAuth("userid");
		return [
			"totalorders" => $this->totalordercount(),
			"pendingorders" => $this->orderscountbystaus("pending"),
			"cancelledorders" => $this->orderscountbystaus("cancelled"),
			"deliveredorders" => $this->orderscountbystaus("delivered"),
			"rescheduledorders" => $this->orderscountbystaus("rescheduled"),
			"intransitorders" => $this->orderscountbystaus("intransit"),
			"onresponseorders" => $this->orderscountbystaus("noresponse"),
			"totalwaybills" => $this->totalwaybillcount(),
			"unsentwaybills" => $this->waybillcountbystaus("onhand"),
			"intransitwaybills" => $this->waybillcountbystaus("sent"),
			"pendingwaybills" => $this->waybillcountbystaus("pending"),
			"recievedwaybills" => $this->waybillcountbystaus("received"),
			"unpaidpayments" => $this->paymentsamountbystatus("unpaid"),
			"paidpayments" => $this->paymentsamountbystatus("paid"),
			"verifiedpayments" => $this->paymentsamountbystatus("verified"),
			"unpaidpaymentscount" => $this->paymentscountbystatus("unpaid"),
			"paidpaymentscount" => $this->paymentscountbystatus("paid"),
			"verifiedpaymentscount" => $this->paymentscountbystatus("verified"),
			"period" => $this->periodrange(),
			"total" => $this->totaltask(),
			"totalcompleted" => $this->completedtaskcount(),
			"totaluncompleted" => $this->uncompletedtaskcount(),
			"uncompleted" => $this->uncompletedtask()
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

	public function totaltask()
	{
		return $this->getCount(["tablename" => "tasks"]);
	}

	public function completedtaskcount()
	{
		return $this->getCount([
			"tablename" => "tasks A",
			"condition" => "B.status = 'delivered'",
			"joins" => "INNER JOIN orders B ON A.order_id = B.id"
		]);
	}

	public function uncompletedtaskcount()
	{
		return $this->getCount([
			"tablename" => "tasks A",
			"condition" => "B.status <> 'delivered'",
			"joins" => "INNER JOIN orders B ON A.order_id = B.id"
		]);
	}

	public function agentuncompletedtask($agentid)
	{
		return $this->exec_query("SELECT A.customer,A.telephone as customertelephone,A.address as deliveryaddress, A.totalamount,A.delivery_fee,A.status as orderstatus,A.description,A.id as order_id,B.id,B.agentfee,B.sendpayment,B.agentpayment,B.sendpayment_status,B.payment_method,B.created_at,B.updated_at, C.state, D.city, E.telephone as sellertelephone, F.companyname as seller, (SELECT CONCAT(firstname, ' ', lastname) FROM users WHERE id = B.user_id) assigner, (SELECT CONCAT(firstname,' ', lastname) FROM agents WHERE id = B.agent_id) as assignee FROM `orders` A INNER JOIN tasks B ON A.id = B.order_id INNER JOIN states C ON A.state_id = C.id INNER JOIN cities D ON A.city_id = D.id INNER JOIN clients E ON A.client_id = E.id INNER JOIN client_profile F ON A.client_id = F.client_id WHERE B.agent_id = '$agentid' AND A.status <> 'delivered' ORDER BY B.created_at DESC");
	}

	public function uncompletedtask()
	{
		return $this->exec_query("SELECT A.customer,A.telephone as customertelephone,A.address as deliveryaddress, A.totalamount,A.delivery_fee,A.status as orderstatus,A.description,A.id as order_id,B.id,B.agentfee,B.sendpayment,B.agentpayment,B.sendpayment_status,B.payment_method,B.created_at,B.updated_at, C.state, D.city, E.telephone as sellertelephone, F.companyname as seller, (SELECT CONCAT(firstname, ' ', lastname) FROM users WHERE id = B.user_id) assigner, (SELECT CONCAT(firstname,' ', lastname) FROM agents WHERE id = B.agent_id) as assignee FROM `orders` A INNER JOIN tasks B ON A.id = B.order_id INNER JOIN states C ON A.state_id = C.id INNER JOIN cities D ON A.city_id = D.id INNER JOIN clients E ON A.client_id = E.id INNER JOIN client_profile F ON A.client_id = F.client_id WHERE  A.status <> 'delivered' ORDER BY B.created_at DESC");
	}

	public function getPeriods()
	{
		$periods = [];

		for ($i = 11; $i >= 0; $i--) {

			if ($i == 0) {
				$tag = date("M", strtotime("this month"));
				$year = date("Y", strtotime("this month"));
				$date = date("Y-m", strtotime("this month"));
			} else {
				$tag = date("M", strtotime("$i month ago"));
				$year = date("Y", strtotime("$i month ago"));
				$date = date("Y-m", strtotime("$i month ago"));
			}
			$periods[] = ['label' => $tag, "date" => $date, "years" => $year];
		}
		return $periods;
	}

	public function periodrange()
	{
		$ranges = [];
		$periods = $this->getPeriods();
		foreach ($periods as $period) {
			$ranges[] = $period["years"];
		}
		return $ranges[0] . " - " . $ranges[count($ranges) - 1];
	}

	public function agentmonthlydeliveries($agentid)
	{
		$periods = $this->getPeriods();
		$stats = ["labels" => [], "uncompleted" => [], "completed" => []];

		foreach ($periods as $period) {
			$completed = $this->exec_query("SELECT COUNT(A.id) as deliveries FROM tasks A INNER JOIN orders B WHERE B.status ='delivered' AND A.order_id = B.id AND A.agent_id = '$agentid' AND CONCAT(YEAR(A.created_at),'-0', MONTH(A.created_at)) = '{$period["date"]}'");

			$uncompleted = $this->exec_query("SELECT COUNT(A.id) as deliveries FROM tasks A INNER JOIN orders B WHERE B.status <> 'delivered' AND A.order_id = B.id AND A.agent_id = '$agentid' AND CONCAT(YEAR(A.created_at),'-0', MONTH(A.created_at)) = '{$period["date"]}'");
			$stats["labels"][] = $period["label"];
			$stats["completed"][] = intval($completed[0]["deliveries"]);
			$stats["uncompleted"][] = intval($uncompleted[0]["deliveries"]);
		}
		return $stats;
	}

	public function clientmonthlydeliveries($clientid)
	{
		$periods = $this->getPeriods();
		$stats = ["labels" => [], "uncompleted" => [], "completed" => []];

		foreach ($periods as $period) {
			$completed = $this->exec_query("SELECT COUNT(id) as deliveries FROM orders WHERE status ='delivered' AND client_id = '$clientid' AND CONCAT(YEAR(created_at),'-0', MONTH(created_at)) = '{$period["date"]}'");

			$uncompleted = $this->exec_query("SELECT COUNT(id) as deliveries FROM orders WHERE status <> 'delivered' AND client_id = '$clientid' AND CONCAT(YEAR(created_at),'-0', MONTH(created_at)) = '{$period["date"]}'");
			$stats["labels"][] = $period["label"];
			$stats["completed"][] = intval($completed[0]["deliveries"]);
			$stats["uncompleted"][] = intval($uncompleted[0]["deliveries"]);
		}
		return $stats;
	}

	public function monthlydeliveries()
	{
		$periods = $this->getPeriods();
		$stats = ["labels" => [], "uncompleted" => [], "completed" => []];

		foreach ($periods as $period) {
			$completed = $this->exec_query("SELECT COUNT(id) as deliveries FROM orders WHERE status ='delivered' AND CONCAT(YEAR(created_at),'-0', MONTH(created_at)) = '{$period["date"]}'");

			$uncompleted = $this->exec_query("SELECT COUNT(id) as deliveries FROM orders WHERE status <> 'delivered' AND CONCAT(YEAR(created_at),'-0', MONTH(created_at)) = '{$period["date"]}'");
			$stats["labels"][] = $period["label"];
			$stats["completed"][] = intval($completed[0]["deliveries"]);
			$stats["uncompleted"][] = intval($uncompleted[0]["deliveries"]);
		}
		return $stats;
	}

	public function clientordersbystate()
	{
		$client = new ClientController();
		$clientid = $client->getclientId();

		$records = ["labels" => [], "data" => []];
		$stats = $this->exec_query("SELECT DISTINCT A.state_id, B.state, COUNT(A.id) as ordercount FROM orders A INNER JOIN states B ON A.state_id = B.id WHERE A.client_id = '$clientid' GROUP BY A.state_id");

		if (count($stats) > 0) {
			foreach ($stats as $stat) {
				$records["labels"][] = $stat["state"];
				$records["data"][] = intval($stat["ordercount"]);
			}
		}

		return $records;
	}

	public function ordersbystate()
	{
		$records = ["labels" => [], "data" => []];
		$stats = $this->exec_query("SELECT DISTINCT A.state_id, B.state, COUNT(A.id) as ordercount FROM orders A INNER JOIN states B ON A.state_id = B.id  GROUP BY A.state_id");

		if (count($stats) > 0) {
			foreach ($stats as $stat) {
				$records["labels"][] = $stat["state"];
				$records["data"][] = intval($stat["ordercount"]);
			}
		}

		return $records;
	}

	public function pendingdeliveries()
	{
		$agent = new AgentController();
		$agentid = $agent->getagentId();
		return $this->findAll([
			"tablename" => "tasks A",
			"condition" => "A.agent_id = :agentid AND B.status = 'pending' ORDER BY A.created_at DESC",
			"joins" => "INNER JOIN orders B ON A.order_id = B.id",
			"bindparam" => [":agentid" => $agentid]
		]);
	}

	public function pendingorders()
	{
		Auth::checkAuth("userid");
		return $this->findAll([
			"tablename" => "orders",
			"condition" => "status = 'pending' ORDER BY created_at DESC",
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
			"uncompleted" => $this->agentuncompletedtask($agentid),
			"period" => $this->periodrange()
		];
	}

	public function agentstats()
	{
		try {
			$agent = new AgentController();
			$agentid = $agent->getagentId();

			$completed = $this->agentmonthlydeliveries($agentid);

			exit(Response::json(["status" => true, "data" => $completed]));
		} catch (\Exception $error) {
			exit(Response::json(["status" => false, "message" => $error->getMessage()]));
		}
	}

	public function clientstats()
	{
		try {
			$client = new ClientController();
			$clientid = $client->getclientId();

			$completed = $this->clientmonthlydeliveries($clientid);
			$statestats = $this->clientordersbystate();

			exit(Response::json(["status" => true, "data" => $completed, "statestats" => $statestats]));
		} catch (\Exception $error) {
			exit(Response::json(["status" => false, "message" => $error->getMessage()]));
		}
	}

	public function deliverybyagents()
	{
		$records = ["labels" => [], "data" => []];
		$stats = $this->exec_query("SELECT CONCAT(A.firstname,' ',A.lastname) as agent, COUNT(B.id) as deliverycount FROM agents A LEFT JOIN tasks B ON A.id = B.agent_id GROUP BY A.id ");

		if (count($stats) > 0) {
			foreach ($stats as $stat) {
				$records["labels"][] = $stat["agent"];
				$records["data"][] = intval($stat["deliverycount"]);
			}
		}

		return $records;
	}

	public function stats()
	{
		try {
			Auth::checkAuth("userid");

			$completed = $this->monthlydeliveries();
			$statestats = $this->ordersbystate();
			$agentdeliveries = $this->deliverybyagents();

			exit(Response::json(["status" => true, "data" => $completed, "statestats" => $statestats, "agentdeliveries" => $agentdeliveries]));
		} catch (\Exception $error) {
			exit(Response::json(["status" => false, "message" => $error->getMessage()]));
		}
	}
}
