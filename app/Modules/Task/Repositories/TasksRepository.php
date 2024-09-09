<?php

namespace App\Modules\Task\Repositories;

use App\Modules\Authentication\Services\AuthenticatedUser;
use App\Modules\Task\Dtos\StudentAnswerDto;
use App\Modules\Task\Dtos\TaskReviewDto;
use App\Modules\Task\Models\Task;
use App\Modules\Task\Models\TaskHistory;
use App\Modules\Task\Models\TaskStatus;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Throwable;

class TasksRepository
{

    public function __construct(
        private readonly TasksQueryBuilder $tasksQueryBuilder
    )
    {
    }


    /**
     * @throws Throwable
     */
    public function addTeacherReview(TaskReviewDto $reviewDto, AuthenticatedUser $user)
    {
        return DB::transaction(function () use ($reviewDto, $user) {
            $task = Task::find($reviewDto->getTaskId());

            $data = [
                'task_id' => $reviewDto->getTaskId(),
                'sender_id' => $user->id,
                'receiver_id' => $reviewDto->getStudentId(),
                'status' => $reviewDto->getStatus(),
                'lecture_id' => $task->lecture_id,
                'message' => $reviewDto->getMessage()
            ];

            $history = TaskHistory::create($data);

            TaskStatus::upsert([
                'task_id' => $reviewDto->getTaskId(),
                'student_id' => $reviewDto->getStudentId(),
                'teacher_id' => $task->user_id,
                'status' => $reviewDto->getStatus()
            ],
                uniqueBy: ['task_id', 'student_id'],
                update: ['status']
            );

            return [
                ...$history->toArray(),
                'user' => $history->user()->first(),
                'files' => []
            ];
        });
    }


    /**
     * @throws Throwable
     */
    public function addStudentAnswer(StudentAnswerDto $dto, AuthenticatedUser $user): TaskHistory
    {
        return DB::transaction(function () use ($dto, $user) {
            $task = Task::find($dto->getTaskId());

            $data = [
                'task_id' => $dto->getTaskId(),
                'sender_id' => $user->id,
                'receiver_id' => $task->user_id,
                'status' => \App\Modules\Task\Enums\TaskStatus::WaitingForReview,
                'lecture_id' => $task->lecture_id,
                'message' => $dto->getMessage()
            ];

            $history = TaskHistory::create($data);

            TaskStatus::upsert([
                'task_id' => $dto->getTaskId(),
                'student_id' => $user->id,
                'teacher_id' => $task->user_id,
                'status' => \App\Modules\Task\Enums\TaskStatus::WaitingForReview
            ],
                uniqueBy: ['task_id', 'user_id'],
                update: ['status']
            );


            return $history;
        });
    }


    public function getStudentTaskCurrentStatus(int $task_id, int $student_id)
    {
        return TaskStatus::from('task_statuses as ts')
            ->where(['ts.task_id' => $task_id, 'ts.student_id' => $student_id])
            ->select([
                'ts.*',
                'ts.status as review_status'
            ])
            ->first();
    }


    public static function getProgressByTasksForUser(int $student_id): array
    {
        $sql = "
            select
                ts.student_id,
                tasks.id as task_id,
                l.title as lecture_title,
                -- concat(u.lastname, ' ', u.firstname) as fio,
                tasks.title as task_title,
                ts.status as review_status,
                ts.created_at
            from tasks
            left join task_statuses ts on tasks.id = ts.task_id and ts.student_id = ?
            inner join lectures l on tasks.lecture_id = l.id
            where tasks.is_visible = 1
        ";

        return DB::select($sql, [$student_id]);
    }

    public function getTasksSentByStudents(int $lecture_id, int $task_id): Builder|EloquentBuilder
    {
        $query = $this->tasksQueryBuilder->getStudentsTasks();

        if ($lecture_id > 0) {
            $query->where(['t.lecture_id' => DB::raw($lecture_id)]);
        }

        if ($task_id > 0) {
            $query->where(['tss.task_id' => DB::raw($task_id)]);
        }

        return $query;
    }

