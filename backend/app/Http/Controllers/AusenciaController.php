<?php

namespace App\Http\Controllers;

use App\Models\Ausencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AusenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Ausencia::where('estado','S')
        ->orderBy('fecha_inicio')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $reglas = [
            'fecha_inicio'  => 'required|date',
            'fecha_final'   => 'required|date|after_or_equal:fecha_inicio',
            'tiempo_inicio' => 'required|date_format:H:i',
            'tiempo_final'  => 'required|date_format:H:i|after:tiempo_inicio',
            'razon'         => 'required|string|max:100',
            'medico_id'     => 'required|exists:perfiles_medicos,id',
        ];

        $mensaje = [
            'fecha_inicio.required'  => 'La fecha de inicio es obligatoria.',
            'fecha_final.required'   => 'La fecha final es obligatoria.',
            'fecha_final.after_or_equal' => 'La fecha final debe ser mayor o igual a la fecha de inicio.',
            'tiempo_inicio.required' => 'La hora de inicio es obligatoria.',
            'tiempo_final.required'  => 'La hora final es obligatoria.',
            'tiempo_final.after'     => 'La hora final debe ser mayor a la hora de inicio.',
            'razon.required'         => 'La razón de la ausencia es obligatoria.',
            'razon.max'              => 'La razón no puede exceder los 100 caracteres.',
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

        $ausencia = Ausencia::create([
            'fecha_inicio'  => $request->fecha_inicio,
            'fecha_final'   => $request->fecha_final,
            'tiempo_inicio' => $request->tiempo_inicio,
            'tiempo_final'  => $request->tiempo_final,
            'razon'         => strtoupper($request->razon),
            'medico_id'     => $request->medico_id,
        ]);

        return response()->json([
            'message'  => 'Ausencia registrada exitosamente.',
            'ausencia' => $ausencia
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $ausencia = Ausencia::find($id);
        if(!$ausencia){
            return response()->json([
                'mensaje' => 'Ausencia no encontrada.'
            ], 404
        );
        }else{

        }
        return response()->json($ausencia,200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $reglas = [
            'fecha_inicio'  => 'required|date',
            'fecha_final'   => 'required|date|after_or_equal:fecha_inicio',
            'tiempo_inicio' => 'required|date_format:H:i',
            'tiempo_final'  => 'required|date_format:H:i|after:tiempo_inicio',
            'razon'         => 'required|string|max:100',
            'medico_id'     => 'required|exists:perfiles_medicos,id',
        ];
        $mensaje = [
            'fecha_inicio.required'  => 'La fecha de inicio es obligatoria.',
            'fecha_final.required'   => 'La fecha final es obligatoria.',
            'fecha_final.after_or_equal' => 'La fecha final debe ser mayor o igual a la fecha de inicio.',
            'tiempo_inicio.required' => 'La hora de inicio es obligatoria.',
            'tiempo_final.required'  => 'La hora final es obligatoria.',
            'tiempo_final.after'     => 'La hora final debe ser mayor a la hora de inicio.',
            'razon.required'         => 'La razón de la ausencia es obligatoria.',
            'razon.max'              => 'La razón no puede exceder los 100 caracteres.',
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

        $ausencia = Ausencia::find($id);

        if (!$ausencia) {
            return response()->json([
                'mensaje' => 'Ausencia no encontrada.'
            ], 404);
        }

        $ausencia->update([
            'fecha_inicio'  => $request->fecha_inicio,
            'fecha_final'   => $request->fecha_final,
            'tiempo_inicio' => $request->tiempo_inicio,
            'tiempo_final'  => $request->tiempo_final,
            'razon'         => strtoupper($request->razon),
            'medico_id'     => $request->medico_id,
        ]);

        return response()->json([
            'message'  => 'Ausencia actualizada exitosamente.',
            'ausencia' => $ausencia
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ausencia = Ausencia::find($id);
        if(!$ausencia){
            return response()->json([
                'mensaje' => 'Ausencia no encontrada.'
            ], 404);
        }
        $ausencia->update([
            'estado' => 'N'
        ]);
        return response()->json([
            'message' => 'Ausencia eliminada exitosamente.'
        ], 201);
    }
}
