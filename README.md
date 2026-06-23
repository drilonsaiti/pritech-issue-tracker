# PRITECH – Mini Issue Tracker

A Laravel-based issue tracking application for small teams to manage projects, issues, tags, and comments, with AJAX-driven interactions for tags, members, and comments.

## Tech Stack

- Laravel 13
- Blade templates + Bootstrap 5 (via CDN — no npm build step required)
- MySQL (or SQLite)
- Laravel Breeze (authentication)
- Vanilla JavaScript (Fetch API) for AJAX interactions

## Features

### Core
- **Projects** — full CRUD, list with issue counts, show page with nested issues
- **Issues** — full CRUD, filterable by status/priority/tag, eager-loaded relations to avoid N+1
- **Tags** — full CRUD, unique names, AJAX attach/detach on issues
- **Comments** — AJAX paginated list, AJAX create with inline validation errors

### Bonus
- **Members** — assign/unassign users to issues via a second pivot (`issue_user`), AJAX attach/detach
- **Authorization** — Policies restrict project edit/delete to the owner, and comment edit/delete to the original author
- **Search** — debounced AJAX text search on issue title/description

## Setup

1. Clone the repo and install PHP dependencies:
```bash
   composer install
```

2. Copy the environment file and generate an app key:
```bash
   cp .env.example .env
   php artisan key:generate
```

3. Configure your database in `.env`:
``` 
DB_CONNECTION=mysql
DB_DATABASE=pritech_issue_tracker
DB_USERNAME=root
DB_PASSWORD=
```

4. Run migrations and seed demo data:
```bash
   php artisan migrate:fresh --seed
```

5. Serve the app:
```bash
   php artisan serve
```
(Or use Laravel Herd / Valet if configured locally.)

No `npm install` or frontend build step is required — Bootstrap is loaded via CDN.

## Demo Accounts

The seeder creates 3 random users via Laravel's default `User` factory. To log in, either:
- Register a new account at `/register`, or
- Login: demo@example.com / password"

## Database Schema

| Table | Notes |
|---|---|
| `projects` | `owner_id` (FK to users), `start_date`, `deadline` added via separate migration |
| `issues` | belongs to `projects`; `status` (enum), `priority` (enum) |
| `tags` | unique `name`; many-to-many with issues via `issue_tag` |
| `comments` | belongs to `issues`; `user_id` (FK to users) for ownership |
| `issue_user` | pivot for member assignment |

## Architecture Notes

- **Form Requests** handle all validation (`StoreXRequest`/`UpdateXRequest` per entity)
- **Enums** (`IssueStatus`, `IssuePriority`) back the `status`/`priority` columns via Eloquent casts, validated with Laravel's `Enum` validation rule
- **Policies** (`ProjectPolicy`, `CommentPolicy`) enforce ownership-based authorization
- **Eager loading** applied throughout (`with()`, `withCount()`) to avoid N+1 queries on index/show pages
- AJAX endpoints return JSON; standard page loads return Blade views — both share the same underlying query logic (e.g. `IssueSearchQuery`)

## Known Limitations / Possible Improvements

- Comment editing UI not implemented (delete is, via the `CommentPolicy`)
- Search results pagination is not rebuilt during live AJAX search (only standard filter+reload pagination is fully wired)
- No automated test suite included
