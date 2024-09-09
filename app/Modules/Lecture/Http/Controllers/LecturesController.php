<?php

namespace App\Modules\Lecture\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\File\Models\File;
use App\Modules\Lecture\Http\Requests\Lecture\CreateLectureRequest;
use App\Modules\Lecture\Http\Requests\Lecture\LecturesIndexRequest;
use App\Modules\Lecture\Models\Lecture;
use App\Modules\Task\Models\Review;
use App\Modules\Task\Models\UserTask;
use Illuminate\Database\Eloquent\Collection;

class LecturesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(LecturesIndexRequest $request): Collection|array
    {
        $fields = $request->validated('fields', ['*']);
        return Lecture::get($fields);
    }


    public function getTasks(Lecture $lecture)
    {
        return UserTask::where(['lecture_id' => $lecture->id])->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateLectureRequest $request)
    {
        $params = $request->only(['title', 'excerpt', 'content', 'is_visible']);
        $ids = json_decode($request->get('files'));

        //return ['id' => auth()->user()];

        $params['user_id'] = auth()->user()->id;
        $lecture = Lecture::create($params);

        File::whereIn('id', $ids)->update([
            'fileable_type' => 'lecture',
            'fileable_id' => $lecture->id
        ]);

        return $lecture;
    }

    /**
     * Display the specified resource.
     */
    public function show(Lecture $lecture)
    {
        $files = File::where([
            'fileable_type' => 'lecture',
            'fileable_id' => $lecture->id
        ])->get();

        $tasks = UserTask::where(['lecture_id' => $lecture->id])->get();

        return [...$lecture->toArray(), 'files' => $files, 'tasks' => $tasks];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateLectureRequest $request, Lecture $lecture)
    {
        return $lecture;

        $params = $request->only(['title', 'excerpt', 'content', 'is_visible']);
        $ids = json_decode($request->get('files'));

        $params['user_id'] = 10;
        $lecture = Lecture::update($params);

        File::whereIn('id', $ids)->update([
            'fileable_type' => 'lecture',
            'fileable_id' => $lecture->id
        ]);

        return $lecture;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lecture $lecture)
    {
        Review::where(['lecture_id' => $lecture->id])->delete();
        return ['result' => $lecture->delete(), 'id' => $lecture->id];
    }
}
