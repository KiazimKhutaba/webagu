<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Modules\File\Models\File;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property mixed $password
 * @property string $firstname
 * @property string $lastname
 * @property string $department
 * @property string $phone
 * @property string|null $role
 * @property string|null $status
 * @property string|null $avatar
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Modules\File\Models\File> $files
 * @property-read int|null $files_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDepartment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFirstname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'phone',
        'password',
        'department',
        'avatar'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }


    public function getJWTCustomClaims(): array
    {
        return [
            'user_id' => $this->id,
            'role' => $this->role,
            'status' => $this->status
        ];
    }


    public function files(): HasMany
    {
        return $this->hasMany(File::class);
    }


    public function students()
    {
        return $this->where(['role' => 'user']);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isActivated()
    {
        return $this->status === 'activated';
    }

    /**
     * @throws \Throwable
     *
     * [mysqld]
     * group_concat_max_len = 1000000
     *
     * @see https://stackoverflow.com/questions/2567000/mysql-and-group-concat-maximum-length
     */
    public static function getTaskStatusesForAllStudents(int $task_id = 0, int $lecture_id = 0, string $department = 'all', int $task_type = -1): array|string
    {
        // @see https://www.databasestar.com/mysql-pivot/
        return DB::transaction(function () use ($task_id, $lecture_id, $department, $task_type) {

            $bindings = [];
            if ($task_id > 0) {
                if ($lecture_id > 0) {

                    $bindings = [$task_id, $lecture_id];

                    $crossJoinClause = "
                        cross join (
                            select
                                  tasks.id
                                , tasks.title
                                , tasks.is_seminar
                                , tasks.lecture_id
                            from
                                tasks
                            where
                                tasks.is_visible = 1 and
                                tasks.id = ? and
                                tasks.lecture_id = ?
                        )
                    ";

                } else {
                    $bindings = [$task_id];
                    $crossJoinClause = "
                        cross join (
                            select
                                  tasks.id
                                , tasks.title
                                , tasks.is_seminar
                                , tasks.lecture_id
                            from
                                tasks
                            where
                                tasks.is_visible = 1 and
                                tasks.id = ?
                        )";
                }
            } else {
                if ($lecture_id > 0) {
                    $bindings = [$lecture_id];
                    $crossJoinClause = "
                        cross join (
                            select
                                  tasks.id
                                , tasks.title
                                , tasks.is_seminar
                                , tasks.lecture_id
                            from
                                tasks
                            where
                                tasks.is_visible = 1 and
                                tasks.lecture_id = ?
                        )";
                } else {
                    $crossJoinClause = "cross join (select * from tasks where tasks.is_visible = 1)";
                }
            }

            if ("all" === $department) {
                $departmentClause = "";
            } else {
                $departmentClause = " and u.department = ? ";
                $bindings = [...$bindings, $department];
            }


            if (-1 === $task_type) {
                $taskTypeClause = "";
            } else {
                $taskTypeClause = " and ut.is_seminar = ? ";
                $bindings = [...$bindings, $task_type];
            }

            DB::statement("
                create temporary table if not exists students_tasks_01
                select u.id                                 as                             student_id,
                       concat(u.lastname, ' ', u.firstname) as                             name,
                       ut.id                                as                             task_id,
                       ut.title                             as                             task_title,
                       if(tr.status is null, 'waiting_execution', tr.status)               task_status,
                       ut.lecture_id
                from users u
                         -- cross join (select tasks.id, tasks.title from tasks where tasks.id = ?) as  ut
                         -- cross join tasks ut
                         $crossJoinClause as ut
                         left join task_statuses tr on u.id = tr.student_id and ut.id = tr.task_id
                where
                    u.status = 'activated'
                    and u.role != 'admin'
                    $departmentClause
                    $taskTypeClause
                order by
                    u.department, u.lastname
            ", $bindings);

            $hasRows = \DB::table('students_tasks_01')->count();

            if (!$hasRows) {
                DB::rollBack();
                return [];
            }

            DB::statement("SET @sql = NULL");
            DB::statement("SET SESSION group_concat_max_len = 1000000");

            DB::statement("
                SELECT GROUP_CONCAT(DISTINCT CONCAT(
                        'MAX(
                            CASE WHEN task_id = ', task_id, '
                                THEN JSON_OBJECT(\"lid\", lecture_id, \"task_id\", task_id, \"task_status\", task_status) ELSE char(1)
                            END
                        ) AS ', quote(task_id))
                )
                INTO @sql
                FROM students_tasks_01
                WHERE task_id IS NOT NULL
            ");

            DB::statement("
                SET @sql = CONCAT('SELECT student_id, name, ', @sql, ' FROM students_tasks_01 GROUP BY student_id, name')
            ");

            // return DB::select("SELECT @sql");

            DB::statement("PREPARE stmt FROM @sql");
            $rs = DB::select("EXECUTE stmt");
            DB::statement("DEALLOCATE PREPARE stmt");
            DB::statement("DROP TEMPORARY TABLE students_tasks_01");

            return array_map(function ($obj) {
                foreach ($obj as $key => $value) {
                    if (is_numeric($key)) $obj->$key = json_decode($value);
                }
                return $obj;
            }, $rs);
        });
    }
}
