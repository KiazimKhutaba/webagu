<?php

namespace App\Modules\Task\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Task\Repositories\TasksRepository;

class TaskReportsController extends Controller
{
    public function __construct(
        private readonly TasksRepository $reportsRepository
    ) {}

    public function getTasksReportsProgress(): array
    {
        return $this->reportsRepository->getTasksProgress();
    }
}
