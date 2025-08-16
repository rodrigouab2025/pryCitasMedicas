<?php

namespace App\Http\Controllers;

use App\Models\PerfilMedico;
use App\Models\PerfilPaciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

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
    public function registrarusuario(Request $request)
    {
        $rol = $request->input('rol', 'paciente');
        $baseRules = [
            'name'       => 'required|string|max:255',
            'email'      => 'required|string|email|max:255|unique:users,email',
            'password'   => 'required|string|min:8|confirmed',
            'telefono'   => 'nullable|digits:8',
            'rol'        => 'nullable|in:paciente,medico,administrador',
        ];

        $messages = [
            'email.unique' => 'El correo electrónico ya está registrado.',
        ];

        $validator = Validator::make($request->all(), $baseRules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en la validación.',
                'errors'  => $validator->errors()
            ], 422);
        }
        if ($rol === 'paciente') {
            $rulesPaciente = [
                'nacimiento'       => 'required|date',
                'sexo'             => 'required|in:VARON,MUJER',
                'direccion'        => 'nullable|string',
                'historial_medico' => 'nullable|string',
            ];

            $v2 = Validator::make($request->all(), $rulesPaciente);
            if ($v2->fails()) {
                return response()->json([
                    'message' => 'Error en la validación (paciente).',
                    'errors'  => $v2->errors()
                ], 422);
            }
        } elseif ($rol === 'medico') {
            $rulesMedico = [
                'reg_profesional'  => 'required|string|max:20',
                'especialidad_id'  => 'required|exists:especialidades,id',
                'biografia'        => 'nullable|string',
            ];

            $v3 = Validator::make($request->all(), $rulesMedico);
            if ($v3->fails()) {
                return response()->json([
                    'message' => 'Error en la validación (médico).',
                    'errors'  => $v3->errors()
                ], 422);
            }
        }
        $perfilCreado = null;
        DB::beginTransaction();
        try {
            $user = User::create([
                'name'     => strtoupper($request->name),
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'telefono' => $request->telefono,
                'rol'      => $rol,
            ]);

            if ($rol === 'paciente') {
                $perfilCreado = PerfilPaciente::create([
                    'nacimiento'       => $request->nacimiento,
                    'sexo'             => $request->sexo,
                    'direccion'        => strtoupper($request->direccion),
                    'historial_medico' => $request->historial_medico ?? null,
                    'user_id'          => $user->id,
                ]);
            } elseif ($rol === 'medico') {
                $perfilCreado = PerfilMedico::create([
                    'reg_profesional' => $request->reg_profesional,
                    'biografia'       => $request->biografia ?? null,
                    'especialidad_id' => $request->especialidad_id,
                    'user_id'         => $user->id,
                ]);
            }
            $token = $user->createToken('api_token')->plainTextToken;

            DB::commit();

            return response()->json([
                'message' => 'Usuario registrado correctamente.',
                'data'    => [
                    'user'    => $user,
                    'perfil'  => $perfilCreado, 
                    'token'   => $token,
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error interno al registrar el usuario.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }



}