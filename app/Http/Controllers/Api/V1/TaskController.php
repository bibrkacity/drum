<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Repositories\Interfaces\ITaskRepository;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends ApiController
{
    public function __construct(
        private readonly ITaskRepository $taskRepository)
    {
    }

    /**
     * @OA\Get(
     *     path="/tasks",
     *     summary="List of tasks",
     *     description="List of tasks by filters",
     *     tags={"Tasks"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *          name="status",
     *          description="Task status for filters",
     *          required=false,
     *          in="query",
     *
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ),
     *
     *     @OA\Parameter(
     *          name="priority",
     *          description="Priority of task for filters",
     *          required=false,
     *          in="query",
     *
     *          @OA\Schema(
     *              type="integer",
     *          )
     *      ),
     *
     *     @OA\Parameter(
     *          name="query",
     *          description="Query string for filters",
     *          required=false,
     *          in="query",
     *
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ),
     *
     *     @OA\Parameter(
     *          name="query",
     *          description="Query string for filters",
     *          required=false,
     *          in="query",
     *
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ),
     *
     *     @OA\Parameter(
     *          name="user_id",
     *          description="Id of user for filters",
     *          required=false,
     *          in="query",
     *
     *          @OA\Schema(
     *              type="integer",
     *          )
     *      ),
     *
     *     @OA\Parameter(
     *          name="sort1Field",
     *          description="Field name for first sorting (priority,completed_at,created_at)",
     *          required=false,
     *          in="query",
     *
     *          @OA\Schema(
     *              type="string",
     *              default="priority",
     *          )
     *      ),
     *
     *     @OA\Parameter(
     *          name="sort1Dir",
     *          description="Direction of sorting of first sort field (asc,desc)",
     *          required=false,
     *          in="query",
     *
     *          @OA\Schema(
     *              type="string",
     *              default="desc",
     *          )
     *      ),
     *
     *     @OA\Parameter(
     *          name="sort2Field",
     *          description="Field name for second sorting (priority,completed_at,created_at)",
     *          required=false,
     *          in="query",
     *
     *          @OA\Schema(
     *              type="string",
     *              default="created_at",
     *          )
     *      ),
     *
     *     @OA\Parameter(
     *          name="sort2Dir",
     *          description="Direction of sorting of second sort field (asc,desc)",
     *          required=false,
     *          in="query",
     *
     *          @OA\Schema(
     *              type="string",
     *              default="asc",
     *          )
     *      ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="The list of tasks in JSON",
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $tasks = TaskService::getTasks($request, $this->taskRepository);

        if (is_string($tasks)) {
            return new JsonResponse(data: $tasks, status: 400, json: true);
        }

        return new JsonResponse(data: $tasks, status: 200, json: false);

    }

    /**
     * @OA\Post(
     *     path="/tasks",
     *     summary="Add new task",
     *     description="Add new task",
     *     tags={"Tasks"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *          name="priority",
     *          description="Task priority",
     *          required=true,
     *          in="query",
     *
     *          @OA\Schema(
     *              type="integer",
     *          )
     *      ),
     *
     *     @OA\Parameter(
     *          name="title",
     *          description="Title of task",
     *          required=true,
     *          in="query",
     *
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ),
     *
     *     @OA\Parameter(
     *          name="description",
     *          description="Description of task",
     *          required=true,
     *          in="query",
     *
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Created task in JSON",
     *     )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $task = TaskService::addTask($request);

        return new JsonResponse(data: $task, status: 200, json: false);
    }

    /**
     * @OA\Get(
     *     path="/tasks/{id}",
     *     summary="Tree of one task",
     *     description="Tree of one task",
     *     tags={"Tasks"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *          name="id",
     *          description="Id of task",
     *          required=true,
     *          in="path",
     *
     *          @OA\Schema(
     *              type="integer",
     *          )
     *      ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Tree of on task in JSON",
     *     )
     * )
     */
    public function show(int $id): JsonResponse
    {
        $tasks = TaskService::getOneTask($id);

        if (is_string($tasks)) {
            return new JsonResponse(data: $tasks, status: 400, json: true);
        }

        return new JsonResponse(data: $tasks, status: 200, json: false);
    }

    /**
     * @OA\Put(
     *     path="/tasks/{id}",
     *     summary="Update task",
     *     description="Update task",
     *     tags={"Tasks"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *          name="id",
     *          description="Id of task",
     *          required=true,
     *          in="path",
     *
     *          @OA\Schema(
     *              type="integer",
     *          )
     *      ),
     *
     *     @OA\Parameter(
     *          name="status",
     *          description="Task status",
     *          required=false,
     *          in="query",
     *
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ),
     *
     *     @OA\Parameter(
     *          name="priority",
     *          description="Priority of task",
     *          required=false,
     *          in="query",
     *
     *          @OA\Schema(
     *              type="integer",
     *          )
     *      ),
     *
     *     @OA\Parameter(
     *          name="title",
     *          description="Title of task",
     *          required=false,
     *          in="query",
     *
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ),
     *
     *     @OA\Parameter(
     *          name="description",
     *          description="Description of task",
     *          required=false,
     *          in="query",
     *
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Tree of on task in JSON",
     *     )
     * )
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $task = TaskService::updateTask($request, $id);

        if (is_string($task)) {
            return new JsonResponse(data: $task, status: 400, json: true);
        }

        return new JsonResponse(data: ['message' => 'Ok'], status: 200, json: false);
    }

    /**
     * @OA\Patch(
     *     path="/tasks/{id}/done",
     *     summary="Set task as done",
     *     description="Set task as done",
     *     tags={"Tasks"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *          name="id",
     *          description="Id of task",
     *          required=true,
     *          in="path",
     *
     *          @OA\Schema(
     *              type="integer",
     *          )
     *      ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Ok if success, error message if error",
     *     )
     * )
     */
    public function setDone(Request $request, int $id): JsonResponse
    {
        $task = TaskService::setDone($request, $id);

        if (is_string($task)) {
            return new JsonResponse(data: $task, status: 400, json: true);
        }

        return new JsonResponse(data: ['message' => 'Ok'], status: 200, json: false);
    }

    /**
     * @OA\Delete(
     *     path="/tasks/{id}",
     *     summary="Delete task",
     *     description="Delete task",
     *     tags={"Tasks"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *          name="id",
     *          description="Id of task",
     *          required=true,
     *          in="path",
     *
     *          @OA\Schema(
     *              type="integer",
     *          )
     *      ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Ok if success, error message if error",
     *     )
     * )
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $task = TaskService::destroyTask($request, $id);

        if (is_string($task)) {
            return new JsonResponse(data: $task, status: 400, json: true);
        }

        return new JsonResponse(data: ['message' => 'Ok'], status: 200, json: false);
    }
}
