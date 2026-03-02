<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'supplier_id',
        'user_id',
        'type',
        'status',
        'quantity',
        'difference',
        'stock_before',
        'stock_after',
        'note',
    ];


    /*
    |----------------------------------------------------------------------
    | RELATION
    |----------------------------------------------------------------------
    */

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /*
    |----------------------------------------------------------------------
    | ACCESSOR
    |----------------------------------------------------------------------
    */

    public function getTypeLabelAttribute()
    {
        return $this->type === 'IN'
            ? 'Barang Masuk'
            : 'Barang Keluar';
    }
}
