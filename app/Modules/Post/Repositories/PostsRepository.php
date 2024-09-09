<?php

namespace App\Modules\Post\Repositories;

use App\Common\Enums\ViewedType;
use App\Modules\Post\Models\Post;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;

class PostsRepository
{
    public function getPostsWithUserViewsCount(int $user_id)
    {
        return Post::from('posts as p')
            ->leftJoin('views_count as vc', function (JoinClause $join) use ($user_id) {
                $join->on('vc.viewed_id', '=', 'p.id')
                    ->where('vc.user_id', '=', $user_id);
            })
            ->select([
                'p.id',
                'p.title',
                'p.created_at',
                DB::raw('
                CASE
                   WHEN vc.count IS NULL THEN 0
                   WHEN vc.count > 0 THEN vc.count
                END AS views_count')
            ])
            ->orderBy('p.created_at', 'desc');
    }

    public function unreadPostsCount(int $user_id)
    {
        $sql = "
            select count(p.id) as unread_counts
            from posts p
            where not exists(
                select 1
                from views_count vc
                where vc.viewed_id = p.id and vc.viewed_type = ? and vc.user_id = ?
            )
        ";

        return DB::selectOne($sql, [ViewedType::Post->value, $user_id]);
    }
}