    /**
     * @param int $task_id
     * @param int $user_id
     * @param array<string> $relations - with
     * @return array
     */
    public function getStudentTask(int $task_id, int $user_id, array $relations = []): array
    {
        $query = $this->tasksQueryBuilder->getStudentTask($task_id, $user_id, $relations);

        $task = $query->first();

        return [...$task->toArray(), 'comments' => $task->history($user_id)->get()->toArray()];
    }

    /**
     * Returns tasks that belong to student
     *
     * @param int $student_id
     * @param int $lecture_id
     * @return Builder|EloquentBuilder
     */
    public function getStudentTasks(int $student_id, int $lecture_id = 0): Builder|EloquentBuilder
    {
        $query = $this->tasksQueryBuilder->getStudentsTasks();

        $query->where(['tss.student_id' => $student_id]);

        if (0 !== $lecture_id) {
            $query->where(['t.lecture_id' => $lecture_id]);
        }

        return $query;
    }

    public function studentsThatSentReply(bool $all_students = false): array
    {
        if ($all_students) {
            $sql = "
                select u.id, u.lastname, u.firstname
                from users u
                where u.role != 'admin' and u.status = 'activated'
            ";
        } else {

            $sql = "
            select u.id, u.lastname, u.firstname
            from users u
            where u.id in
                  (select distinct sender_id from comments where commentable_id = ? and commentable_type = ?)
              and u.role != ?
        ";
        }

        $sql = "
            select
                u.id,
                u.lastname,
                u.firstname
            from
                task_histories th
                left join users u on th.sender_id = u.id
            where
                th.task_id = ?
        ";

        return DB::select($sql, [$this->id]);
        //return DB::select($sql, $all_students ? [] : [$this->id, 'App\\Models\\Comment', 'admin']);
    }

    public function getTasksProgress(): array
    {
        $sql = "
            select s.student_id
                 , s.name
                 , s.accepted
                 , s.waiting_execution
                 , s.rejected
                 , s.returned
                 , (s.waiting_execution + s.returned + s.rejected) as uncompleted
            -- , round(((s.accepted - s.rejected) / s.common) * 100, 2) as progress
            from (select c.student_id
                       , c.name
                       , count(if(task_status = 'accepted', 1, null))          as accepted
                       , count(if(task_status = 'waiting_execution', 1, null)) as waiting_execution
                       , count(if(task_status = 'rejected', 1, null))          as rejected
                       , count(if(task_status = 'returned', 1, null))          as returned
                  from (select u.id                                            as student_id,
                               concat(u.lastname, ' ', u.firstname)            as name,
                               u.department,
                               if(tr.status is null, 'waiting_execution', tr.status) task_status
                        from users u
                                 cross join (select * from tasks where tasks.is_visible = 1) ut
                                 left join task_histories tr on u.id = tr.sender_id and ut.id = tr.task_id
                        where
                            u.status  = 'activated' and
                            u.role   != 'admin'
                        order by u.department, u.lastname) c
                  group by c.student_id, c.name, c.department
                  order by c.department, c.name) s
        ";

        return DB::select($sql);
    }

    public function getTaskHistoryCurrentStatus(int $task_id, int $student_id): Model|EloquentBuilder|Builder|null
    {
        return $this->tasksQueryBuilder->getStudentTaskHistory($task_id, $student_id)->first();
    }

    public function getStudentTaskHistory(int $task_id, int $student_id)
    {
        return $this->tasksQueryBuilder->getStudentTaskHistory($task_id, $student_id)->get();
    }

    public function getStudentTasksWithStatuses(int $student_id, int $lecture_id = 0)
    {
        $query = $this->tasksQueryBuilder->getStudentTasksWithStatuses($student_id);

        if(0 !== $lecture_id) {
            $query->where(['t.lecture_id' => $lecture_id]);
        }

        return $query;
    }

    public function getListOfTasks(array $fields, int $lecture_id)
    {
        return $this->tasksQueryBuilder->getListOfTasks($fields, $lecture_id);
    }


}
