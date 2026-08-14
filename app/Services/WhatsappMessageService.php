<?php

namespace App\Services;

use App\Models\MmaRegistration;
use App\Models\WhatsappNotification;

class WhatsappMessageService
{
    /**
     * Genera un mensaje de confirmación/rechazo para el cliente.
     */
    public static function messageForClient(MmaRegistration $registration, string $status): string
    {
        $name = $registration->full_name;
        $type = ucfirst($registration->ticket_type);
        $qty  = $registration->quantity;
        $total = number_format($registration->total_amount, 2);

        if ($status === 'approved') {
            $msg = "¡Hola {$name}! 🥊\n\n";
            $msg .= "Tu registro para la *Copa Índigo MMA* fue *APROBADO*.\n";
            $msg .= "Entrada: *{$type}*\n";
            $msg .= "Cantidad de sillas: *{$qty}*\n";
            $msg .= "Total pagado: *\${$total} USD*\n\n";
            $msg .= "Te esperamos el *Sábado 24 de Octubre a las 8:00 p.m.* en el *Hotel Hesperia Valencia*.\n";
            $msg .= "Presenta este mensaje en la entrada.\n\n";
            $msg .= "Gracias por ser parte de esta noche histórica.";
            return $msg;
        }

        // rejected
        $msg = "Hola {$name}.\n\n";
        $msg .= "Lamentamos informarte que tu registro para la *Copa Índigo MMA* no pudo ser aprobado en esta ocasión.\n";
        $msg .= "Motivo: *" . ($registration->admin_notes ?: 'Pago no verificado o datos incorrectos') . "*\n\n";
        $msg .= "Si crees que es un error, contáctanos para revisarlo.";
        return $msg;
    }

    /**
     * Genera un mensaje de alerta para el admin cuando llega un registro.
     */
    public static function messageForAdmin(MmaRegistration $registration): string
    {
        $msg = "🆕 *Nuevo registro — Copa Índigo MMA*\n\n";
        $msg .= "Nombre: *" . $registration->full_name . "*\n";
        $msg .= "Cédula: " . $registration->id_number . "\n";
        $msg .= "Teléfono: " . $registration->phone . "\n";
        $msg .= "Entrada: " . ucfirst($registration->ticket_type) . "\n";
        $msg .= "Sillas: " . $registration->quantity . "\n";
        $msg .= "Total: \$" . number_format($registration->total_amount, 2) . " USD\n";
        $msg .= "Método: " . ($registration->payment_method ? ucfirst($registration->payment_method) : 'N/A') . "\n";
        $msg .= "Ref: " . ($registration->payment_reference ?: 'N/A') . "\n\n";
        $msg .= "Revisa el panel admin para aprobarlo.";
        return $msg;
    }

    /**
     * Genera el enlace wa.me para abrir WhatsApp Web/App con el mensaje.
     */
    public static function waLink(string $phone, string $message): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Convertir número local venezolano (0414...) a formato internacional (58414...)
        if (substr($phone, 0, 1) === '0') {
            $phone = '58' . ltrim($phone, '0');
        } elseif (substr($phone, 0, 2) !== '58') {
            $phone = '58' . $phone;
        }

        return 'https://wa.me/' . $phone . '?text=' . rawurlencode($message);
    }

    /**
     * Crea un registro de notificación pendiente.
     */
    public static function logNotification(MmaRegistration $registration, string $message, string $target = 'client'): WhatsappNotification
    {
        $phone = $target === 'client' ? $registration->phone : config('mma.whatsapp.admin_number');

        return WhatsappNotification::create([
            'mma_registration_id' => $registration->id,
            'phone'               => $phone,
            'message'             => $message,
            'status'              => 'pending',
        ]);
    }

    /**
     * Marca una notificación como enviada.
     */
    public static function markAsSent(WhatsappNotification $notification): void
    {
        $notification->update([
            'status'  => 'sent',
            'sent_at' => now(),
        ]);
    }

    /**
     * Marca una notificación como fallida y guarda el error.
     */
    public static function markAsFailed(WhatsappNotification $notification, string $error = 'No se pudo abrir WhatsApp'): void
    {
        $notification->update([
            'status' => 'failed',
            'error'  => $error,
        ]);
    }
}
