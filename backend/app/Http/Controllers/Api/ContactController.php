<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\ContactMail;

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
            // Send email to admin
            $adminEmail = env('MAIL_ADMIN_ADDRESS', 'admin@petuniaplay.com');
            Mail::to($adminEmail)->send(new ContactMail($data, 'admin'));

            // Send confirmation email to customer
            Mail::to($data['email'])->send(new ContactMail($data, 'customer'));

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
