<?php

namespace App\Modules\Post\Http\Controllers;

use App\Common\Enums\ViewedType;
use App\Http\Controllers\Controller;
use App\Modules\Authentication\Services\AuthenticationService;
use App\Modules\Post\Http\Requests\Post\PostCreateRequest;
use App\Modules\Post\Http\Requests\Post\PostIndexRequest;
use App\Modules\Post\Models\Post;
use App\Modules\Post\Repositories\PostsRepository;
use App\Modules\Post\Repositories\ViewsCounterRepository;
use Illuminate\Database\Query\JoinClause;


class PostController extends Controller
{
    public function __construct(
        private readonly AuthenticationService $authenticationService,
        private readonly PostsRepository $postsRepository
    )
    {
        $this->middleware('auth:api');
    }


    /**
     * Display a listing of the resource.
     */
    public function index(PostIndexRequest $request)
    {
        $user = $this->authenticationService->getAuthenticatedUserX(auth());
        $fields = $request->validated('fields', ['*']);

        return $this->postsRepository->getPostsWithUserViewsCount($user->id)->get($fields);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostCreateRequest $request)
    {
        $post = $request->validated();
        $post['user_id'] = auth()?->id();
        return Post::create($post);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post, ViewsCounterRepository $counterRepository)
    {
        $counterRepository->updateCounter(auth()?->id(), $post->id, ViewedType::Post);

        return [
            'post' => $post,
            'views_history' => auth()?->user()->isAdmin() ? $counterRepository->getCount($post->id, ViewedType::Post) : []
        ];
    }


    public function unreadPostsCount(PostsRepository $postsRepository)
    {
        return $postsRepository->unreadPostsCount(auth()?->id());
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        return $post->delete();
    }
}
