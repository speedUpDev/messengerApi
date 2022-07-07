<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function save(Request $request)
    {
        if (Auth::check()) {
            return response(['message' => 'Вы уже авторизованы']);
        }
        $validateFields = $request->validate([
            'name' => 'required|min:2|max:15',
            'email' => 'required|email',
            'password' => 'min:6|required_with:password_conf|same:password_conf',
            'password_conf' => 'min:6'
        ]);
        if (User::where('email', $validateFields['email'])->exists()) {
            return response(['message' => 'Такой пользователь уже зарегестрирован']);
        }
        $user = User::create([
            'name' => $validateFields['name'],
            'email' => $validateFields['email'],
            'password' => bcrypt($validateFields['password'])
        ]);
        if ($user) {
            return response(['message' => "You've been registered", 'user'=>$user]);
        }
        return response(['message' => 'Ошибка при создании пользователя']);
    }
}
