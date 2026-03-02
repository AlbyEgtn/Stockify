<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
    ];
    public function isAdmin()
    {
        return $this->role === 'Admin';
    }

    public function isManager()
    {
        return $this->role === 'Manajer Gudang';
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function stockTransactions()
    {
        return $this->hasMany(StockTransaction::class);
    }
    public function activities()
    {
        return $this->hasMany(UserActivity::class);
    }

}
