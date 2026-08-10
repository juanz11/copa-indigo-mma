<?php

namespace App\Http\Controllers;

use App\Models\Mesa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MapaController extends Controller
{
    public function index()
    {
        $mesas = Mesa::withSum('registrations', 'quantity')
            ->orderByRaw('CAST(numero AS UNSIGNED)')
            ->get();

        return view('mapa', compact('mesas'));
    }

    public function reservar(Request $request)
    {
        $request->validate([
            'mesa_id' => 'required|exists:mesas,id',
        ]);

        $mesa = Mesa::withSum('registrations', 'quantity')->findOrFail($request->mesa_id);

        $vendidas = (int) ($mesa->registrations_sum_quantity ?? 0);

        if ($mesa->estado === 'ocupada' || $vendidas >= $mesa->capacidad) {
            return response()->json([
                'success' => false,
                'message' => 'La mesa #' . $mesa->numero . ' está completa. Sillas vendidas: ' . $vendidas . '/' . $mesa->capacidad . '.',
            ], 409);
        }

        return response()->json([
            'success'    => true,
            'mesa_id'    => $mesa->id,
            'numero'     => $mesa->numero,
            'redirect'   => route('mma.registro', [
                'mesa_id' => $mesa->id,
                'numero'  => $mesa->numero,
            ]),
            'message'    => 'Mesa #' . $mesa->numero . ' seleccionada.',
        ]);
    }

    public function adminMesas()
    {
        $mesas = Mesa::withSum('registrations', 'quantity')
            ->orderByRaw('CAST(numero AS UNSIGNED)')
            ->get();

        return view('admin.mesas.index', compact('mesas'));
    }

    public function updateMesa(Request $request, Mesa $mesa)
    {
        $validated = $request->validate([
            'capacidad' => 'required|integer|min:1',
            'estado'    => 'required|in:disponible,reservada,ocupada',
        ]);

        $mesa->update($validated);

        return back()->with('success', 'Mesa #' . $mesa->numero . ' actualizada.');
    }
}
