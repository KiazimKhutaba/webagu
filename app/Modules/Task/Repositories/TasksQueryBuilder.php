<?php

namespace App\Modules\Task\Repositories;

use App\Modules\Task\Models\Task;
use App\Modules\Task\Models\TaskHistory;
use App\Modules\Task\Models\TaskStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;

class TasksQueryBuilder
{
    /**
     * Returns query builder for all tasks
     *
     * @param array $fields
     * @param int $lecture_id
     * @return EloquentBuilder|QueryBuilder|Task
     */
    public function getListOfTasks(array $fields = ['*'], int $lecture_id = 0): EloquentBuilder|QueryBuilder|Task
    {
        $query = Task::with('lecture');

        if (0 !== $lecture_id) {
            $query->where(['lecture_id' => $lecture_id]);
        }

        return $query->select($fields);
    }


    /**
     * Return task query builder with associated files and students that send task for review
     *
     * @param int $task_id
     * @param int $student_id
     * @param array $relations
     * @return Task|QueryBuilder|EloquentBuilder
     */
    public function getStudentTask(int $task_id, int $student_id, array $relations = []): Task|QueryBuilder|EloquentBuilder
    {
        $default_relations = ['files'];

        return Task::with([
            ...$default_relations,
            ...$relations
        ])
            ->from('tasks as t')
            ->leftJoin('task_statuses as tss', function (JoinClause $join) use ($student_id) {
                $join->on('tss.task_id', '=', 't.id')
                    ->where('tss.student_id', '=', $student_id);
            })
            ->select([
                't.id',
                't.title',
                't.description',
                't.is_visible',
                't.is_seminar',
                't.lecture_id',
                't.user_id',
                't.created_at',
                't.updated_at',
                't.expired_at',
                'tss.status as review_status',
            ])
            ->where(['t.id' => $task_id]);
    }

    /**
     * Returns tasks that users have sent for review
     *
     * @return EloquentBuilder|QueryBuilder
     */
    public function getStudentsTasks(): EloquentBuilder|QueryBuilder
    {
        return TaskStatus::from('task_statuses as tss')
            ->select([
                't.id as task_id',
                't.title',
                't.is_visible',
                't.is_seminar',
                't.lecture_id',
                'tss.student_id',
                DB::raw("CONCAT(u.lastname, ' ', u.firstname) as student_name"),
                't.created_at',
                't.updated_at',
                'tss.status as review_status',
                'l.title as lecture_title'
            ])
            ->join('tasks as t', 't.id', '=', 'tss.task_id')
            ->join('lectures as l', 'l.id', '=', 't.lecture_id')
            ->join('users as u', 'u.id', '=', 'tss.student_id')
            ->orderBy('tss.created_at', 'desc')
            ->orderBy('tss.task_id');
    }


    public function getStudentTasksWithStatuses(int $student_id): QueryBuilder|EloquentBuilder
    {
        return Task::from('tasks as t')
            ->select([
                't.id',
                't.title',
                't.is_visible',
                't.is_seminar',
                't.lecture_id',
                't.user_id',
                't.created_at',
                't.updated_at',
                'ts.status as review_status',
                'lectures.title as lecture_title'
            ])
            ->leftJoin('task_statuses as ts', function (JoinClause $join) use ($student_id) {
                $join->on('ts.task_id', '=', 't.id')
                    ->where('ts.student_id', '=', $student_id);
            })
            ->join('lectures', 't.lecture_id', '=', 'lectures.id');
    }


    public function getStudentTaskHistory(int $task_id, int $student_id): EloquentBuilder|QueryBuilder
    {
        return TaskHistory::with(['files', 'user'])
            ->from('task_histories as th')
            ->where(['task_id' => $task_id])
            ->where(function ($query) use ($student_id) {
                $query->where('sender_id', $student_id)->orWhere('receiver_id', $student_id);
            })
            ->select(['th.*', 'th.status as review_status'])
            ->orderBy('id', 'desc');
    }
}
