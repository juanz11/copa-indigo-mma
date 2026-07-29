<?php

namespace App\Http\Controllers;

use App\Models\MmaRegistration;
use App\Models\WhatsappNotification;
use App\Services\WhatsappMessageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
                'ticket_type'       => 'required|in:general,vip,ringside',
                'quantity'          => 'required|integer|min:1|max:50',
                'total_amount'      => 'required|numeric|min:1',
                'payment_method'    => 'nullable|string|max:100',
                'payment_reference' => 'nullable|string|max:255',
                'payment_proof'     => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
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

        $registration->update([
            'status'      => $validated['status'],
            'admin_notes' => $validated['admin_notes'] ?? null,
            'approved_at' => $validated['status'] === 'approved' ? now() : null,
            'approved_by' => $validated['status'] === 'approved' ? auth()->id() : null,
        ]);

        // Guardar notificación de WhatsApp para el cliente (se envía manualmente desde el admin)
        WhatsappMessageService::logNotification(
            $registration,
            WhatsappMessageService::messageForClient($registration, $validated['status'])
        );

        $statusText = $validated['status'] === 'approved' ? 'aprobado' : 'rechazado';
        return back()->with('success', "Registro {$statusText} y notificación de WhatsApp guardada. Abre el panel para enviarla.");
    }

    public function destroy(MmaRegistration $registration)
    {
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
