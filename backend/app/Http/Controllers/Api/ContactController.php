<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\ContactMail;
use App\Services\NotificationService;
use App\Models\ContactMessage;

class ContactController extends Controller
{
    /**
     * Send contact form message
     */
    public function send(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();

        try {
            // Guardar mensaje en la base de datos
            $contactMessage = ContactMessage::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'subject' => $data['subject'],
                'message' => $data['message'],
                'status' => 'pending'
            ]);

            // Send email to admin
            $adminEmail = env('MAIL_ADMIN_ADDRESS', 'admin@petuniaplay.com');
            Mail::to($adminEmail)->send(new ContactMail($data, 'admin'));

            // Send confirmation email to customer
            Mail::to($data['email'])->send(new ContactMail($data, 'customer'));

            // Crear notificación para managers/admins
            $notificationService = new NotificationService();
            $subjectLabels = [
                'product' => 'Consulta sobre producto',
                'order' => 'Estado de pedido',
                'shipping' => 'Información de envío',
                'return' => 'Devoluciones y cambios',
                'other' => 'Otro'
            ];
            $subjectLabel = $subjectLabels[$data['subject']] ?? 'Mensaje de contacto';

            $notificationService->notifyManagers(
                'contact_message',
                'Nuevo mensaje de contacto',
                "{$data['name']} envió un mensaje sobre: {$subjectLabel}",
                [
                    'contact_message_id' => $contactMessage->id,
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'phone' => $data['phone'] ?? 'No proporcionado',
                    'subject' => $subjectLabel,
                    'message' => $data['message'],
                    'action_url' => '/admin/contact-messages',
                    'action_text' => 'Ver mensaje'
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Tu mensaje ha sido enviado exitosamente. Te responderemos pronto.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al enviar el mensaje. Por favor intenta nuevamente.'
            ], 500);
        }
    }
}
