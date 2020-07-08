<?php

namespace Mnemosine\Http\Controllers;

use Mnemosine\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Give user an option to change a known password for a new one
     *
     */
    public function changePassword()
    {
        return view('perfil.change-password');
    }

    /**
     * Update the password
     *
     */
    public function updatePassword(Request $request)
    {
        $user = User::findOrFail(Auth::user()->id);

        $this->validate($request, [
            'new_password' => 'required|min:8|different:current_password',
            'confirm_password' => 'required|min:8|same:new_password',
            'current_password' => [
                'required',
                'min:8',
                function ($attribute, $value, $fail) use ($user) {
                    if (!Hash::check($value, $user->password)) {
                        $fail('La contraseña actual es incorrecta');
                    }
                },
            ],
        ]);

        $user->password = bcrypt($request->get('new_password'));
        $user->save();

        flash()->success('La contraseña ha sido modificada');

        return redirect()->route('home');
    }
}
