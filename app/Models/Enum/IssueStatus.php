<?php

namespace App\Models\Enum;

enum IssueStatus: string
{
    case Open = 'open';
    case InProgress = 'in_progress';
    case Closed = 'closed';


    public function label(): string
    {
        return match ($this) {
            IssueStatus::Open => 'Open',
            IssueStatus::InProgress => 'In Progress',
            IssueStatus::Closed => 'Closed',
        };
    }

    public function canStatusChangeTo(self $new): bool
    {
        return match ($this) {
            self::Open => in_array($new, [
                self::InProgress
            ], true),

            self::InProgress => in_array($new, [
                self::Closed
            ]),
            self::Closed => false
        };
    }
}
