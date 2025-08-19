<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use Illuminate\Http\Request;

class ReporteController extends Controller
{
    public function boletaReserva($id)
    {
        $cita = Cita::with([
            'paciente.user',
            'horario.medico.user',
            'horario.medico.especialidad'
        ])->findOrFail($id);
        return response()->json([
            'id' => $cita->id,
            'fecha' => $cita->fecha,
            'desde' => $cita->tiempo_inicio,
            'hasta' => $cita->tiempo_final,
            'estado' => $cita->estado,
            'paciente' => $cita->paciente ? $cita->paciente->user->name : null,
            'medico' => $cita->medico ? $cita->medico->user->name : null,
            'especialidad' => $cita->medico && $cita->medico->especialidad ? $cita->medico->especialidad->nombre : null,
        ]);


        return response()->json($data);
    }
    public function reporteCitas(Request $request)
    {
        $citas = Cita::with(['paciente.user', 'horario.medico.user', 'horario.medico.especialidad'])
            ->whereHas('horario', function ($q) use ($request) {
                $q->where('medico_id', $request->medico_id);
            })
            ->whereBetween('fecha', [$request->fecha_inicio, $request->fecha_fin])
            ->orderBy('fecha')
            ->orderBy('tiempo_inicio')
            ->get();

        $data = $citas->map(function ($cita) {
            return [
                'cita_id'   => $cita->id,
                'paciente'  => $cita->paciente ? $cita->paciente->user->name : null,
                'fecha'     => $cita->fecha,
                'hora_inicio'=> $cita->tiempo_inicio,
                'hora_fin'  => $cita->tiempo_final,
                'estado'    => $cita->estado,
            ];
        });

        return response()->json([
            'medico_id'    => $request->medico_id,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin'    => $request->fecha_fin,
            'total'        => $data->count(),
            'citas'        => $data,
        ]);


    }
}
