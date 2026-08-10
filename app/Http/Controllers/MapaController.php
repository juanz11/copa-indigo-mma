<?php

namespace App\Http\Controllers;

use App\Models\Mesa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MapaController extends Controller
{
    public function index()
    {
        $mesas = Mesa::orderByRaw('CAST(numero AS UNSIGNED)')->get();
        return view('mapa', compact('mesas'));
    }

    public function reservar(Request $request)
    {
        $request->validate([
            'mesa_id' => 'required|exists:mesas,id',
        ]);

        return DB::transaction(function () use ($request) {
            $mesa = Mesa::lockForUpdate()->findOrFail($request->mesa_id);

            if ($mesa->estado !== 'disponible') {
                return response()->json([
                    'success' => false,
                    'message' => 'La mesa #' . $mesa->numero . ' ya no está disponible.',
                ], 409);
            }

            $mesa->update(['estado' => 'reservada']);

            return response()->json([
                'success'    => true,
                'mesa_id'    => $mesa->id,
                'numero'     => $mesa->numero,
                'redirect'   => route('home', ['mesa_id' => $mesa->id, 'numero' => $mesa->numero, 'open_modal' => 1]),
                'message'    => 'Mesa #' . $mesa->numero . ' reservada. Completa el pago.',
            ]);
        });
    }
}
