<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('auth.profile.edit', [
            'user' => Auth::user(),
        ]);
    }

    public function update(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $hasChanges =
        $request->name  !== $user->name  ||
        $request->email !== $user->email ||
        $request->has('image')           ||
        $request->filled('new_password');

        $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'new_password' => ['nullable', 'string' , Password::default()],
            'current_password' => ['nullable' , Rule::requiredIf($hasChanges) , 'string' , Rule::currentPassword()],
            'image' => ['nullable' , 'image' , 'max:5120'],
            ]);

        if($request->image instanceof UploadedFile){
            Storage::disk('public')->delete($user->image_user_path);
            $image_path = $request->image->store('users' , 'public');
            $user->image_user_path = $image_path;
            }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->filled('new_password') ? $request->new_password : $user->password,
            'image_user_path' =>  $user->image_user_path
            ]);

        return to_route('profile.edit')->with('success', 'profile updated!');
    }
}
