<?php

namespace App\Http\Controllers;

use App\Mail\TicketApprovedMail;
use App\Models\Mesa;
use App\Models\MmaRegistration;
use App\Models\WhatsappNotification;
use App\Services\WhatsappMessageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class MmaRegistrationController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'full_name'         => 'required|string|max:255',
                'id_number'         => 'required|string|max:50',
                'phone'             => 'required|string|max:20',
                'email'             => 'nullable|email|max:255',
                'social_media'      => 'nullable|string|max:255',
                'ticket_type'       => 'required|in:general,vip,ringside,mesa,mesa_general,mesa_vip',
                'quantity'          => 'required|integer|min:1',
                'total_amount'      => 'required|numeric|min:1',
                'payment_method'    => 'nullable|string|max:100',
                'payment_reference' => 'nullable|string|max:255',
                'payment_proof'     => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
                'mesa_id'           => 'nullable|exists:mesas,id',
            ], [
                'full_name.required'    => 'El nombre completo es obligatorio.',
                'id_number.required'    => 'La cédula es obligatoria.',
                'phone.required'        => 'El teléfono es obligatorio.',
                'ticket_type.required'  => 'Debes seleccionar un tipo de entrada.',
                'ticket_type.in'        => 'El tipo de entrada no es válido.',
                'quantity.required'     => 'La cantidad es obligatoria.',
                'quantity.min'          => 'Debe adquirir al menos 1 entrada.',
                'total_amount.required' => 'El monto total es obligatorio.',
                'payment_proof.mimes'   => 'El comprobante debe ser JPG, PNG o PDF.',
                'payment_proof.max'     => 'El comprobante no debe superar los 5MB.',
            ]);

            if (!empty($validated['mesa_id'])) {
                $mesa = Mesa::withSum('registrations', 'quantity')->findOrFail($validated['mesa_id']);
                $vendidas = (int) ($mesa->registrations_sum_quantity ?? 0);

                if ($vendidas + $validated['quantity'] > $mesa->capacidad) {
                    throw ValidationException::withMessages([
                        'quantity' => 'Solo quedan ' . ($mesa->capacidad - $vendidas) . ' sillas disponibles en la mesa #' . $mesa->numero . '.',
                    ]);
                }

                $mesa->update([
                    'estado' => ($vendidas + $validated['quantity'] >= $mesa->capacidad) ? 'ocupada' : 'reservada',
                ]);

                // El tipo depende del número de mesa: 1-14 VIP, 15-25 General
                $validated['ticket_type'] = ((int) $mesa->numero <= 14) ? 'mesa_vip' : 'mesa_general';
                $precio = $validated['ticket_type'] === 'mesa_vip' ? 60 : 50;
                $validated['total_amount'] = $validated['quantity'] * $precio;
            }

            $paymentProofPath = null;
            if ($request->hasFile('payment_proof')) {
                $file = $request->file('payment_proof');
                $filename = time() . '_' . $validated['id_number'] . '.' . $file->getClientOriginalExtension();
                $paymentProofPath = $file->storeAs('mma_proofs', $filename, 'public');
            }

            $registration = MmaRegistration::create([
                'user_id'           => auth()->id(),
                'full_name'         => $validated['full_name'],
                'id_number'         => $validated['id_number'],
                'phone'             => $validated['phone'],
                'email'             => $validated['email'] ?? null,
                'social_media'      => $validated['social_media'] ?? null,
                'ticket_type'       => $validated['ticket_type'],
                'quantity'          => $validated['quantity'],
                'total_amount'      => $validated['total_amount'],
                'payment_method'    => $validated['payment_method'] ?? null,
                'payment_reference' => $validated['payment_reference'] ?? null,
                'payment_proof'     => $paymentProofPath,
                'mesa_id'           => $validated['mesa_id'] ?? null,
                'status'            => 'pending',
            ]);

            // Log del pago
            Log::channel('daily')->info('Nuevo registro de pago — Copa Índigo MMA', [
                'user_id'    => auth()->id(),
                'email'      => auth()->user()?->email,
                'registro_id'=> $registration->id,
                'nombre'     => $registration->full_name,
                'cedula'     => $registration->id_number,
                'telefono'   => $registration->phone,
                'entrada'    => $registration->ticket_type,
                'cantidad'   => $registration->quantity,
                'total'      => $registration->total_amount,
                'metodo'     => $registration->payment_method,
                'referencia' => $registration->payment_reference,
            ]);

            // Guardar notificación para el admin (no se envía automáticamente, queda pendiente)
            if (config('mma.whatsapp.notify_admin_on_register')) {
                WhatsappMessageService::logNotification(
                    $registration,
                    WhatsappMessageService::messageForAdmin($registration),
                    'admin'
                );
            }

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => '¡Registro exitoso! Tu inscripción está pendiente de validación. Te contactaremos pronto.',
                ]);
            }

            return back()->with('success', '¡Registro exitoso! Tu inscripción está pendiente de validación. Te contactaremos pronto.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson() || $request->ajax()) {
                $errors = $e->errors();
                $firstError = reset($errors);
                $message = is_array($firstError) ? $firstError[0] : $firstError;
                return response()->json(['success' => false, 'message' => $message, 'errors' => $errors], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Error al procesar tu registro: ' . $e->getMessage()], 500);
            }
            return back()->with('error', 'Ocurrió un error al procesar tu registro. Por favor, intenta nuevamente.');
        }
    }

    public function registro(Request $request)
    {
        $validated = $request->validate([
            'mesa_id'  => 'required|exists:mesas,id',
            'numero'   => 'nullable|string|max:50',
            'tipo'     => 'nullable|in:mesa_general,mesa_vip',
            'cantidad' => 'nullable|integer|min:1',
        ]);

        $mesa = Mesa::withSum('registrations', 'quantity')->findOrFail($validated['mesa_id']);
        $vendidas = (int) ($mesa->registrations_sum_quantity ?? 0);
        $disponibles = max(0, $mesa->capacidad - $vendidas);

        // Las mesas del 1 al 14 son VIP, del 15 al 25 son General
        $tipo = ((int) $mesa->numero <= 14) ? 'mesa_vip' : 'mesa_general';

        $cantidad = min(max((int) ($validated['cantidad'] ?? 1), 1), $disponibles);
        $precio = $tipo === 'mesa_vip' ? 60 : 50;
        $total = $cantidad * $precio;

        return view('registro', [
            'mesa'        => $mesa,
            'numero'      => $validated['numero'] ?? $mesa->numero,
            'tipo'        => $tipo,
            'cantidad'    => $cantidad,
            'precio'      => $precio,
            'total'       => $total,
            'disponibles' => $disponibles,
            'vendidas'    => $vendidas,
        ]);
    }

    public function adminIndex()
    {
        $registrations = MmaRegistration::with(['approver', 'whatsappNotifications'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $stats = [
            'pending'  => MmaRegistration::pending()->count(),
            'approved' => MmaRegistration::approved()->count(),
            'rejected' => MmaRegistration::rejected()->count(),
            'total'    => MmaRegistration::count(),
            'revenue'  => MmaRegistration::approved()->sum('total_amount'),
        ];

        return view('admin.mma.index', compact('registrations', 'stats'));
    }

    public function updateStatus(Request $request, MmaRegistration $registration)
    {
        $validated = $request->validate([
            'status'      => 'required|in:approved,rejected',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $isApproval = $validated['status'] === 'approved';

        $updateData = [
            'status'      => $validated['status'],
            'admin_notes' => $validated['admin_notes'] ?? null,
            'approved_at' => $isApproval ? now() : null,
            'approved_by' => $isApproval ? auth()->id() : null,
        ];

        if ($isApproval) {
            $updateData['ticket_token'] = Str::random(32);
        }

        $registration->update($updateData);

        // Actualizar estado de la mesa asociada
        if ($registration->mesa) {
            $registration->mesa->update([
                'estado' => $isApproval ? 'ocupada' : 'disponible',
            ]);
        }

        // Guardar notificación de WhatsApp para el cliente (se envía manualmente desde el admin)
        WhatsappMessageService::logNotification(
            $registration,
            WhatsappMessageService::messageForClient($registration, $validated['status'])
        );

        // Enviar correo con QR al aprobar
        if ($isApproval && $registration->email) {
            try {
                $adminEmail = config('mail.from.address');
                Mail::to($registration->email)
                    ->bcc($adminEmail)
                    ->send(new TicketApprovedMail($registration));
            } catch (\Exception $e) {
                Log::warning('No se pudo enviar correo de aprobación', [
                    'registration_id' => $registration->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $statusText = $isApproval ? 'aprobado' : 'rechazado';
        return back()->with('success', "Registro {$statusText}. Correo con QR enviado al cliente y copia al admin.");
    }

    public function destroy(MmaRegistration $registration)
    {
        if ($registration->mesa) {
            $registration->mesa->update(['estado' => 'disponible']);
        }

        $registration->delete();
        return back()->with('success', 'Registro eliminado exitosamente.');
    }

    public function whatsappLink(WhatsappNotification $notification)
    {
        $link = WhatsappMessageService::waLink($notification->phone, $notification->message);
        return redirect()->away($link);
    }

    public function markWhatsappSent(WhatsappNotification $notification)
    {
        WhatsappMessageService::markAsSent($notification);
        return back()->with('success', 'Notificación de WhatsApp marcada como enviada.');
    }

    public function markWhatsappFailed(WhatsappNotification $notification)
    {
        WhatsappMessageService::markAsFailed($notification, 'No se pudo abrir WhatsApp en el equipo. Queda guardada para reintentar.');
        return back()->with('info', 'Notificación guardada como fallida. Puedes reintentar cuando tengas WhatsApp disponible.');
    }
}
