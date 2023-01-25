<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Utils\QS;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();
        $search = $request->query("search");

        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->whereRaw("lower(\"name\") like '%$search%'");
                $query->orWhereRaw("lower(\"username\") like '%$search%'");
            });
        }

        $pagination = QS::pagination($request->query());

        return response()->json($query->paginate($pagination['amount'], '*', 'page', $pagination['page']));
    }

    public function show(Request $request, $id)
    {
        if ($id === '@me') {
            return $request->user()->toJson();
        }

        return User::findOrFail($id)->toJson();
    }

    public function update(Request $request)
    {
        $user = $request->user();
        $data = $request->validate([
            'name' => 'max:255',
            'username' => 'unique:users|min:3|max:255',
            'email' => 'unique:users|max:255|email',
            'password' => 'min:8|max:180',
        ]);

        if ($data['password']) {
            $data = array_merge($data, $request->validate([
                'confirmation' => 'required|min:8|max:180',
            ]));

            if (!Hash::check($data['confirmation'], $user->password)) {
                return response('Wrong password', Response::HTTP_SERVICE_UNAVAILABLE);
            }

        }

        $user->fill($data)->save();

        return $user->toJson();
    }

    public function destroy(Request $request)
    {
        $user = $request->user();
        $data = $request->validate([
            'confirmation' => 'required|min:8|max:180',
        ]);

        if (!Hash::check($data['confirmation'], $user->password)) {
            return response('Wrong password', Response::HTTP_SERVICE_UNAVAILABLE);
        }

        $user->delete();

        return response('', Response::HTTP_NO_CONTENT);
    }
}
