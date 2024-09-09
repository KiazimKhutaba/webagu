<?php

namespace App\Modules\Post\Repositories;

use App\Common\Enums\ViewedType;
use App\Modules\Post\Models\ViewsCount;
use DB;
use Illuminate\Database\Eloquent\Collection;

class ViewsCounterRepository
{
    /**
     * @param int $user_id - User who views that object
     * @param int $object_id - object id, that was viewed by user
     * @param ViewedType $viewedType - object type, that was viewed
     * @return void
     */
    public function updateCounter(int $user_id, int $object_id, ViewedType $viewedType): void
    {
        $conditions = [
            'user_id' => $user_id,
            'viewed_id' => $object_id,
            'viewed_type' => $viewedType->value,
        ];

        $row = ViewsCount::where($conditions);

        if($row->exists()) {
            $row->increment('count');
        }
        else {
            $conditions['count'] = 1;
            ViewsCount::create($conditions);
        }
    }


    /**
     * Returns counter for specific object
     *
     * @param int $object_id
     * @param string $object_type
     * @return Collection|array
     */
    public function getCount(int $object_id, ViewedType $viewedType): Collection|array
    {
        $conditions = [
            'viewed_id' => $object_id,
            'viewed_type' => $viewedType->value
        ];

        return ViewsCount::where($conditions)
            ->leftJoin('users as u', 'views_count.user_id', '=', 'u.id')
            ->get([
                'u.id',
                DB::raw('CONCAT(u.lastname, \' \', u.firstname) as username'),
                'views_count.count as count',
                'views_count.created_at as first_viewed_at',
                'views_count.updated_at as last_viewed_at',
            ]);
    }
}
