<?php

namespace App\Enums;

/**
 * Possible statuses for user accounts.
 */
enum UserStatus: string
{
    case Pending = 'Pending';
    case Approved = 'Approved';
    case Active = 'Active';
    case Rejected = 'Rejected';
}
