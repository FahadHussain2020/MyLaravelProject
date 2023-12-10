<?php

namespace App\Repository\V1;

use App\Interfaces\Repositories\V1\TaskRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Models\Task;

class TaskRepository implements TaskRepositoryInterface
{
    private $model;

    public function __construct(Task $modelNameObject)
    {
        $this->model = $modelNameObject;
    }

    function index()
    {
        $data = Task::orderBy('created_at', 'desc')->paginate(5);
        if ($data->isEmpty()) {
            return response()->json('No Tasks Available', 200);
        }
        return response()->json($data);
    }

    function store($req)
    {
        $data = $req->only(['title', 'status']);

        $title = $data['title'];
        $status = $data['status'];

        // Create a new task
        $newTask = new Task();
        $newTask->title = $title;
        $newTask->status = $status;
        $newTask->save();

        return response()->json($newTask, 201);
    }

    function getTaskById($id)
    {
        $task = Task::findOrFail($id);

        return response()->json($task, 200);
    }

    function updateTask($id, $data)
    {
        try {
            $task = Task::find($id);

            $task->update([
                'title' => $data['title'],
                'status' => $data['status'],
            ]);

            return response()->json($task, 200);
        } catch (ModelNotFoundException $e) {
            throw new \Exception('Task not found.');
        } catch (\Exception $e) {
            throw new \Exception('Failed to update task.');
        }
    }

    function deleteTask($id)
    {
        try {
            $task = Task::find($id);
            if ($task) {
                $task->delete();
                return response()->json('Task deleted successfully', 200);
            } else {
                return response()->json('Task not found', 404);
            }
        } catch (\Exception $e) {
            return response()->json('Failed to delete task', 500);
        }
    }
    function markAsComplete($id)
    {
        try {
            $task = Task::find($id);
            if ($task) {
                $task->update([
                    'status' => 1
                ]);
                return response()->json('Task Is Mark Completed', 200);
            } else {
                return response()->json('Task not found', 404);
            }
        } catch (\Exception $e) {
            return response()->json('Failed to mark task as complete.', 500);
        }
    }
    function markAsInComplete($id)
    {
        try {
            $task = Task::find($id);
            if ($task) {
                $task->update([
                    'status' => 0
                ]);
                return response()->json('Task Is Mark In Complete', 200);
            } else {
                return response()->json('Task not found', 404);
            }
        } catch (\Exception $e) {
            return response()->json('Failed to mark task as in complete.', 500);
        }
    }
}
