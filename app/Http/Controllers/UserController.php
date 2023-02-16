<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'status' => 'success',
            'result' => User::get(),

        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, User $user)
    {
        $userId = $request->input();
        return response()->json([
            'status' => 'true',
            'result' => User::find($userId),
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        // if (Auth::guard('userAuth')->check()) {
        //     $user->update($request->all());
        //     return response()->json([
        //         'status' => 'success',
        //         'message' => 'User updated successfully!',
        //         'user' => $user,
        //     ], 200);
        // } else {
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => 'Login please!',
        //     ], 500);
        // }
        $user->update($request->all());
        return response()->json([
            'status' => 'success',
            'message' => 'User updated successfully!',
            'user' => $user,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'User deleted successfully!',
        ], 200);
    }

    public function backend(Request $request)
    {
        $query = User::query();

        if ($search = $request->input(key: 'search')) {
            $query->whereRaw(sql: "name LIKE '%" . $search . "%'")
                ->orWhereRaw(sql: "institution LIKE '%" . $search . "%'");
        }

        $perPage = 10;
        $page = $request->input(key: 'page', default: 1);
        $total = $query->count();

        $result = $query->offset(value: ($page - 1) * $perPage)->limit($perPage)->get();

        return response()->json(
            [
                'status' => 'success',
                'result' => $result,
                'total' => $total,
                'page' => $page,
                'last_page' => ceil($total / $perPage),
            ],
            200
        );
    }
}
