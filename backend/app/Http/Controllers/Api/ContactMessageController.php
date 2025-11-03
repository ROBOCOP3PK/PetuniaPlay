<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactMessageController extends Controller
{
    /**
     * Obtener todos los mensajes de contacto (admin/manager)
     */
    public function index(Request $request)
    {
        try {
            $query = ContactMessage::with('resolvedBy')
                ->latest();

            // Filtrar por estado
            if ($request->has('status')) {
                if ($request->status === 'pending') {
                    $query->pending();
                } elseif ($request->status === 'in_progress') {
                    $query->inProgress();
                } elseif ($request->status === 'resolved') {
                    $query->resolved();
                }
            }

            // Paginación
            $perPage = $request->get('per_page', 20);
            $messages = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $messages->items(),
                'meta' => [
                    'current_page' => $messages->currentPage(),
                    'last_page' => $messages->lastPage(),
                    'per_page' => $messages->perPage(),
                    'total' => $messages->total(),
                    'from' => $messages->firstItem(),
                    'to' => $messages->lastItem(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los mensajes'
            ], 500);
        }
    }

    /**
     * Obtener estadísticas de mensajes (admin/manager)
     */
    public function stats()
    {
        try {
            $stats = [
                'total' => ContactMessage::count(),
                'pending' => ContactMessage::pending()->count(),
                'in_progress' => ContactMessage::inProgress()->count(),
                'resolved' => ContactMessage::resolved()->count(),
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener estadísticas'
            ], 500);
        }
    }

    /**
     * Obtener un mensaje específico
     */
    public function show($id)
    {
        try {
            $message = ContactMessage::with('resolvedBy')->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $message
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Mensaje no encontrado'
            ], 404);
        }
    }

    /**
     * Actualizar estado del mensaje
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $request->validate([
                'status' => 'required|in:pending,in_progress,resolved',
                'admin_notes' => 'nullable|string'
            ]);

            $message = ContactMessage::findOrFail($id);

            $updateData = ['status' => $request->status];

            if ($request->status === 'resolved') {
                $updateData['resolved_by'] = Auth::id();
                $updateData['resolved_at'] = now();
            }

            if ($request->has('admin_notes')) {
                $updateData['admin_notes'] = $request->admin_notes;
            }

            $message->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Estado actualizado exitosamente',
                'data' => $message->fresh()->load('resolvedBy')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el estado'
            ], 500);
        }
    }

    /**
     * Eliminar un mensaje
     */
    public function destroy($id)
    {
        try {
            $message = ContactMessage::findOrFail($id);
            $message->delete();

            return response()->json([
                'success' => true,
                'message' => 'Mensaje eliminado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el mensaje'
            ], 500);
        }
    }
}
