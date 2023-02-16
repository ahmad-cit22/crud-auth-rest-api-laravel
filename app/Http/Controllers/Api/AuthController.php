<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Validation\Rules\Password;
use Validator;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|regex:/^[a-zA-Z\s]+$/|min:3',
            'email' => 'required|email:rfc,dns|unique:users',
            'phone_number' => 'required',
            'password' => ['required', Password::min(8)->letters()->mixedCase()->symbols()->numbers(), 'confirmed'],
            'password_confirmation' => 'required',
            'institution' => 'required',
        ], [
            'name.required' => 'You must enter your name!',
            'name.regex' => "Name can't contain numbers!",
            'email.required' => 'You must enter your email!',
            'email.email' => 'Invalid email format!',
            'password.required' => 'You must enter your password!',
            'password.confirmed' => 'Please confirm your password correctly!',
            'password_confirmation.required' => 'You must confirm your password!',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors(),
            ], 400);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);

        $success['token'] = $user->createToken('MyApp')->plainTextToken;
        $success['name'] = $user->name;

        return response()->json([
            'status' => 'success',
            'message' => 'User registered successfully!',
            'data' => $success,
        ], 200);
    }


    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

            $user = Auth::user();

            $success['token'] = $user->createToken('MyApp')->plainTextToken;
            $success['name'] = $user->name;

            return response()->json([
                'status' => 'success',
                'message' => 'User logged in successfully!',
                'data' => $success,
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Wrong credentials given!',
            ], 400);
        }
    }
}
