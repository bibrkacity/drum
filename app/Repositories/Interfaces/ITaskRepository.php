<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\DTO\TaskDTO;

interface ITaskRepository
{
    public function findByCriteria(TaskDTO $search): array;
}
