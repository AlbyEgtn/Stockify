<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'system_name',
        'logo',
    ];

    /**
     * Ambil nilai kolom setting
     */
    public static function getValue($column, $default = null)
    {
        $setting = static::first();

        return $setting?->{$column} ?? $default;
    }

    /**
     * Update nilai kolom setting
     */
    public static function setValue($column, $value)
    {
        $setting = static::first();

        if (!$setting) {
            $setting = static::create([
                $column => $value
            ]);
        } else {
            $setting->update([
                $column => $value
            ]);
        }

        return $setting;
    }
}