<?php

// AuthController.php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;

class RegisterController extends Controller
{
public function register(Request $request)
{
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|unique:users,email|email',
            'password' => 'required'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        if(!$token = auth()->attempt($request->only(['email', 'password'])))
        {
            return abort(401);
        }

        return (new UserResource($user))
            ->additional([
               'meta' => [
                 'token' => $token
             ]
        ]);
}
}
