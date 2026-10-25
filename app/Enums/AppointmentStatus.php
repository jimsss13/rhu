<?php

namespace App\Enums;

/**
 * Possible statuses for appointments.
 */
enum AppointmentStatus: string
{
    case Pending = 'Pending';
    case Approved = 'Approved';
    case Completed = 'Completed';
    case Rejected = 'Rejected';
}
