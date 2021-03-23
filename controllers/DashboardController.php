<?php

namespace App\controllers;

use App\middleware\Auth;

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
}
