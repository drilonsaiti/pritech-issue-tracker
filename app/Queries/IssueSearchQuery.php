<?php

namespace App\Queries;

use App\Models\Issue;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;

class IssueSearchQuery
{

    public function search(array $filters)
    {
        $query = Issue::query();


        if(!empty($filters['search'])) {
            $term = $filters['search'];
            $query->where(function ($query) use ($term) {
                $query->where('title', 'LIKE', "%{$term}%")
                    ->orWhere('description', 'LIKE', "%{$term}%");
            });
        }

        // Tag filter
        if (!empty($filters['tag'])) {
            $query->whereHas('tags', function ($q) use ($filters) {
                $q->whereIn('tags.id', (array) $filters['tag']);
            });
        }

        // Status filter
        if (!empty($filters['status'])) {
            $query->whereIn('status', (array) $filters['status']);
        }

        // Priority filter
        if (!empty($filters['priority'])) {
            $query->whereIn('priority', (array) $filters['priority']);
        }

        return $query->with(['project','tags'])->latest()->paginate(15);
    }
}
