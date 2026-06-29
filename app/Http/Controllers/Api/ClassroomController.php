<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Http\Requests\StoreClassroomRequest;
use App\Http\Requests\UpdateClassroomRequest;
use App\Models\StudentClassroomRelation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;


class ClassroomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $classrooms = Classroom::where('status', 'accept')
            ->where('statusClass', 'public')
            ->whereColumn('total_user', '<', 'limit')
            ->get()
            ->map(function ($classroom) {
                return [
                    'id' => $classroom->id,
                    'name' => $classroom->name,
                    'codeClass' => $classroom->codeClass,
                    'limit' => $classroom->limit,
                    'total_user' => $classroom->total_user,
                    'description' => $classroom->description,
                    'thumbnail' => $classroom->thumbnail,
                    'status' => $classroom->status,
                    'statusClass' => $classroom->statusClass,
                    'user_name' => $classroom->user ? $classroom->user->name : null,
                    'user_image' => $classroom->user ? $classroom->user->image : null,
                    'created_at' => $classroom->created_at,
                    'updated_at' => $classroom->updated_at,
                ];
            });
        return response()->json([
            'message' => 'success',
            'data' => $classrooms
        ], 200);
    }

    public function classroomAdmin()
    {
        $classrooms = Classroom::all()
            ->map(function ($classroom) {
                return [
                    'id' => $classroom->id,
                    'name' => $classroom->name,
                    'codeClass' => $classroom->codeClass,
                    'limit' => $classroom->limit,
                    'status' => $classroom->status,
                    'total_user' => $classroom->total_user,
                    'description' => $classroom->description,
                    'thumbnail' => $classroom->thumbnail,
                    'status' => $classroom->status,
                    'statusClass' => $classroom->statusClass,
                    'user_name' => $classroom->user ? $classroom->user->name : null,
                    'user_image' => $classroom->user ? $classroom->user->image : null,
                    'created_at' => $classroom->created_at,
                    'updated_at' => $classroom->updated_at,
                ];
            });
        return response()->json([
            'message' => 'success',
            'data' => $classrooms
        ], 200);
    }

    public function classroomTeacher($id)
    {
        $classroom = Classroom::where('user_id', $id)->with('semester')->get();
        return response()->json([
            'status' => 'success',
            'data' => $classroom
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
    public function store(StoreClassroomRequest $request)
    {
        $classroom = Classroom::create([
            'name' => $request->name,
            'user_id' => $request->user_id,
            'codeClass' => $request->codeClass,
            'limit' => $request->limit,
            'statusClass' => $request->statusClass,
            'description' => $request->description,
            'thumbnail' => $request->file('thumbnail')->store('thumbnails'),
            'semester_id' => $request->semester_id,
            'sks' => $request->sks,
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $classroom
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Classroom $classroom)
    {
        return response()->json([
            'message' => 'success',
            'data' => $classroom
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Classroom $classroom)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(UpdateClassroomRequest $request, Classroom $classroom)
    {
        if ($request->hasFile('thumbnail')) {
            if ($classroom->thumbnail && Storage::exists('public/storage/' . $classroom->thumbnail)) {
                Storage::delete('public/storage/' . $classroom->thumbnail);
            }

            $thumbnailPath = $request->file('thumbnail')->store('classroom-thumbnails', 'public');
        } else {
            $thumbnailPath = $classroom->thumbnail;
        }


        $classroom->update([
            'name' => $request->name,
            'user_id' => $request->user_id,
            'codeClass' => $request->codeClass,
            'limit' => $request->limit,
            'statusClass' => $request->statusClass,
            'description' => $request->description,
            'thumbnail' => $thumbnailPath,
            'semester_id' => $request->semester_id,
            'sks' => $request->sks,
        ]);

        return response()->json([
            'message' => 'Kelas berhasil diupdate!',
            'data' => $classroom
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Classroom $classroom)
    {
        if ($classroom->thumbnail) {
            Storage::disk('public')->delete($classroom->thumbnail);
        }

        $classroom->delete();

        return response()->json([
            'message' => 'success',
            'data' => null
        ], 200);
    }

    public function studentCourse($id)
    {
        $students = StudentClassroomRelation::where('classroom_id', $id)->where('status', 'accept')
            ->with('user')
            ->get();

        $formattedData = $students->map(function ($relation) {
            $user = $relation->user;
            return [
                'id_relation' => $relation->id,
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'profile' => $user->image,
            ];
        });

        return response()->json([
            'status' => true,
            'data' => $formattedData,
        ], 200);
    }

    public function classroomStudent($id)
    {
        $classrooms = Classroom::where('status', 'accept')
            ->where('statusClass', 'public')
            ->whereDoesntHave('studentClassroomRelations', function ($query) use ($id) {
                $query->where('user_id', $id)
                    ->where('status', '!=', 'pending');
            })
            ->whereColumn('total_user', '<', 'limit')
            ->get()
            ->map(function ($classroom) {
                return [
                    'id' => $classroom->id,
                    'name' => $classroom->name,
                    'codeClass' => $classroom->codeClass,
                    'limit' => $classroom->limit,
                    'total_user' => $classroom->total_user,
                    'description' => $classroom->description,
                    'thumbnail' => $classroom->thumbnail,
                    'status' => $classroom->status,
                    'statusClass' => $classroom->statusClass,
                    'user_id' => $classroom->user_id,
                    'user_name' => $classroom->user ? $classroom->user->name : null,
                    'user_image' => $classroom->user ? $classroom->user->image : null,
                    'created_at' => $classroom->created_at,
                    'updated_at' => $classroom->updated_at,
                ];
            });

        return response()->json([
            'message' => 'success',
            'data' => $classrooms
        ], 200);
    }

    public function detailClassroom($id)
    {
            return view('pages.admin.classroom.detail' , compact('id'));
    }

    public function detailCourse($id){

        return view('pages.admin.classroom.detailCourse', compact('id'));
    }
}
