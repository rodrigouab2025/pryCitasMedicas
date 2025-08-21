<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Horario;
use App\Models\PerfilPaciente;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CitaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Cita::where('estado','S')
        ->orderBy('id')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $reglas = [
            'fecha'         => 'required|date',
            'tiempo_inicio' => 'required|date_format:H:i',
            'tiempo_final'  => 'required|date_format:H:i|after:tiempo_inicio',
            'razon'         => 'required|string|max:100',
            'horario_id'    => 'required|exists:horarios,id',
            'paciente_id'   => 'required|exists:perfiles_pacientes,id',
        ];

        $mensaje = [
            'fecha.required'         => 'La fecha de la cita es obligatoria.',
            'fecha.date'             => 'La fecha no es válida.',
            'tiempo_inicio.required' => 'La hora de inicio es obligatoria.',
            'tiempo_inicio.date_format' => 'La hora de inicio debe tener el formato HH:mm.',
            'tiempo_final.required'  => 'La hora final es obligatoria.',
            'tiempo_final.date_format' => 'La hora final debe tener el formato HH:mm.',
            'tiempo_final.after'     => 'La hora final debe ser posterior a la hora de inicio.',
            'razon.required'         => 'La razón de la cita es obligatoria.',
            'razon.max'              => 'La razón no puede exceder los 100 caracteres.',
            'horario_id.required'    => 'El horario es obligatorio.',
            'horario_id.exists'      => 'El horario seleccionado no existe.',
            'paciente_id.required'   => 'El paciente es obligatorio.',
            'paciente_id.exists'     => 'El paciente seleccionado no existe.',
        ];

        $validator = Validator::make($request->all(), $reglas, $mensaje);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en la validación.',
                'errors'  => $validator->errors()
            ], 422);
        }

         $existeCita = Cita::where('fecha', $request->fecha)
            ->where('horario_id', $request->horario_id)
            ->where('estado', 'S') 
            ->where(function ($query) use ($request) {
                $query->whereBetween('tiempo_inicio', [$request->tiempo_inicio, $request->tiempo_final])
                    ->orWhereBetween('tiempo_final', [$request->tiempo_inicio, $request->tiempo_final])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('tiempo_inicio', '<=', $request->tiempo_inicio)
                            ->where('tiempo_final', '>=', $request->tiempo_final);
                    });
            })
            ->exists();

        if ($existeCita) {
            return response()->json([
                'message' => 'El horario seleccionado no está disponible.',
            ], 409);
        }
        $paciente = PerfilPaciente::where('user_id',$request->paciente_id)->first();
        $cita = Cita::create([
            'fecha'         => $request->fecha,
            'tiempo_inicio' => $request->tiempo_inicio,
            'tiempo_final'  => $request->tiempo_final,
            'razon'         => strtoupper($request->razon),
            'horario_id'    => $request->horario_id,
            'paciente_id'   => $paciente->id,
        ]);

        return response()->json([
            'message' => 'Cita registrada exitosamente.',
            'cita'    => $cita
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $cita = Cita::with('paciente.user')->find($id);

        if (!$cita) {
            return response()->json([
                'mensaje' => 'Cita no encontrada.'
            ], 404);
        }

        return response()->json($cita, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
         $reglas = [
            'fecha'         => 'required|date',
            'tiempo_inicio' => 'required|date_format:H:i',
            'tiempo_final'  => 'required|date_format:H:i|after:tiempo_inicio',
            'razon'         => 'required|string|max:100',
            'horario_id'    => 'required|exists:horarios,id',
            'paciente_id'   => 'required|exists:perfiles_pacientes,id',
        ];

        $mensaje = [
            'fecha.required'         => 'La fecha de la cita es obligatoria.',
            'fecha.date'             => 'La fecha no es válida.',
            'tiempo_inicio.required' => 'La hora de inicio es obligatoria.',
            'tiempo_inicio.date_format' => 'La hora de inicio debe tener el formato HH:mm.',
            'tiempo_final.required'  => 'La hora final es obligatoria.',
            'tiempo_final.date_format' => 'La hora final debe tener el formato HH:mm.',
            'tiempo_final.after'     => 'La hora final debe ser posterior a la hora de inicio.',
            'razon.required'         => 'La razón de la cita es obligatoria.',
            'razon.max'              => 'La razón no puede exceder los 100 caracteres.',
            'horario_id.required'    => 'El horario es obligatorio.',
            'horario_id.exists'      => 'El horario seleccionado no existe.',
            'paciente_id.required'   => 'El paciente es obligatorio.',
            'paciente_id.exists'     => 'El paciente seleccionado no existe.',
        ];

        $validator = Validator::make($request->all(), $reglas, $mensaje);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en la validación.',
                'errors'  => $validator->errors()
            ], 422);
        }
        $existeCita = Cita::where('fecha', $request->fecha)
            ->where('horario_id', $request->horario_id)
            ->where('estado', 'S') 
            ->where(function ($query) use ($request) {
                $query->whereBetween('tiempo_inicio', [$request->tiempo_inicio, $request->tiempo_final])
                    ->orWhereBetween('tiempo_final', [$request->tiempo_inicio, $request->tiempo_final])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('tiempo_inicio', '<=', $request->tiempo_inicio)
                            ->where('tiempo_final', '>=', $request->tiempo_final);
                    });
            })
            ->exists();

        if ($existeCita) {
            return response()->json([
                'message' => 'El horario seleccionado no está disponible.',
            ], 409);
        }

        $cita = Cita::find($id);

        if (!$cita) {
            return response()->json([
                'mensaje' => 'Cita no encontrada.'
            ], 404);
        }

        $cita->update([
            'fecha'         => $request->fecha,
            'tiempo_inicio' => $request->tiempo_inicio,
            'tiempo_final'  => $request->tiempo_final,
            'razon'         => strtoupper($request->razon),
            'estado'        => $request->estado ?? 'S',
            'horario_id'    => $request->horario_id,
            'paciente_id'   => $request->paciente_id,
        ]);

        return response()->json([
            'message' => 'Cita actualizada exitosamente.',
            'cita'    => $cita
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $cita = Cita::find($id);
        if(!$cita){
            return response()->json([
                'mensaje' => 'Cita no encontrada.'
            ], 404);
        }
        $cita->update([
            'estado' => 'N'
        ]);
        return response()->json([
            'message' => 'Cita eliminada exitosamente.'
        ], 201);
    }
    public function buscarCitaPaciente(string $id){
        $cita = Cita::where('paciente_id',$id)->where('estado','S')->get();
        if(!$cita){
            return response()->json([
                'mensaje' => 'Cita no encontrada.'
            ], 404
        );
        }else{

        }
        return response()->json($cita,200);
    }
    public function buscarCitaUsuario(string $id)
    {
        $hoy = now()->toDateString();
        $citas = Cita::with(['horario.medico', 'paciente'])
                    ->whereHas('horario.medico', function ($query) use ($id) {
                        $query->where('user_id', $id);
                    })
                    ->where('fecha', '>=', $hoy)
                    ->where('estado', 'S')
                    ->orderBy('fecha', 'asc')
                    ->orderBy('tiempo_inicio', 'asc')
                    ->get();

        if ($citas->isEmpty()) {
            return response()->json([
                'mensaje' => 'Cita no encontrada.'
            ], 404);
        }

        return response()->json([
            'mensaje' => 'Citas encontradas',
            'data' => $citas
        ], 200);
    }
    public function modificarHistorialPaciente(Request $request, string $id)
    {
        $perfil = PerfilPaciente::updateOrCreate(
            ['id' => $id],
            [
                'historial_medico' => $request->historial_medico ?? null,
            ]
        );

        return response()->json([
            'mensaje' => 'Historial médico actualizado correctamente.',
            'data' => $perfil
        ]);
    }
    public function buscarCitaActualUsuario(string $id)
    {
        $hoy = now()->toDateString();

        $cita = Cita::with(['horario.medico', 'paciente'])
            ->whereHas('paciente', function ($query) use ($id) {
                $query->where('user_id', $id); 
            })
            ->where('estado', 'S')
            ->where('fecha', '>=', $hoy)
            ->orderBy('fecha', 'desc')
            ->orderBy('tiempo_final', 'desc')
            ->first();


        if (!$cita) {
            return response()->json([
                'mensaje' => 'Cita no encontrada.'
            ], 404);
        }

        return response()->json([
            'mensaje' => 'Cita encontrada',
            'data' => $cita
        ], 200);
    }

}
