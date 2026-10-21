<?php

namespace App\Controllers;

use CodeIgniter\Controller;

abstract class BaseController extends Controller
{
    protected $helpers = ['form', 'url'];

    protected function render(string $view, array $data = [])
    {
        $data['pageTitle'] = $data['pageTitle'] ?? 'POS System';
        return view('layout/header', $data)
            . view($view, $data)
            . view('layout/footer', $data);
    }
}
