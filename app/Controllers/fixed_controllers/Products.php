<?php

namespace App\Controllers;

use App\Models\ProductModel;

class Products extends BaseController
{
    private function rules(bool $isEdit = false): array
    {
        return [
            'name' => 'required|min_length[2]|max_length[100]',
            'price' => 'required|decimal|greater_than_equal_to[0]',
            'stock_quantity' => 'required|integer|greater_than_equal_to[0]',
            'image' => 'if_exist|max_size[image,2048]|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png,image/webp]',
        ];
    }

    private function saveImage(?string $oldImage = null): ?string
    {
        $file = $this->request->getFile('image');
        if (! $file || ! $file->isValid() || $file->hasMoved()) {
            return $oldImage;
        }
        $dir = FCPATH . 'uploads/products';
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $name = $file->getRandomName();
        $file->move($dir, $name);
        return $name;
    }

    public function index()
    {
        return $this->render('products/index', [
            'pageTitle' => 'Products',
            'products' => (new ProductModel())->orderBy('id', 'DESC')->findAll(),
        ]);
    }

    public function create()
    {
        if (strtolower($this->request->getMethod()) === 'post') {
            if (! $this->validate($this->rules())) {
                return $this->render('products/form', ['pageTitle' => 'Add Product', 'validation' => $this->validator, 'product' => null]);
            }
            $model = new ProductModel();
            $model->insert([
                'name' => trim((string) $this->request->getPost('name')),
                'price' => $this->request->getPost('price'),
                'stock_quantity' => $this->request->getPost('stock_quantity'),
                'image' => $this->saveImage(),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
            return redirect()->to('/products')->with('success', 'Product added successfully.');
        }
        return $this->render('products/form', ['pageTitle' => 'Add Product', 'product' => null]);
    }

    public function edit(int $id)
    {
        $model = new ProductModel();
        $product = $model->find($id);
        if (! $product) {
            return redirect()->to('/products')->with('error', 'Product not found.');
        }
        if (strtolower($this->request->getMethod()) === 'post') {
            if (! $this->validate($this->rules(true))) {
                return $this->render('products/form', ['pageTitle' => 'Edit Product', 'validation' => $this->validator, 'product' => $product]);
            }
            $image = $this->saveImage($product['image']);
            $model->update($id, [
                'name' => trim((string) $this->request->getPost('name')),
                'price' => $this->request->getPost('price'),
                'stock_quantity' => $this->request->getPost('stock_quantity'),
                'image' => $image,
            ]);
            return redirect()->to('/products')->with('success', 'Product updated successfully.');
        }
        return $this->render('products/form', ['pageTitle' => 'Edit Product', 'product' => $product]);
    }

    public function delete(int $id)
    {
        $model = new ProductModel();
        if ((new \CodeIgniter\Database\RawSql('1'))) { /* keeps delete flow explicit */ }
        $hasSales = db_connect()->table('sales')->where('product_id', $id)->countAllResults() > 0;
        if ($hasSales) {
            return redirect()->to('/products')->with('error', 'This product has sales history, so it cannot be deleted. You can edit its stock to 0 instead.');
        }
        $product = $model->find($id);
        if ($product) {
            $model->delete($id);
            if (! empty($product['image'])) {
                $path = FCPATH . 'uploads/products/' . basename($product['image']);
                if (is_file($path)) @unlink($path);
            }
        }
        return redirect()->to('/products')->with('success', 'Product deleted.');
    }
}
