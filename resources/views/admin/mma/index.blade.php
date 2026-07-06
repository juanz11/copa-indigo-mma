@extends('layouts.admin')

@section('title', 'Registros — Copa Índigo MMA')
@section('page-title', '🥊 Registros Copa Índigo MMA')

@section('content')

<!-- Stats -->
<div class="stats-grid">
    <div class="stat-card warning">
        <p class="stat-label"><i class="fas fa-clock"></i> Pendientes</p>
        <p class="stat-value">{{ $stats['pending'] }}</p>
    </div>
    <div class="stat-card success">
        <p class="stat-label"><i class="fas fa-check-circle"></i> Aprobados</p>
        <p class="stat-value">{{ $stats['approved'] }}</p>
    </div>
    <div class="stat-card danger">
        <p class="stat-label"><i class="fas fa-times-circle"></i> Rechazados</p>
        <p class="stat-value">{{ $stats['rejected'] }}</p>
    </div>
    <div class="stat-card">
        <p class="stat-label"><i class="fas fa-users"></i> Total</p>
        <p class="stat-value">{{ $stats['total'] }}</p>
    </div>
    <div class="stat-card gold">
        <p class="stat-label"><i class="fas fa-dollar-sign"></i> Recaudado</p>
        <p class="stat-value">${{ number_format($stats['revenue'], 2) }}</p>
    </div>
</div>

