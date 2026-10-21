<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\CustomerModel;
use App\Models\SaleModel;
use App\Models\UserModel;

class Dashboard extends BaseController
{
    public function index()
    {
        return $this->render('dashboard/index', [
            'pageTitle' => 'Dashboard',
            'productCount' => (new ProductModel())->countAllResults(),
            'customerCount' => (new CustomerModel())->countAllResults(),
            'staffCount' => (new UserModel())->countAllResults(),
            'sales' => (new SaleModel())->getHistory(5),
        ]);
    }
}
