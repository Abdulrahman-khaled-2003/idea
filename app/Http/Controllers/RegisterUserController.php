<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class RegisterUserController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'min:3', 'max:255', 'string'],
            'email' => ['required', 'string', 'email', Rule::unique('users', 'email'), 'max:255'],
            'password' => ['required', 'confirmed', 'min:8 , max:255', 'string'],
            'image' => ['required', 'image' , 'max:5120']
            ]);

            $data = collect($validated)->only('name' , 'email' , 'password')->toArray();
            if($request->image ?? false){
                $data['image_user_path'] = $request->image->store('users' , 'public');
            }

        $user = User::create($data);

        Auth::login($user);

        return to_route('idea.index')->with('success', 'Registration complete!');
    }
}
