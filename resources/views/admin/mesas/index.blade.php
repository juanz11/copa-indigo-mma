@extends('layouts.admin')

@section('title', 'Disponibilidad de Mesas')
@section('page-title', '🪑 Disponibilidad de Mesas')

@section('content')
<div class="table-card">
    <div class="table-card-header">
        <h2><i class="fas fa-chair" style="color:var(--gold);margin-right:0.5rem;"></i>Control de Mesas</h2>
        <span style="font-size:0.8rem;color:#666;">{{ $mesas->count() }} mesas en total</span>
    </div>

    <div style="overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th style="text-align:center;"># Mesa</th>
                    <th style="text-align:center;">Capacidad</th>
                    <th style="text-align:center;">Estado</th>
                    <th style="text-align:center;">Vendidas</th>
                    <th style="text-align:center;">Disponibles</th>
                    <th style="text-align:center;">Acción</th>
                </tr>
            </thead>
            <tbody>
                @foreach($mesas as $mesa)
                    @php
                        $vendidas = (int) ($mesa->registrations_sum_quantity ?? 0);
                        $disponibles = max(0, $mesa->capacidad - $vendidas);
                    @endphp
                    <tr>
                        <td style="text-align:center;font-weight:600;color:#fff;">#{{ $mesa->numero }}</td>
                        <td style="text-align:center;">
                            <input type="number" name="capacidad" value="{{ $mesa->capacidad }}" min="1" max="99" form="form-mesa-{{ $mesa->id }}" style="width:70px;padding:0.4rem;background:#111;border:1px solid rgba(255,255,255,0.1);border-radius:6px;color:#fff;text-align:center;">
                        </td>
                        <td style="text-align:center;">
                            <select name="estado" form="form-mesa-{{ $mesa->id }}" style="padding:0.4rem;background:#111;border:1px solid rgba(255,255,255,0.1);border-radius:6px;color:#fff;">
                                <option value="disponible" {{ $mesa->estado == 'disponible' ? 'selected' : '' }}>Disponible</option>
                                <option value="reservada" {{ $mesa->estado == 'reservada' ? 'selected' : '' }}>Reservada</option>
                                <option value="ocupada" {{ $mesa->estado == 'ocupada' ? 'selected' : '' }}>Ocupada</option>
                            </select>
                        </td>
                        <td style="text-align:center;color:#888;">{{ $vendidas }}</td>
                        <td style="text-align:center;color:{{ $disponibles > 0 ? '#1cc88a' : '#e74a3b' }};font-weight:600;">{{ $disponibles }}</td>
                        <td style="text-align:center;">
                            <form id="form-mesa-{{ $mesa->id }}" method="POST" action="{{ route('admin.mesas.update', $mesa) }}" style="display:inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn-action approve" title="Guardar cambios"><i class="fas fa-save"></i></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
