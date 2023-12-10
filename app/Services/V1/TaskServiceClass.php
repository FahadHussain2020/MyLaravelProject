<?php

namespace App\Services\V1;

use App\Interfaces\Services\V1\TaskServiceClassInterface;
use Illuminate\Support\Facades\Validator;

class TaskServiceClass implements TaskServiceClassInterface
{
    private $repository;

    public function __construct($repo)
    {
        $this->repository = $repo;
    }

    function index()
    {
        $res = $this->repository->index();
        return $res;
    }

    function store($req)
    {
        $validator = Validator::make($req->all(), [
            'title' => 'required|max:50',
            'status' => 'required|boolean',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        $res = $this->repository->store($req);
        return $res;
    }

    function getTaskById($id)
    {
        $res = $this->repository->getTaskById($id);
        return $res;
    }
    function updateTask($req, $id)
    {
        $validator = Validator::make($req->all(), [
            'title' => 'required|max:50',
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $data = $req->only(['title', 'status']);

        try {
            $res = $this->repository->updateTask($id, $data);

            return $res;
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update task.'], 500);
        }
    }

    function deleteTask($id)
    {
        try {
            $res = $this->repository->deleteTask($id);

            return $res;
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete task.'], 500);
        }
    }
    function markAsComplete($id)
    {
        try {
            $res = $this->repository->markAsComplete($id);

            return $res;
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to mark task as complete.'], 500);
        }
    }
    function markAsInComplete($id)
    {
        try {
            $res = $this->repository->markAsInComplete($id);

            return $res;
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to mark task as in complete.'], 500);
        }
    }
}
