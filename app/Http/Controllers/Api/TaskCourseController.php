<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TaskCourse;
use App\Http\Requests\StoreTaskCourseRequest;
use App\Http\Requests\UpdateTaskCourseRequest;
use Carbon\Carbon;

class TaskCourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */


     public function index($id)
     {
         $taskCourse = TaskCourse::where('course_id', $id)->get();

         $taskCourse->each(function($task) {
             $task->deadline_format = Carbon::parse($task->deadline)->locale('id')->diffForHumans();
         });

         return response()->json([
             'status' => 'success',
             'data' => $taskCourse->map(function($task) {
                 return [
                     'id' => $task->id,
                     'name' => $task->name,
                     'description' => $task->description,
                     'deadline' => $task->deadline,
                     'deadline_format' => $task->deadline_format,
                     'type' => $task->type,
                     'course_id' => $task->course_id,
                 ];
             })
         ], 200);
     }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskCourseRequest $request)
    {
        $taskCourse = TaskCourse::create($request->all());
        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil menambah tugas',
            'data' => $taskCourse
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(TaskCourse $taskCourse)
    {
        return view('pages.admin.task.detailTask.detailTask', compact('taskCourse'));
    }
    public function teacher(TaskCourse $taskCourse)
    {
        return view('pages.teacher.task.detailTask.detailTask', compact('taskCourse'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TaskCourse $taskCourse)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskCourseRequest $request, TaskCourse $taskCourse)
    {
        $taskCourse->update($request->all());
        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil mengedit tugas',
            'data' => $taskCourse
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TaskCourse $taskCourse)
    {
        $taskCourse->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil menghapus tugas',
            'data' => $taskCourse
        ], 200);
    }

    public function allTasks()
    {
        $tasks = TaskCourse::with('course')->orderBy('deadline', 'desc')->get();
        return response()->json([
            'status' => 'success',
            'data' => $tasks->map(function ($t) {
                return [
                    'id' => $t->id,
                    'name' => $t->name,
                    'description' => $t->description,
                    'deadline' => $t->deadline,
                    'deadline_format' => \Carbon\Carbon::parse($t->deadline)->locale('id')->diffForHumans(),
                    'course_name' => $t->course ? $t->course->name : 'Umum',
                ];
            })
        ]);
    }
}
