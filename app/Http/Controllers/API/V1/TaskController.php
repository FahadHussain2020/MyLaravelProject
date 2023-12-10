<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Interfaces\Services\V1\TaskServiceClassInterface;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class TaskController extends Controller
{

    private $interface;

    public function __construct(TaskServiceClassInterface $interface)
    {
        $this->interface = $interface;
    }


    public function index()
    {
        try {
            $res = $this->interface->index();
            return $res;
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $res = $this->interface->getTaskById($id);
            return $res;
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Task not found.'], 404);
        }
    }

    public function store(Request $request)
    {
        try {
            $res = $this->interface->store($request);
            return $res;
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $res = $this->interface->updateTask($request, $id);
            return response()->json(['message' => json_decode($res->getContent(), true)]);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $res = $this->interface->deleteTask($id);
            return response()->json(['message' => json_decode($res->getContent(), true)]);
        } catch (\Exception $e) {
            // Handle other exceptions (e.g., database errors)
            return response()->json(['error' => 'Failed to delete task.'], 500);
        }
    }

    public function markAsComplete($id)
    {
        try {
            $res = $this->interface->markAsComplete($id);
            return response()->json(['message' => json_decode($res->getContent(), true)]);
        } catch (\Exception $e) {
            // Handle other exceptions (e.g., database errors)
            return response()->json(['error' => 'Failed to mark task as complete.'], 500);
        }
    }

    public function markAsInComplete($id)
    {
        try {
            $res = $this->interface->markAsInComplete($id);
            return response()->json(['message' => json_decode($res->getContent(), true)]);
        } catch (\Exception $e) {
            // Handle other exceptions (e.g., database errors)
            return response()->json(['error' => 'Failed to mark task as in complete.'], 500);
        }
    }
}
