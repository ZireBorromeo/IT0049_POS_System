<?php

namespace App\Controllers;

use App\Models\CustomerModel;

class Customers extends BaseController
{
    private function rules(): array
    {
        return [
            'full_name' => 'required|min_length[2]|max_length[100]',
            'email' => 'required|valid_email|max_length[100]',
            'phone' => 'permit_empty|max_length[20]',
        ];
    }

    public function index()
    {
        return $this->render('customers/index', [
            'pageTitle' => 'Customers',
            'customers' => (new CustomerModel())->orderBy('id', 'DESC')->findAll(),
        ]);
    }

    public function create()
    {
        if ($this->request->getMethod() === 'post') {
            if (! $this->validate($this->rules())) {
                return $this->render('customers/form', ['pageTitle' => 'Add Customer', 'validation' => $this->validator, 'customer' => null]);
            }
            (new CustomerModel())->insert([
                'full_name' => trim((string) $this->request->getPost('full_name')),
                'email' => trim((string) $this->request->getPost('email')),
                'phone' => trim((string) $this->request->getPost('phone')),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
            return redirect()->to('/customers')->with('success', 'Customer added.');
        }
        return $this->render('customers/form', ['pageTitle' => 'Add Customer', 'customer' => null]);
    }

    public function edit(int $id)
    {
        $model = new CustomerModel();
        $customer = $model->find($id);
        if (! $customer) return redirect()->to('/customers')->with('error', 'Customer not found.');

        if ($this->request->getMethod() === 'post') {
            if (! $this->validate($this->rules())) {
                return $this->render('customers/form', ['pageTitle' => 'Edit Customer', 'validation' => $this->validator, 'customer' => $customer]);
            }
            $model->update($id, [
                'full_name' => trim((string) $this->request->getPost('full_name')),
                'email' => trim((string) $this->request->getPost('email')),
                'phone' => trim((string) $this->request->getPost('phone')),
            ]);
            return redirect()->to('/customers')->with('success', 'Customer updated.');
        }
        return $this->render('customers/form', ['pageTitle' => 'Edit Customer', 'customer' => $customer]);
    }

    public function delete(int $id)
    {
        if (db_connect()->table('sales')->where('customer_id', $id)->countAllResults() > 0) {
            return redirect()->to('/customers')->with('error', 'This customer has sales history and cannot be deleted.');
        }
        (new CustomerModel())->delete($id);
        return redirect()->to('/customers')->with('success', 'Customer deleted.');
    }
}
