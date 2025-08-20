<?php

namespace App\Http\Controllers;

use App\Models\Especialidad;
use Illuminate\Http\Request;

class EspecialidadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Especialidad::where('estado','S')
        ->orderBy('nombre')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:20',
            'descripcion' => 'nullable|string',
        ]);

        $especialidad = Especialidad::create([
            'nombre' => strtoupper($request->nombre),
            'descripcion' => strtoupper($request->descripcion),
        ]);
        return response()->json([
            'message' => 'Especialidad creada exitosamente.',
            'especialidad' => $especialidad
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $especialidad = Especialidad::find($id);
        if(!$especialidad){
            return response()->json([
                'mensaje' => 'Especialidad no encontrada.'
            ], 404
        );
        }else{

        }
        return response()->json($especialidad,200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:20',
            'descripcion' => 'nullable|string',
        ]);
        $especialidad = Especialidad::find($id);
        if(!$especialidad){
            return response()->json([
                'mensaje' => 'Especialidad no encontrada.'
            ], 404);
        }
        $especialidad->update([
            'nombre' => strtoupper($request->nombre),
            'descripcion' => strtoupper($request->descripcion),
        ]);
        return response()->json([
            'message'      => 'Especialidad actualizada exitosamente.',
            'especialidad' => $especialidad
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $especialidad = Especialidad::find($id);
        if(!$especialidad){
            return response()->json([
                'mensaje' => 'Especialidad no encontrada.'
            ], 404);
        }
        $especialidad->update([
            'estado' => 'N'
        ]);
        return response()->json([
            'message' => 'Especialidad eliminada exitosamente.'
        ], 201);
    }
    public function buscarEspecialidad(Request $request)
    {
        $busqueda = $request->input('busqueda');

        $especialidades = Especialidad::where('estado', 'S')
            ->when($busqueda, function ($query, $busqueda) {
                $query->where(function ($q) use ($busqueda) {
                    foreach ((new Especialidad)->getFillable() as $field) {
                        $q->orWhere($field, 'like', "%{$busqueda}%");
                    }
                });
            })
            ->orderBy('nombre')
            ->get();

        return response()->json($especialidades);
}

}
