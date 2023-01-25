<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|max:255',
            'username' => 'required|unique:users|min:3|max:255',
            'email' => 'required|unique:users|max:255|email',
            'password' => 'required|min:8|max:180',
        ]);

        $data['password'] = Hash::make($data['password']);

        $user = new User($data);

        $user->save();

        return $user;
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'uid' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('username', $data['uid'])->orWhere('email', $data['uid'])->first();

        if (!$user || !Hash::check($data['password'], $user->password))
            return response('Login invalid', Response::HTTP_SERVICE_UNAVAILABLE);

        return response('', Response::HTTP_NO_CONTENT)
            ->header('Authorization', 'Bearer: ' . $user->createToken('')->plainTextToken);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response('', Response::HTTP_NO_CONTENT);
    }

    public function refresh()
    {
        return response('', Response::HTTP_NO_CONTENT);
    }
}