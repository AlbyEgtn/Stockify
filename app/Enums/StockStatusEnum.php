<?php

namespace App\Enums;

enum StockStatusEnum: string
{
    case PENDING = 'Pending';
    case DITERIMA = 'Diterima';
    case DITOLAK = 'Ditolak';
    case DIKELUARKAN = 'Dikeluarkan';
}