<!-- Table -->
<div class="table-card">
    <div class="table-card-header">
        <h2><i class="fas fa-list" style="color:var(--gold);margin-right:0.5rem;"></i>Lista de Registros</h2>
        <span style="font-size:0.8rem;color:#666;">{{ $registrations->total() }} registros en total</span>
    </div>

    @if($registrations->isEmpty())
        <div class="empty-state">
            <i class="fas fa-inbox"></i>
            <p>No hay registros aún.</p>
        </div>
    @else
        <div style="overflow-x:auto;">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Cédula</th>
                        <th>Teléfono</th>
                        <th>Tipo</th>
                        <th>Cant.</th>
                        <th>Total</th>
                        <th>Método Pago</th>
                        <th>Referencia</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($registrations as $reg)
                    <tr>
                        <td style="color:#555;">{{ $reg->id }}</td>
                        <td style="color:#fff;font-weight:500;">{{ $reg->full_name }}</td>
                        <td>{{ $reg->id_number }}</td>
                        <td>{{ $reg->phone }}</td>
                        <td>
                            <span class="badge badge-{{ $reg->ticket_type }}">{{ ucfirst($reg->ticket_type) }}</span>
                        </td>
                        <td style="text-align:center;">{{ $reg->quantity }}</td>
                        <td style="color:var(--gold);font-weight:600;">${{ number_format($reg->total_amount, 2) }}</td>
                        <td style="text-transform:capitalize;">{{ $reg->payment_method ?? '—' }}</td>
                        <td>
                            @if($reg->payment_reference)
                                <code style="background:rgba(255,255,255,0.05);padding:0.2rem 0.5rem;border-radius:4px;font-size:0.8rem;">{{ $reg->payment_reference }}</code>
                            @else
                                <span style="color:#555;">—</span>
                            @endif
                        </td>
                        <td>
                            @if($reg->status === 'approved')
                                <span class="badge badge-approved">Aprobado</span>
                            @elseif($reg->status === 'pending')
                                <span class="badge badge-pending">Pendiente</span>
                            @else
                                <span class="badge badge-rejected">Rechazado</span>
                            @endif
                        </td>
                        <td style="color:#555;font-size:0.8rem;">{{ $reg->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <div class="action-btns">
                                @if($reg->status === 'pending')
                                    <button class="btn-action approve" title="Aprobar" onclick="openStatusModal({{ $reg->id }}, 'approved')">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button class="btn-action reject" title="Rechazar" onclick="openStatusModal({{ $reg->id }}, 'rejected')">
                                        <i class="fas fa-times"></i>
                                    </button>
                                @endif
                                <button class="btn-action view" title="Ver detalles" onclick="openDetails({{ $reg->id }})">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn-action delete" title="Eliminar" onclick="openDelete({{ $reg->id }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="pagination">
            {{ $registrations->links() }}
        </div>
    @endif
</div>

<!-- Modal: Update Status -->
<div class="m-overlay" id="statusModal">
    <div class="m-box">
        <div class="m-header">
            <h3 id="statusModalTitle">Actualizar Estado</h3>
            <button class="m-close" onclick="closeStatusModal()">&times;</button>
        </div>
        <form id="statusForm" method="POST">
            @csrf @method('PATCH')
            <input type="hidden" id="statusAction" name="status">
            <div class="m-body">
                <div class="form-group">
                    <label>Notas del Administrador (opcional)</label>
                    <textarea name="admin_notes" rows="3" placeholder="Agrega una nota sobre esta decisión..."></textarea>
                </div>
            </div>
            <div class="m-footer">
                <button type="button" class="btn-secondary" onclick="closeStatusModal()">Cancelar</button>
                <button type="submit" class="btn-confirm">Confirmar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Details -->
<div class="m-overlay" id="detailsModal">
    <div class="m-box">
        <div class="m-header">
            <h3>Detalles del Registro</h3>
            <button class="m-close" onclick="closeDetails()">&times;</button>
        </div>
        <div class="m-body" id="detailsContent"></div>
        <div class="m-footer">
            <button type="button" class="btn-secondary" onclick="closeDetails()">Cerrar</button>
        </div>
    </div>
</div>

<!-- Modal: Delete -->
<div class="m-overlay" id="deleteModal">
    <div class="m-box">
        <div class="m-header">
            <h3>Confirmar Eliminación</h3>
            <button class="m-close" onclick="closeDelete()">&times;</button>
        </div>
        <form id="deleteForm" method="POST">
            @csrf @method('DELETE')
            <div class="m-body">
                <p style="color:#ccc;">¿Estás seguro de que deseas eliminar este registro? Esta acción no se puede deshacer.</p>
            </div>
            <div class="m-footer">
                <button type="button" class="btn-secondary" onclick="closeDelete()">Cancelar</button>
                <button type="submit" class="btn-danger-solid">Eliminar</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const allRegs = @json($registrations->items());
    const ticketLabels = { general: 'General', vip: 'VIP', ringside: 'Ringside' };
    const statusLabels = { pending: 'Pendiente', approved: 'Aprobado', rejected: 'Rechazado' };
    const statusColors = { pending: '#f6c23e', approved: '#1cc88a', rejected: '#e74a3b' };

    function openStatusModal(id, action) {
        document.getElementById('statusForm').action = `/admin/registros/${id}/status`;
        document.getElementById('statusAction').value = action;
        document.getElementById('statusModalTitle').textContent = action === 'approved' ? '✅ Aprobar Registro' : '❌ Rechazar Registro';
        document.getElementById('statusModal').classList.add('show');
    }
    function closeStatusModal() {
        document.getElementById('statusModal').classList.remove('show');
        document.getElementById('statusForm').reset();
    }

    function openDetails(id) {
        const reg = allRegs.find(r => r.id === id);
        if (!reg) return;
        const color = statusColors[reg.status] || '#888';
        const label = statusLabels[reg.status] || reg.status;
        document.getElementById('detailsContent').innerHTML = `
            <div class="detail-row"><span class="detail-label">ID</span><span class="detail-value">${reg.id}</span></div>
            <div class="detail-row"><span class="detail-label">Estado</span><span class="detail-value" style="color:${color};font-weight:600;">${label}</span></div>
            <div class="detail-row"><span class="detail-label">Nombre Completo</span><span class="detail-value" style="color:#fff;font-weight:500;">${reg.full_name}</span></div>
            <div class="detail-row"><span class="detail-label">Cédula</span><span class="detail-value">${reg.id_number}</span></div>
            <div class="detail-row"><span class="detail-label">Teléfono</span><span class="detail-value">${reg.phone}</span></div>
            <div class="detail-row"><span class="detail-label">Correo</span><span class="detail-value">${reg.email || '—'}</span></div>
            <div class="detail-row"><span class="detail-label">Red Social</span><span class="detail-value">${reg.social_media || '—'}</span></div>
            <div class="detail-row"><span class="detail-label">Tipo de Entrada</span><span class="detail-value" style="color:var(--gold);font-weight:600;">${ticketLabels[reg.ticket_type] || reg.ticket_type}</span></div>
            <div class="detail-row"><span class="detail-label">Cantidad</span><span class="detail-value">${reg.quantity}</span></div>
            <div class="detail-row"><span class="detail-label">Total</span><span class="detail-value" style="color:var(--gold);font-weight:700;">$${parseFloat(reg.total_amount).toFixed(2)} USD</span></div>
            <div class="detail-row"><span class="detail-label">Método de Pago</span><span class="detail-value" style="text-transform:capitalize;">${reg.payment_method || '—'}</span></div>
            <div class="detail-row"><span class="detail-label">Referencia</span><span class="detail-value">${reg.payment_reference || '—'}</span></div>
            ${reg.payment_proof ? `<div class="detail-row"><span class="detail-label">Comprobante</span><span class="detail-value"><a href="/storage/${reg.payment_proof}" target="_blank" style="color:var(--gold);">Ver comprobante</a></span></div>` : ''}
            <div class="detail-row"><span class="detail-label">Fecha Registro</span><span class="detail-value">${new Date(reg.created_at).toLocaleString('es-ES')}</span></div>
            ${reg.admin_notes ? `<div class="detail-row"><span class="detail-label">Notas Admin</span><span class="detail-value" style="color:#ccc;">${reg.admin_notes}</span></div>` : ''}
        `;
        document.getElementById('detailsModal').classList.add('show');
    }
    function closeDetails() { document.getElementById('detailsModal').classList.remove('show'); }

    function openDelete(id) {
        document.getElementById('deleteForm').action = `/admin/registros/${id}`;
        document.getElementById('deleteModal').classList.add('show');
    }
    function closeDelete() { document.getElementById('deleteModal').classList.remove('show'); }

    document.querySelectorAll('.m-overlay').forEach(m => {
        m.addEventListener('click', e => { if (e.target === m) m.classList.remove('show'); });
    });
</script>
@endsection
