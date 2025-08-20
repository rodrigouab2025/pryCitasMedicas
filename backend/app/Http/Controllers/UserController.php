<?php

namespace App\Http\Controllers;

use App\Models\PerfilMedico;
use App\Models\PerfilPaciente;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::where('estado', 'S')
            ->orderBy('id')
            ->get()
            ->map(function ($user) {
                if ($user->rol === 'paciente') {
                    $user->load('perfilPaciente');
                } elseif ($user->rol === 'medico') {
                    $user->load('perfilMedico.especialidad');
                }
                return $user;
            });

        return response()->json($users);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::where('estado', 'S')->findOrFail($id);

        if ($user->rol === 'paciente') {
            $user->load('perfilPaciente');
        } elseif ($user->rol === 'medico') {
            $user->load('perfilMedico.especialidad');
        }

        return response()->json($user);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $rol = $request->input('rol', 'paciente');

        $baseRules = [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'telefono' => 'nullable|digits:8',
            'rol'      => 'nullable|in:paciente,medico,administrador',
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

        // Reglas adicionales por rol
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

        DB::beginTransaction();
        try {
            $user = User::findOrFail($id);

            // Actualizar datos base del usuario
            $user->update([
                'name'     => strtoupper($request->name),
                'email'    => $request->email,
                'telefono' => $request->telefono,
                'rol'      => $rol,
                'password' => $request->filled('password') 
                            ? Hash::make($request->password) 
                            : $user->password
            ]);

            $perfilActualizado = null;

            if ($rol === 'paciente') {
                $perfilActualizado = PerfilPaciente::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'nacimiento'       => $request->nacimiento,
                        'sexo'             => $request->sexo,
                        'direccion'        => strtoupper($request->direccion),
                        'historial_medico' => $request->historial_medico ?? null,
                    ]
                );
            } elseif ($rol === 'medico') {
                $perfilActualizado = PerfilMedico::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'reg_profesional' => $request->reg_profesional,
                        'biografia'       => $request->biografia ?? null,
                        'especialidad_id' => $request->especialidad_id,
                    ]
                );
            }

            DB::commit();

            return response()->json([
                'message' => 'Usuario actualizado correctamente.',
                'data'    => [
                    'user'   => $user,
                    'perfil' => $perfilActualizado,
                ]
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error interno al actualizar el usuario.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
        if(!$user){
            return response()->json([
                'mensaje' => 'Usuario no encontrado.'
            ], 404);
        }
        $user->update([
            'estado' => 'N'
        ]);
        return response()->json([
            'message' => 'Usuario eliminado exitosamente.'
        ], 201);
    }
    public function buscarUser(Request $request)
    {
        $busqueda = $request->input('busqueda');

        $users = User::where('estado', 'S')
            ->when($busqueda, function ($query, $busqueda) {
                $query->where(function ($q) use ($busqueda) {
                    foreach ((new User)->getFillable() as $field) {
                        $q->orWhere($field, 'like', "%{$busqueda}%");
                    }
                });
            })
            ->orderBy('id')
            ->get()
            ->map(function ($user) {
                if ($user->rol === 'paciente') {
                    $user->load('perfilPaciente');
                } elseif ($user->rol === 'medico') {
                    $user->load('perfilMedico.especialidad');
                }
                return $user;
            });

        return response()->json($users);
    }

}
