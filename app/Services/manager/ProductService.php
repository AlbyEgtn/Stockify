<?php

namespace App\Services\Manager;

use App\Repositories\Manager\ProductRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    protected $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getPaginatedProducts()
    {
        return $this->productRepository->paginateWithRelations(10);
    }

    public function store(array $data)
    {
        return DB::transaction(function () use ($data) {

            // Validasi bisnis
            if ($data['selling_price'] < $data['purchase_price']) {
                throw new \Exception('Harga jual tidak boleh lebih kecil dari harga beli');
            }

            // Upload gambar
            if (isset($data['image'])) {
                $data['image'] = $data['image']->store('products', 'public');
            }

            return $this->productRepository->create($data);
        });
    }

    public function update($product, array $data)
    {
        return DB::transaction(function () use ($product, $data) {

            if ($data['selling_price'] < $data['purchase_price']) {
                throw new \Exception('Harga jual tidak boleh lebih kecil dari harga beli');
            }

            // Jika ada gambar baru
            if (isset($data['image'])) {

                // Hapus gambar lama
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }

                $data['image'] = $data['image']->store('products', 'public');
            }

            return $this->productRepository->update($product, $data);
        });
    }

    public function delete($product)
    {
        return DB::transaction(function () use ($product) {

            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            return $this->productRepository->delete($product);
        });
    }
}
