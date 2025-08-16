<?php

namespace App\Http\Controllers;

use App\Models\PerfilPaciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    //
    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'min:8'],
        ]);
        
        $user = User::where('email', $request->email)->first();
        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Credenciales incorrectas.'], 401);
        }
        if ($user->estado !== 'S') {
            return response()->json([
                'message' => 'Tu cuenta está inactiva, contacta al administrador.'
            ], 403);
        }
        $user->tokens()->delete();
        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'message' => 'Login exitoso.',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'rol' => $user->rol,
                'telefono' => $user->telefono,
            ]
        ]);
    }
    public function registrarpaciente(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'       => 'required|string|max:255',
            'email'      => 'required|string|email|max:255|unique:users,email',
            'password'   => 'required|string|min:8|confirmed',
            'telefono'   => 'nullable|digits:8',
            'rol'        => 'nullable|in:paciente,medico,administrador',
            'nacimiento' => 'required|date',
            'sexo'       => 'required|in:VARON,MUJER',
            'direccion'  => 'nullable|string',
            'historial_medico' => 'nullable|string',
        ], [
            'email.unique' => 'El correo electrónico ya está registrado.',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en la validación.',
                'errors'  => $validator->errors()
            ], 422);
        }
        $user = User::create([
            'name'     => strtoupper($request->name),
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'telefono' => $request->telefono,
            'rol'      => $request->rol ?? 'paciente'
        ]);

        $paciente = PerfilPaciente::create([
            'nacimiento'       => $request->nacimiento,
            'sexo'             => $request->sexo,
            'direccion'        => strtoupper($request->direccion), 
            'user_id'          => $user->id
        ]);

        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'message' => 'Usuario registrado correctamente.',
            'data'    => [
                'user'     => $user,
                'paciente' => $paciente,
                'token'    => $token
            ]
        ], 201);
    }


}