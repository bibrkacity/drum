<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\TaskDTO;
use App\Enums\TaskStatus;
use App\Models\Task;
use App\Models\User;
use App\Repositories\Interfaces\ITaskRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TaskService
{
    public static function getTasks(Request $request, ITaskRepository $taskRepository): array|string
    {
        $validate = self::validateFilters($request);

        if (is_string($validate)) {
            return $validate;
        }

        $dto = self::factory($request);

        return $taskRepository->findByCriteria($dto);
    }

    public static function addTask(Request $request)
    {

        $rules = [
            'priority' => 'required|integer|min:1|max:5',
            'title' => 'required|string',
            'description' => 'required|string',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errors = $validator->errors();

            return $errors->toJson();
        }

        $task = new Task();
        $task->userId = $request->user()->id;
        $task->priority = $request->get('priority');
        $task->status = TaskStatus::Todo;
        $task->title = $request->get('title');
        $task->description = $request->get('description');

        $task->save();

        return $task->toArray();
    }

    public static function getOneTask(int $id): array|string
    {

        $task = Task::find($id);

        if (! $task instanceof Task) {
            return 'Task with id='.$id.' does not exist';
        }

        $data = $task->toArray();
        $data['children'] = self::getChildren($task);

        return $data;

    }

    public static function updateTask(Request $request, int $id): bool|string
    {
        $task = Task::find($id);

        if (! $task instanceof Task) {
            return 'Task with id='.$id.' does not exist';
        }

        if ($task->user_id != $request->user()->id) {
            return 'Task with id='.$id.' does not assign to you';
        }

        $rules = [
            'status' => [Rule::enum(TaskStatus::class)],
            'priority' => 'integer|min:1|max:5',
            'title' => 'string',
            'description' => 'string',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errors = $validator->errors();

            return $errors->toJson();
        }

        $status = $request->get('status');
        if ($status == TaskStatus::Done) {
            if (! self::checkChildrenDone($task)) {
                return json_encode(['message' => 'You can\'t set task with id='.$id.' as done: it consists not-done subtasks']);
            }
        }

        $fields = ['priority', 'title', 'description'];
        foreach ($fields as $field) {
            $$field = $request->get($field);
            if ($$field !== null) {
                $task->$field = $$field;
            }
        }
        if ($status !== null) {
            $task->status = $status;
        }

        $task->save();

        return true;

    }

    public static function setDone(Request $request, int $id): bool|string
    {
        $task = Task::find($id);

        if (! $task instanceof Task) {
            return 'Task with id='.$id.' does not exist';
        }

        if ($task->user_id != $request->user()->id) {
            return 'Task with id='.$id.' does not assign to you';
        }

        if (! self::checkChildrenDone($task)) {
            return json_encode(['message' => 'You can\'t set task with id='.$id.' as done: it consists not-done subtasks']);
        }

        $task->status = TaskStatus::Done;
        $task->completed_at = date('Y-m-d H:i:s');

        $task->save();

        return true;

    }

    public static function destroyTask(Request $request, int $id): bool|string
    {
        $task = Task::find($id);

        if (! $task instanceof Task) {
            return 'Task with id='.$id.' does not exist';
        }

        if ($task->user_id != $request->user()->id) {
            return 'Task with id='.$id.' does not assign to you';
        }

        if ($task->SubTasks->count() > 0) {
            return json_encode(['message' => 'You can\'t set task with id='.$id.' as done: it consists subtasks']);
        }

        if ($task->status == TaskStatus::Done) {
            return 'Task with id='.$id.' is done - it can\'t be deleted';
        }

        $task->delete();

        return true;

    }

    private static function validateFilters(Request $request): string|bool
    {
        $rules = [
            'status' => [Rule::enum(TaskStatus::class)],
            'priority' => 'integer|min:1|max:5',
            'user_id' => 'integer|min:1',
            'query' => 'string',
            'sort1Field' => 'string|in:priority,completed_at,created_at',
            'sort1Dir' => 'string|in:asc,desc',
            'sort2Field' => 'string|in:priority,completed_at,created_at',
            'sort2Dir' => 'string|in:asc,desc',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errors = $validator->errors();

            return $errors->toJson();
        }

        return true;
    }

    private static function factory(Request $request): TaskDTO
    {
        $dto = new TaskDTO();

        $dto->priority = $request->get('priority');
        $dto->status = $request->get('status');
        $dto->query = $request->get('query');
        $userId = $request->get('user_id');
        if ($userId !== null) {
            $dto->user = User::find($userId);
        }

        $sort1Field = $request->get('sort1Field') ?? 'priority';
        $sort1Dir = $request->get('sort1Dir') ?? 'desc';
        $dto->sort1 = [$sort1Field, $sort1Dir];

        $sort2Field = $request->get('sort2Field') ?? 'created_at';
        $sort2Dir = $request->get('sort2Dir') ?? 'asc';
        $dto->sort2 = [$sort2Field, $sort2Dir];

        return $dto;
    }

    private static function getChildren(Task $task): array
    {

        $data = [];

        $children = $task->SubTasks;

        foreach ($children as $child) {
            $item = $child->toArray();
            $item['children'] = self::getChildren($child);
            $data[] = $item;
        }

        return $data;
    }

    private static function checkChildrenDone(Task $task): bool
    {
        $children = $task->SubTasks;

        $check = true;

        foreach ($children as $child) {
            if ($child->status != TaskStatus::Done) {
                $check = false;
                break;
            }
        }

        return $check;
    }
}
