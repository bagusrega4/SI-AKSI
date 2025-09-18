<?php

namespace App\Enums;

enum Status: string
{
    case ENTRI_DOKUMEN = 'Entri Dokumen';
    case PENGECEKAN_DOKUMEN = 'Pengecekan Dokumen';
    case DITOLAK = 'Ditolak';
    case DISETUJUI = 'Disetujui';
    case SELESAI = 'Selesai';

    /**
     * Get all statuses.
     */
    public static function getAll(): array
    {
        return array_map(fn($status) => $status->value, self::cases());
    }
}
