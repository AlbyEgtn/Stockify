<?php

namespace App\Enums;

enum RoleEnum: string
{
    case ADMIN = 'Admin';
    case STAFF = 'Staff Gudang';
    case MANAGER = 'Manajer Gudang';
}
