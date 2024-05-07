<?php

declare(strict_types=1);

namespace App\DTO;

use App\Models\User;

class TaskDTO
{
    public ?string $status = null;

    public ?int $priority = null;

    public ?string $query = null;

    public ?User $user = null;

    public array $sort1 = ['priority', 'desc'];

    public array $sort2 = ['created_at', 'asc'];
}
