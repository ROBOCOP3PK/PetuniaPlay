<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    /**
     * Crear una notificación para un usuario
     */
    public function createNotification(User $user, string $type, string $title, string $message, array $data = [])
    {
        $notification = Notification::create([
            'user_id' => $user->id,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
            'read' => false
        ]);

        // Enviar email si el usuario tiene habilitadas las notificaciones por email
        if ($user->email_notifications_enabled) {
            $this->sendEmail($user, $type, $title, $message, $data);
        }

        return $notification;
    }

    /**
     * Crear notificación para múltiples usuarios
     */
    public function createBulkNotification($users, string $type, string $title, string $message, array $data = [])
    {
        $notifications = [];

        foreach ($users as $user) {
            $notifications[] = $this->createNotification($user, $type, $title, $message, $data);
        }

        return $notifications;
    }

    /**
     * Notificar a todos los gestores (managers y admins)
     */
    public function notifyManagers(string $type, string $title, string $message, array $data = [])
    {
        $managers = User::whereIn('role', ['manager', 'admin'])
            ->where('is_active', true)
            ->get();

        return $this->createBulkNotification($managers, $type, $title, $message, $data);
    }

    /**
     * Enviar email de notificación
     */
    private function sendEmail(User $user, string $type, string $title, string $message, array $data)
    {
        try {
            Mail::send('emails.notification', [
                'user' => $user,
                'title' => $title,
                'message' => $message,
                'data' => $data,
                'type' => $type
            ], function ($mail) use ($user, $title) {
                $mail->to($user->email, $user->name)
                    ->subject($title);
            });
        } catch (\Exception $e) {
            // Log el error pero no fallar la creación de la notificación
            \Log::error('Error sending notification email: ' . $e->getMessage());
        }
    }
}
