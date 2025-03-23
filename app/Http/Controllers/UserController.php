<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function fetchUsers(Request $request)
    {
        $users = User::where('id','!=',Auth::id());

        if ($request->has('search')) {
            $users = $users->where('name','like', '%' . $request->get('search') . '%');
        }

        $users = $users->orderBy('id','desc')->paginate(10);

        return response()->json(UserResource::collection($users), 200);
    }

    public function switchUserRole($id)
    {
        if ($id == Auth::id()) {
            return response()->json(['message' => 'You cannot change your own role'], 403);
        }

        $user = User::where('id', $id)
            ->where('role', UserRole::AUTHOR)
            ->first();


        if (!$user) {
            $response['message'] = 'No user found';
            $statusCode = 404;
        } else {
            $user->role = UserRole::COLLABORATOR;
            $user->save();
            $response['data'] = new UserResource($user);
            $statusCode = 200;
        }
        return response()->json($response, $statusCode);
    }
}
