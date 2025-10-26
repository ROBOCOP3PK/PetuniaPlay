<?php

namespace App\Http\Controllers;

use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Obtener notificaciones del usuario autenticado
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $query = $user->notifications();

        // Filtrar por no leídas si se especifica
        if ($request->boolean('unread_only')) {
            $query->unread();
        }

        // Limitar cantidad
        $limit = $request->input('limit', 20);

        $notifications = $query->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();

        return NotificationResource::collection($notifications);
    }

    /**
     * Obtener contador de notificaciones no leídas
     */
    public function unreadCount(Request $request)
    {
        $count = $request->user()
            ->notifications()
            ->unread()
            ->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Marcar una notificación como leída
     */
    public function markAsRead(Request $request, $id)
    {
        $notification = $request->user()
            ->notifications()
            ->findOrFail($id);

        $notification->markAsRead();

        return response()->json([
            'message' => 'Notificación marcada como leída',
            'notification' => new NotificationResource($notification)
        ]);
    }

    /**
     * Marcar todas las notificaciones como leídas
     */
    public function markAllAsRead(Request $request)
    {
        $count = $request->user()
            ->notifications()
            ->unread()
            ->update([
                'read' => true,
                'read_at' => now()
            ]);

        return response()->json([
            'message' => 'Todas las notificaciones marcadas como leídas',
            'count' => $count
        ]);
    }

    /**
     * Eliminar una notificación
     */
    public function destroy(Request $request, $id)
    {
        $notification = $request->user()
            ->notifications()
            ->findOrFail($id);

        $notification->delete();

        return response()->json([
            'message' => 'Notificación eliminada'
        ]);
    }

    /**
     * Eliminar todas las notificaciones leídas
     */
    public function deleteRead(Request $request)
    {
        $count = $request->user()
            ->notifications()
            ->where('read', true)
            ->delete();

        return response()->json([
            'message' => 'Notificaciones leídas eliminadas',
            'count' => $count
        ]);
    }
}
