<?php

namespace App\Modules\Account\Repositories;

use App\Modules\Account\Dtos\UserActivityLogsFilterDto;
use App\Modules\Account\Models\UserActivityLog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class UserActionsLoggingRepository
{
    public function get(int $user_id): LengthAwarePaginator
    {
        return DB::table('user_activity_logs', 'logs')
            ->leftJoin('users as u', 'u.id', '=', 'logs.user_id')
            ->where('logs.user_id', '=', $user_id)
            ->select([
                DB::raw('concat(u.lastname, \' \', u.firstname) as username'),
                'logs.*'
            ])
            ->orderBy('logs.created_at', 'desc')
            ->paginate(30);
    }


    public function getUniqueIPs()
    {
        $sql = "
            select distinct logs.client_ip
            from user_activity_logs logs
            order by INET_ATON(logs.client_ip)
        ";

        return DB::select($sql);
    }

    public function getUniqueUrls()
    {
        $sql = "
            select distinct logs.url
            from user_activity_logs logs
            order by 1
        ";

        return DB::select($sql);
    }

    public function getAllLogs(UserActivityLogsFilterDto $filter = null): LengthAwarePaginator|array
    {
        $query = DB::table('user_activity_logs', 'logs')
            ->leftJoin('users as u', 'u.id', '=', 'logs.user_id')
            ->where('role', '!=', 'admin')
            ->orderBy('logs.created_at', 'desc')
            ->select([
                'logs.user_id',
                DB::raw('(concat(u.lastname, \' \', u.firstname)) as name'),
                'logs.action_name',
                'logs.os',
                'logs.browser',
                'logs.client_ip',
                'logs.url',
                'logs.referer',
                'logs.created_at'
            ]);


        if(null !== $filter)
        {
            if($filter->users) {
                $query->whereIn('u.id', $filter->users);
            }

            if($filter->actions) {
                $query->whereIn('logs.action_name', $filter->actions);
            }

            if($filter->ips) {
                $query->whereIn('logs.client_ip', $filter->ips);
            }

            if($filter->urls) {
                $query->whereIn('logs.url', $filter->urls);
            }

            if($filter->start_dt) {
                $start = Carbon::createFromTimestamp($filter->start_dt / 1000); // Todo: validation to dto props
                $query->where('logs.created_at', '>=', $start);
            }

            if($filter->end_dt) {
                $end = Carbon::createFromTimestamp($filter->end_dt / 1000);
                $query->where('logs.created_at', '<=', $end);
            }
        }

        return $query->paginate(30);
    }


    public function getMosActiveUsers(): array
    {
        return DB::select("
            select
                u.id as user_id,
                concat(u.lastname, ' ', u.firstname) as username,
                count(logs.id) as activity
            from users u
            left join user_activity_logs logs on logs.user_id = u.id
            where u.status = 'activated' and u.role != 'admin'
            group by u.id, username
            order by activity desc
        ");
    }

    public function getUsersIpInfo()
    {
        throw new \Exception('Not implemented properly');

        $sql = "
            select
                ips.uid,
                concat(u.lastname, ' ', u.firstname) as username,
                ips.ip
            from (select distinct l.user_id   as uid,
                                  l.client_ip as ip
                  from user_activity_logs l
                  order by l.client_ip) ips
            left join users u on u.id = ips.uid
            order by u.id
        ";

        $rows = collect(DB::select($sql));
        $ips = $rows->pluck('ip');

        // return $ips;

        $headers = [
            "Content-Type: application/json",
            sprintf("Content-Length: %d", strlen(json_encode($ips)))
        ];

        $context = stream_context_create([
            'http' => [
                'header' => join("\r\n", $headers),
                'method' => 'POST',
                'content' => $ips,
                'timeout' => rand(1, 2),
            ]
        ]);

        // $getIpInfo = fn($ip) => json_decode(file_get_contents("https://ipinfo.io/$ip?token=d1438d49d83d57", false, $context));

        return json_decode(file_get_contents("https://ipinfo.io/batch?token=d1438d49d83d57", false, $context));

        /*return $rows->map(static function ($row) use ($getIpInfo) {
            $row->ip = $getIpInfo($row->ip);
            //sleep(1);
            return $row;
        });*/
    }

    public function save(array $data): UserActivityLog
    {
        return UserActivityLog::create($data);
    }
}
