<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Product::with(['category', 'supplier', 'productAttributes'])
            ->get()
            ->map(function ($product) {

                // Format atribut jadi string
                $attributes = $product->productAttributes
                    ->map(function ($attr) {
                        return $attr->name . ':' . $attr->value;
                    })
                    ->implode(';');

                return [
                    'nama_produk'  => $product->name,
                    'sku'          => $product->sku,
                    'kategori'     => $product->category->name ?? '',
                    'supplier'     => $product->supplier->name ?? '',
                    'harga_beli'   => $product->purchase_price,
                    'harga_jual'   => $product->selling_price,
                    'stok'         => $product->stock,
                    'minimum_stok' => $product->minimum_stock,
                    'status'       => $product->status,
                    'gambar'       => $product->image,
                    'atribut'      => $attributes,
                    'deskripsi'    => $product->description,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'nama_produk',
            'sku',
            'kategori',
            'supplier',
            'harga_beli',
            'harga_jual',
            'stok',
            'minimum_stok',
            'status',
            'gambar',
            'atribut',
            'deskripsi',
        ];
    }
}