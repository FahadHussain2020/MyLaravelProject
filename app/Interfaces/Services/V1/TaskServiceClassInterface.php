<?php

namespace App\Interfaces\Services\V1;


interface TaskServiceClassInterface
{
    public function index();
    public function store($req);

    public function getTaskById($id);
    public function updateTask($req, $id);
    public function deleteTask($id);
    public function markAsComplete($id);
    public function markAsInComplete($id);
}
