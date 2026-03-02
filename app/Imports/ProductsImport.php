<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\ProductAttribute;
use App\Models\StockTransaction;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class ProductsImport implements
    ToModel,
    WithHeadingRow,
    WithValidation,
    WithChunkReading,
    SkipsOnFailure,
    SkipsEmptyRows
{
    use Importable, SkipsFailures;

    public function model(array $row)
    {
        DB::beginTransaction();

        try {

            // =============================
            // NORMALISASI DATA
            // =============================

            $sku = strtoupper(trim($row['sku']));
            $nama = trim($row['nama_produk']);
            $status = strtolower(trim($row['status'] ?? 'draft'));
            $status = in_array($status, ['active', 'draft']) ? $status : 'draft';

            $stok = (int) $row['stok'];
            $hargaBeli = (float) $row['harga_beli'];
            $hargaJual = (float) $row['harga_jual'];
            $minimumStok = (int) $row['minimum_stok'];

            // =============================
            // RESOLVE CATEGORY
            // =============================

            $category = Category::firstOrCreate([
                'name' => trim($row['kategori'])
            ]);

            // =============================
            // RESOLVE SUPPLIER
            // =============================

            $supplier = Supplier::firstOrCreate([
                'name' => trim($row['supplier'])
            ]);

            // =============================
            // CEK PRODUK BERDASARKAN SKU
            // =============================

            $product = Product::whereRaw('LOWER(sku) = ?', [strtolower($sku)])->first();

            if ($product) {

                // =============================
                // UPDATE MODE
                // =============================

                $product->update([
                    'name'           => $nama,
                    'category_id'    => $category->id,
                    'supplier_id'    => $supplier->id,
                    'purchase_price' => $hargaBeli,
                    'selling_price'  => $hargaJual,
                    'minimum_stock'  => $minimumStok,
                    'status'         => $status,
                    'description'    => $row['deskripsi'] ?? null,
                    'image'          => $row['gambar'] ?: $product->image,
                ]);

                if ($stok > 0) {

                    $stockBefore = $product->stock;
                    $stockAfter  = $stockBefore + $stok;

                    $product->update([
                        'stock' => $stockAfter
                    ]);

                    StockTransaction::create([
                        'product_id'   => $product->id,
                        'type'         => 'IN',
                        'quantity'     => $stok,
                        'stock_before' => $stockBefore,
                        'stock_after'  => $stockAfter,
                        'user_id'      => Auth::id(),
                        'note'         => 'Import produk (update)',
                    ]);
                }

                $this->syncAttributes($product, $row['atribut'] ?? null);

            } else {

                // =============================
                // CREATE MODE
                // =============================

                $product = Product::create([
                    'name'           => $nama,
                    'sku'            => $sku,
                    'category_id'    => $category->id,
                    'supplier_id'    => $supplier->id,
                    'purchase_price' => $hargaBeli,
                    'selling_price'  => $hargaJual,
                    'stock'          => $stok,
                    'minimum_stock'  => $minimumStok,
                    'status'         => $status,
                    'description'    => $row['deskripsi'] ?? null,
                    'image'          => $row['gambar'] ?? null,
                ]);

                if ($stok > 0) {

                    $stockBefore = 0;
                    $stockAfter  = $stok;

                    StockTransaction::create([
                        'product_id'   => $product->id,
                        'type'         => 'IN',
                        'quantity'     => $stok,
                        'stock_before' => $stockBefore,
                        'stock_after'  => $stockAfter,
                        'user_id'      => Auth::id(),
                        'note'         => 'Import produk (baru)',
                    ]);
                }

                $this->syncAttributes($product, $row['atribut'] ?? null);
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return null;
    }

    // =============================
    // VALIDATION RULES
    // =============================

    public function rules(): array
    {
        return [
            '*.nama_produk'  => 'required|string',
            '*.sku'          => 'required|string',
            '*.kategori'     => 'required|string',
            '*.supplier'     => 'required|string',
            '*.harga_beli'   => 'required|numeric|min:0',
            '*.harga_jual'   => 'required|numeric|min:0',
            '*.stok'         => 'required|integer|min:0',
            '*.minimum_stok' => 'required|integer|min:0',
            '*.status'       => 'nullable|in:active,draft',
        ];
    }

    // =============================
    // CHUNK SIZE
    // =============================

    public function chunkSize(): int
    {
        return 100;
    }

    // =============================
    // SYNC ATRIBUT
    // =============================

    private function syncAttributes($product, $attributeString)
    {
        $product->productAttributes()->delete();

        if (!$attributeString) {
            return;
        }

        $pairs = explode(';', $attributeString);

        foreach ($pairs as $pair) {

            if (!Str::contains($pair, ':')) {
                continue;
            }

            [$name, $value] = explode(':', $pair);

            $name = trim($name);
            $value = trim($value);

            if ($name && $value) {
                ProductAttribute::create([
                    'product_id' => $product->id,
                    'name'       => $name,
                    'value'      => $value,
                ]);
            }
        }
    }
}