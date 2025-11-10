<?php

namespace WhitePage\Enums;

enum RolesEnum: string
{
    case ADMIN = 'admin';
    case MANAGER = 'manager';
    case USER = 'user';

    public function label(): string
    {
        return match ($this) {
            static::ADMIN => 'Admin',
            static::MANAGER => 'Manager',
            static::USER => 'User',
        };
    }
}
