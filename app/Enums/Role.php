<?php

namespace App\Enums;

enum Role: string
{
    case PPK = 'admin';
    case KEUANGAN = 'keuangan';
    case USER = 'user';

    /**
     * Get all statuses.
     */
    public static function getAll(): array
    {
        return array_map(fn($role) => $role->value, self::cases());
    }
}
