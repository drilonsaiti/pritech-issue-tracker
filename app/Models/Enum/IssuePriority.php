<?php

namespace App\Models\Enum;

enum IssuePriority: string
{
    case Low = 'low';
    case Medium = 'medium';
    case High = 'high';

    public function label(): string
    {
        return match ($this) {
            IssuePriority::Low => 'Low',
            IssuePriority::Medium => 'Medium',
            IssuePriority::High => 'High',
        };
    }
}
