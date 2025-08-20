<?php

namespace App\Http\Controllers;

use App\Models\Horario;
use App\Models\PerfilMedico;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HorarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Horario::where('estado','S')
        ->orderBy('id')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $reglas = [
            'dia_semana'    => 'required|integer|min:1|max:7',
            'tiempo_inicio' => 'required|date_format:H:i',
            'tiempo_final'  => 'required|date_format:H:i|after:tiempo_inicio',
            'duracion_cita' => 'required|date_format:H:i',
            'medico_id'     => 'required|exists:perfiles_medicos,id',
        ];
        $mensaje = [
            'dia_semana.required'    => 'El día de la semana es obligatorio.',
            'dia_semana.integer'     => 'El día de la semana debe ser un número.',
            'dia_semana.min'         => 'El día de la semana debe estar entre 1 (lunes) y 7 (domingo).',
            'dia_semana.max'         => 'El día de la semana debe estar entre 1 (lunes) y 7 (domingo).',
            'tiempo_inicio.required' => 'La hora de inicio es obligatoria.',
            'tiempo_final.required'  => 'La hora final es obligatoria.',
            'tiempo_final.after'     => 'La hora final debe ser mayor que la hora de inicio.',
            'duracion_cita.required' => 'La duración de la cita es obligatoria.',
            'duracion_cita.date_format' => 'La duración de la cita debe tener el formato HH:mm.',
            'medico_id.required'     => 'El médico es obligatorio.',
            'medico_id.exists'       => 'El médico seleccionado no existe.',
        ];

        $validator = Validator::make($request->all(), $reglas, $mensaje);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en la validación.',
                'errors'  => $validator->errors()
            ], 422);
        }

        $horario = Horario::create([
            'dia_semana'    => $request->dia_semana,
            'tiempo_inicio' => $request->tiempo_inicio,
            'tiempo_final'  => $request->tiempo_final,
            'duracion_cita' => $request->duracion_cita,
            'medico_id'     => $request->medico_id,
        ]);

        return response()->json([
            'message' => 'Horario registrado exitosamente.',
            'horario' => $horario
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $horario = Horario::find($id);
        if(!$horario){
            return response()->json([
                'mensaje' => 'Horario no encontrado.'
            ], 404
        );
        }else{

        }
        return response()->json($horario,200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $reglas = [
            'dia_semana'    => 'required|integer|min:1|max:7',
            'tiempo_inicio' => 'required|date_format:H:i',
            'tiempo_final'  => 'required|date_format:H:i|after:tiempo_inicio',
            'duracion_cita' => 'required|date_format:H:i',
            'medico_id'     => 'required|exists:perfiles_medicos,id',
        ];

        $mensaje = [
            'dia_semana.required'    => 'El día de la semana es obligatorio.',
            'dia_semana.integer'     => 'El día de la semana debe ser un número.',
            'dia_semana.min'         => 'El día de la semana debe estar entre 1 (lunes) y 7 (domingo).',
            'dia_semana.max'         => 'El día de la semana debe estar entre 1 (lunes) y 7 (domingo).',
            'tiempo_inicio.required' => 'La hora de inicio es obligatoria.',
            'tiempo_final.required'  => 'La hora final es obligatoria.',
            'tiempo_final.after'     => 'La hora final debe ser mayor que la hora de inicio.',
            'duracion_cita.required' => 'La duración de la cita es obligatoria.',
            'duracion_cita.date_format' => 'La duración de la cita debe tener el formato HH:mm.',
            'medico_id.required'     => 'El médico es obligatorio.',
            'medico_id.exists'       => 'El médico seleccionado no existe.',
        ];

        $validator = Validator::make($request->all(), $reglas, $mensaje);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en la validación.',
                'errors'  => $validator->errors()
            ], 422);
        }

        $horario = Horario::find($id);

        if (!$horario) {
            return response()->json([
                'mensaje' => 'Horario no encontrado.'
            ], 404);
        }

        $horario->update([
            'dia_semana'    => $request->dia_semana,
            'tiempo_inicio' => $request->tiempo_inicio,
            'tiempo_final'  => $request->tiempo_final,
            'duracion_cita' => $request->duracion_cita,
            'medico_id'     => $request->medico_id,
        ]);

        return response()->json([
            'message' => 'Horario actualizado exitosamente.',
            'horario' => $horario
        ], 200);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $horario = Horario::find($id);
        if(!$horario){
            return response()->json([
                'mensaje' => 'Horario no encontrado.'
            ], 404);
        }
        $horario->update([
            'estado' => 'N'
        ]);
        return response()->json([
            'message' => 'Horario eliminado exitosamente.'
        ], 201);
    }
    public function disponibles(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date',
            'especialidad_id' => 'required|integer|exists:especialidades,id',
        ]);
        $fecha = Carbon::parse($request->fecha);
        $diaSemana = $fecha->dayOfWeekIso;
        $medicos = PerfilMedico::with([
                'user',
                'especialidad',
                'horarios' => function($q) use ($diaSemana) {
                    $q->where('dia_semana', $diaSemana)
                      ->where('estado', 'S');
                }
            ])
            ->where('especialidad_id', $request->especialidad_id)
            ->get();

        return response()->json($medicos, 200);
    }
    public function buscarHorario(Request $request)
    {
        $busqueda = $request->input('busqueda');

        $horarios = Horario::with(['medico.especialidad', 'medico.user'])
            ->where('estado', 'S')
            ->when($busqueda, function ($query, $busqueda) {
                $query->where(function ($q) use ($busqueda) {
                    foreach ((new Horario)->getFillable() as $field) {
                        $q->orWhere($field, 'like', "%{$busqueda}%");
                    }
                })
                ->orWhereHas('medico', function ($q) use ($busqueda) {
                    foreach ((new PerfilMedico)->getFillable() as $field) {
                        $q->orWhere($field, 'like', "%{$busqueda}%");
                    }
                    $q->orWhereHas('user', function ($q2) use ($busqueda) {
                        foreach ((new User())->getFillable() as $field) {
                            $q2->orWhere($field, 'like', "%{$busqueda}%");
                        }
                    });
                });
            })
            ->orderBy('id')
            ->get();

        return response()->json($horarios);
    }

}
