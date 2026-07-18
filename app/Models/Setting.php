<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
    ];

    protected static $settingsCache = null;

    // Static helper to get setting value
    public static function get($key, $default = null)
    {
        if (is_null(self::$settingsCache)) {
            try {
                self::$settingsCache = self::pluck('value', 'key')->toArray();
            } catch (\Exception $e) {
                return $default;
            }
        }

        return self::$settingsCache[$key] ?? $default;
    }

    // Static helper to set setting value
    public static function set($key, $value)
    {
        $setting = self::updateOrCreate(['key' => $key], ['value' => $value]);
        self::$settingsCache = null; // Clear cache
        return $setting;
    }
}
