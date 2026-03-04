<?php

namespace App\Services;

final class UsernameNormalizer
{
    public function normalize(string $value): string
    {
        $value = trim($value);
        $value = strtolower($value);

        // No diacritics allowed. Keep only a-z, 0-9, underscore, dot, dash.
        $value = preg_replace('/[^a-z0-9._-]+/', '', $value) ?? '';

        return $value;
    }
}
