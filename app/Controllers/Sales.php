<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\CustomerModel;
use App\Models\SaleModel;

class Sales extends BaseController
{
    public function create()
    {
        $products = (new ProductModel())
            ->where('stock_quantity >', 0)
            ->orderBy('name')
            ->findAll();

        $customers = (new CustomerModel())
            ->orderBy('full_name')
            ->findAll();

        // FIXED: Check the request method without case sensitivity.
        if (strtolower($this->request->getMethod()) === 'post') {
            $rules = [
                'product_id' => 'required|is_natural_no_zero',
                'customer_id' => 'permit_empty|is_natural',
                'quantity' => 'required|is_natural_no_zero|less_than_equal_to[1000000]',
            ];

            if (! $this->validate($rules)) {
                return $this->render('sales/create', [
                    'pageTitle' => 'Record Sale',
                    'products' => $products,
                    'customers' => $customers,
                    'validation' => $this->validator,
                ]);
            }

            $productId = (int) $this->request->getPost('product_id');
            $customerRaw = $this->request->getPost('customer_id');
            $customerId = ($customerRaw === '' || $customerRaw === null)
                ? null
                : (int) $customerRaw;
            $quantity = (int) $this->request->getPost('quantity');

            if (
                $customerId !== null &&
                ! (new CustomerModel())->find($customerId)
            ) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Please select a valid customer.');
            }

            $db = db_connect();
            $db->transBegin();

            try {
                // Lock the selected product row to prevent concurrent overselling.
                $product = $db->query(
                    'SELECT * FROM products WHERE id = ? FOR UPDATE',
                    [$productId]
                )->getRowArray();

                if (! $product) {
                    $db->transRollback();

                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'The selected product does not exist.');
                }

                if ($quantity > (int) $product['stock_quantity']) {
                    $db->transRollback();

                    return redirect()->back()
                        ->withInput()
                        ->with(
                            'error',
                            'Not enough stock. Available quantity: '
                            . $product['stock_quantity'] . '.'
                        );
                }

                $total = round((float) $product['price'] * $quantity, 2);

                $db->table('sales')->insert([
                    'product_id' => $productId,
                    'customer_id' => $customerId,
                    'sold_by' => (int) session()->get('user_id'),
                    'quantity' => $quantity,
                    'total_price' => number_format($total, 2, '.', ''),
                    'created_at' => date('Y-m-d H:i:s'),
                ]);

                $db->table('products')
                    ->where('id', $productId)
                    ->update([
                        'stock_quantity' =>
                            (int) $product['stock_quantity'] - $quantity,
                    ]);

                if ($db->transStatus() === false) {
                    $db->transRollback();

                    return redirect()->back()
                        ->withInput()
                        ->with(
                            'error',
                            'The sale could not be saved. Please try again.'
                        );
                }

                $db->transCommit();

                return redirect()->to('/sales/history')
                    ->with(
                        'success',
                        'Sale recorded. Total: ₱' . number_format($total, 2)
                    );
            } catch (\Throwable $e) {
                $db->transRollback();

                log_message('error', 'POS sale error: ' . $e->getMessage());

                return redirect()->back()
                    ->withInput()
                    ->with(
                        'error',
                        'An unexpected error occurred while recording the sale.'
                    );
            }
        }

        return $this->render('sales/create', [
            'pageTitle' => 'Record Sale',
            'products' => $products,
            'customers' => $customers,
        ]);
    }

    public function history()
    {
        return $this->render('sales/history', [
            'pageTitle' => 'Sales History',
            'sales' => (new SaleModel())->getHistory(),
        ]);
    }
}
