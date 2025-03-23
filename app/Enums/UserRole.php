<?php

namespace App\Enums;

enum UserRole: string
{
    case AUTHOR = 'author';
    case COLLABORATOR = 'collaborator';
}
