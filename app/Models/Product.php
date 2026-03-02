<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
Product::active()->get();
class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'supplier_id',
        'name',
        'sku',
        'description',
        'purchase_price',
        'stock',
        'selling_price',
        'image',
        'minimum_stock',
        'status',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    public function productAttributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }

    public function stockTransactions()
    {
        return $this->hasMany(StockTransaction::class);
    }
    public function scopeLowStock($query)
    {
        return $query->whereColumn('stock', '<=', 'minimum_stock');
    }
    public function latestIncoming()
    {
        return $this->hasOne(\App\Models\StockTransaction::class)
                    ->where('type', 'IN')
                    ->latestOfMany();
    }

    public function latestOutgoing()
    {
        return $this->hasOne(\App\Models\StockTransaction::class)
                    ->where('type', 'OUT')
                    ->latestOfMany();
    }
    public function latestTransaction()
    {
        return $this->hasOne(\App\Models\StockTransaction::class)
                    ->latestOfMany();
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }
    
}
