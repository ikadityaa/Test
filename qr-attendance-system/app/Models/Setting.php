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

    public static function get(string $key, $default = null)
    {
        $record = static::query()->where('key', $key)->first();
        if (!$record) {
            return $default;
        }

        $value = $record->value;
        $decoded = json_decode($value, true);
        return json_last_error() === JSON_ERROR_NONE ? $decoded : $value;
    }

    public static function set(string $key, $value): self
    {
        $payload = is_array($value) || is_object($value)
            ? json_encode($value)
            : (string) $value;

        return static::updateOrCreate(['key' => $key], ['value' => $payload]);
    }
}