<?php

declare(strict_types=1);

namespace App\Repositories;

use App\DTO\TaskDTO;
use App\Models\Task;
use App\Repositories\Interfaces\ITaskRepository;

class TaskRepository implements ITaskRepository
{
    public function findByCriteria(TaskDTO $search): array
    {

        $data = [];

        if ($search->user === null) {
            $builder = Task::select('*');
        } else {
            $builder = $search->user->Tasks();
        }

        if ($search->priority !== null) {
            $builder = $builder->where('priority', '=', $search->priority);
        }
        if ($search->status !== null) {
            $builder = $builder->where('status', '=', $search->status);
        }
        if ($search->query != null) {
            $builder = $builder->whereFullText(['title', 'description'], $search->query);
        }

        $builder = $builder->orderBy($search->sort1[0], $search->sort1[1])
            ->orderBy($search->sort2[0], $search->sort2[1])
            ->get();

        foreach ($builder as $one) {
            $item = $one->toArray();
            $item['hasChildren'] = $one->SubTasks->count() > 0;
            $data[] = $item;
        }

        $tasks = [];
        $tasks['count'] = count($data);
        $tasks['data'] = $data;

        return $tasks;
    }
}
