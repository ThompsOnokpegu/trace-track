<?php

namespace App\Enums;

enum ProjectStage: string
{
    // Pre-Installation
    case SCHEDULED_VISIT = 'scheduled_visit';
    case DRAWING_COMPLETED = 'drawing_completed';
    
    // Manufacturing & Logistics
    case PRODUCTION = 'production';
    case PRODUCTION_COMPLETED = 'production_completed';
    case SHIPPED = 'shipped';
    case ARRIVED_NIGERIA = 'arrived_nigeria';
    case CUSTOMS_CLEARANCE = 'customs_clearance';
    case WAREHOUSE_LAGOS = 'warehouse_lagos';
    case IN_TRANSIT_SITE = 'in_transit_site';
    
    // Installation
    case INSTALLATION_SCHEDULED = 'installation_scheduled';
    case INSTALLATION_IN_PROGRESS = 'installation_in_progress';
    case INSTALLATION_COMPLETED = 'installation_completed';
    case COMMISSIONING = 'commissioning';
    case HANDOVER = 'handover';

    // Helper method to get human-readable labels
    public function label(): string
    {
        return match($this) {
            self::SCHEDULED_VISIT => 'Scheduled Site Visit',
            self::DRAWING_COMPLETED => 'Technical Drawing Completed',
            self::PRODUCTION => 'Sent for Production',
            self::PRODUCTION_COMPLETED => 'Production Completed',
            self::SHIPPED => 'Elevator Shipped',
            self::ARRIVED_NIGERIA => 'Arrived in Nigeria',
            self::CUSTOMS_CLEARANCE => 'Undergoing Customs Clearance',
            self::WAREHOUSE_LAGOS => 'Arrived at Lagos Warehouse',
            self::IN_TRANSIT_SITE => 'In Transit to Project Site',
            self::INSTALLATION_SCHEDULED => 'Installation Scheduled',
            self::INSTALLATION_IN_PROGRESS => 'Installation In Progress',
            self::INSTALLATION_COMPLETED => 'Installation Completed',
            self::COMMISSIONING => 'Commissioning',
            self::HANDOVER => 'Project Handover',
        };
    }

    // Helper for UI Colors (Tailwind classes)
    public function color(): string
    {
        return match($this) {
            self::HANDOVER, self::COMMISSIONING => 'green',
            self::INSTALLATION_IN_PROGRESS, self::CUSTOMS_CLEARANCE => 'yellow',
            self::SHIPPED, self::IN_TRANSIT_SITE => 'blue',
            default => 'gray',
        };
    }
}