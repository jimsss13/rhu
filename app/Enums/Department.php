<?php

namespace App\Enums;

/**
 * Departments available in the RHU system.
 */
enum Department: string
{
    case IT = 'IT DEPARTMENT';
    case SuperAdmin = 'SUPER ADMIN';
    case Inventory = 'INVENTORY';
    case Laboratory = 'LABORATORY';
    case Consultation = 'CONSULTATION';
    case Vaccination = 'VACCINATION';
    case Blood = 'BLOOD';
    case Client = 'Client';
}
