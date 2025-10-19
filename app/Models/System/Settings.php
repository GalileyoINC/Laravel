<?php

declare(strict_types=1);

namespace App\Models\System;

use Throwable;

class Settings
{
    /**
     * Get a setting value from the persistent store (settings_safe table).
     * Falls back to provided default when missing.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        try {
            $row = SettingsSafe::query()->find($key);
            if ($row === null) {
                return $default;
            }
            $value = $row->value;

            // Try to cast common scalar types stored as strings
            if (is_numeric($value)) {
                // differentiate int vs float
                return str_contains((string) $value, '.') ? (float) $value : (int) $value;
            }
            $lower = mb_strtolower(trim((string) $value));
            if ($lower === 'true') {
                return true;
            }
            if ($lower === 'false') {
                return false;
            }

            // Attempt JSON decode for arrays/objects
            $json = json_decode((string) $value, true);
            if (json_last_error() === JSON_ERROR_NONE && $json !== null) {
                return $json;
            }

            return $value;
        } catch (Throwable) {
            return $default;
        }
    }
}
