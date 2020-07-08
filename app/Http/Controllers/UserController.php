<?php

namespace Mnemosine\Http\Controllers;

use Mnemosine\User;
use Mnemosine\Role;
use Mnemosine\Permission;
use Mnemosine\Authorizable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    use Authorizable;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $result = User::all();
        return view('usuarios.index', compact('result', 'request'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::pluck('name', 'id');
        return view('usuarios.new', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $customMessages = [
            'name.required' => 'El nombre es un campo requerido',
            'email.required'  => 'El correo electrónico es un campo requerido',
            'roles.required'  => 'El rol es un campo requerido',
            'password.required'  => 'La contraseña es un campo requerido',
            'name.min'  => 'El nombre debe tener al menos :min caracteres',
            'password.min'  => 'La contraseña debe tener al menos :min caracteres',
            'roles.min'  => 'Debe elegir al menos :min rol de usuario',
            'email.unique'  => 'Otro usuario usa ese correo, elija otro',
        ];
        $this->validate($request, [
            'name' => 'bail|required|min:2',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'roles' => 'required|min:1'
        ], $customMessages);

        $request->merge(['password' => bcrypt($request->get('password'))]);

        if ( $user = User::create($request->except('roles', 'permissions')) ) {
            $this->syncPermissions($request, $user);
            flash()->success('El usuario ha sido creado.');
        } else {
            flash()->error('No es posible crear al usuario.');
        }

        return redirect()->route('usuarios.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
       $roles = Role::pluck('name', 'id');
       $permissions = Permission::all('name', 'id');

       return view('usuarios.edit', compact('user', 'roles', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $customMessages = [
            'name.required' => 'El nombre es un campo requerido',
            'email.required'  => 'El correo electrónico es un campo requerido',
            'roles.required'  => 'El rol es un campo requerido',
            'name.min'  => 'El nombre debe tener al menos :min caracteres',
            'roles.min'  => 'Debe elegir al menos :min rol de usuario',
            'email.unique'  => 'Otro usuario usa ese correo, elija otro',
        ];
        $this->validate($request, [
            'name' => 'bail|required|min:2',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:8',
            'roles' => 'required|min:1'
        ], $customMessages);

        // Get the user
        $user = User::findOrFail($id);

        // Update user
        $user->fill($request->except('roles', 'permissions', 'password'));

        // check for password change
        if($request->get('password')) {
            $user->password = bcrypt($request->get('password'));
        }

        // Handle the user roles
        $this->syncPermissions($request, $user);

        $user->save();
        flash()->success('El usuario ha sido modificado.');
        return redirect()->route('usuarios.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ( Auth::user()->id == $id ) {
            flash()->warning('No se puede eliminar al usuario actualmente autenticado :(')->important();
            return redirect()->back();
        }

        if( User::findOrFail($id)->delete() ) {
            flash()->success('El usuario ha sido eliminado');
        } else {
            flash()->success('El usuario no se ha podido eliminar');
        }

        return redirect()->back();
    }

    private function syncPermissions(Request $request, $user)
    {
        // Get the submitted roles
        $roles = $request->get('roles', []);
        $permissions = $request->get('permissions', []);

        // Get the roles
        $roles = Role::find($roles);

        // check for current role changes
        if( ! $user->hasAllRoles( $roles ) ) {
            // reset all direct permissions for user
            $user->permissions()->sync([]);
        } else {
            // handle permissions
            $user->syncPermissions($permissions);
        }

        $user->syncRoles($roles);
        return $user;
    }
}
