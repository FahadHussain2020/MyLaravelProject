<?php

namespace App\Interfaces\Repositories\V1;


interface TaskRepositoryInterface
{
    public function index();
    public function store($req);

    public function getTaskById($id);

    public function updateTask($id, $data);

    public function deleteTask($id);
    public function markAsComplete($id);
    public function markAsInComplete($id);
}
