<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function teacher()
    {
        $user = User::where('role', 'teacher')->where('status', 'pending')->get();
        return response()->json([
            'status' => 'success',
            'data' => $user
        ], 200);
    }

    public function teacherAll()
    {
        $user = User::where('role', 'teacher')->where('status' , 'accept')->get();
        return response()->json([
            'status' => 'success',
            'data' => $user
        ], 200);
    }

    public function show($id)
    {
        $user = User::where('id', $id)->first();
        return view('pages.admin.teacher.detail' , compact('user'));
    }


    public function destroy(User $user)
    {
        if ($user->image) {
            Storage::delete($user->image);
        }

        $user->delete();

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $user->id,
                'message' => 'User deleted successfully.'
            ]
        ], 200);
    }

    public function profileUpdate(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        if ($request->hasFile('image')) {
            if ($user->image && Storage::disk('public')->exists($user->image)) {
                Storage::disk('public')->delete($user->image);
            }
            $data['image'] = $request->file('image')->store('profiles', 'public');
        }

        $user->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Profil berhasil diperbarui!',
            'data' => $user
        ], 200);
    }

    public function studentAll()
    {
        $users = User::where('role', 'student')->get();
        return response()->json([
            'status' => 'success',
            'data' => $users
        ], 200);
    }
}
