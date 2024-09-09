<?php

namespace App\Modules\Task\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\File\Models\File;
use App\Modules\Task\Models\Reply;
use Illuminate\Http\Request;

class TaskReplyController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Reply::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $params = $request->only(['description', 'task_id', 'lecture_id']);
        $ids = json_decode($request->get('files'));


        $params['user_id'] = auth()->user()->id;
        $obj = Reply::create($params);


        File::whereIn('id', $ids)->update([
            'fileable_type' => Reply::class,
            'fileable_id' => $obj->id
        ]);

        return $obj;
    }


    public function get(int $lecture_id, int $task_id)
    {
        return Reply::where([
            'user_id' => auth()->user()->id,
            'lecture_id' => $lecture_id,
            'task_id' => $task_id
        ])->first();
    }

    /**
     * Display the specified resource.
     */
    public function show(Reply $reply)
    {
        return $reply;
    }
}
