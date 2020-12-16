<?php

namespace App\controllers;

use App\controllers\BaseController;

class UserController extends BaseController
{

    public function index()
    {
        return $this->response->json($_POST);
    }

    public function add()
    {
    }

    public function edit()
    {
    }

    public function delete()
    {
    }
}
