<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadController extends Controller
{
    /**
     * Upload de imagen
     */
    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // Max 5MB
            'type' => 'nullable|string|in:product,category,blog', // Tipo de imagen para organización
        ]);

        try {
            $file = $request->file('image');
            $type = $request->input('type', 'general');

            // Generar nombre único
            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();

            // Guardar en storage/app/public/{type}/{filename}
            $path = $file->storeAs("images/{$type}", $filename, 'public');

            // Generar URL pública
            $url = Storage::url($path);

            return response()->json([
                'success' => true,
                'data' => [
                    'path' => $path,
                    'url' => $url,
                    'filename' => $filename,
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                ],
                'message' => 'Imagen subida exitosamente'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al subir imagen: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload múltiple de imágenes
     */
    public function uploadMultiple(Request $request)
    {
        $request->validate([
            'images' => 'required|array|max:10', // Máximo 10 imágenes
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'type' => 'nullable|string|in:product,category,blog',
        ]);

        try {
            $type = $request->input('type', 'general');
            $uploadedImages = [];

            foreach ($request->file('images') as $file) {
                $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs("images/{$type}", $filename, 'public');
                $url = Storage::url($path);

                $uploadedImages[] = [
                    'path' => $path,
                    'url' => $url,
                    'filename' => $filename,
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                ];
            }

            return response()->json([
                'success' => true,
                'data' => $uploadedImages,
                'message' => count($uploadedImages) . ' imágenes subidas exitosamente'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al subir imágenes: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar imagen
     */
    public function deleteImage(Request $request)
    {
        $request->validate([
            'path' => 'required|string',
        ]);

        try {
            $path = $request->input('path');

            // Verificar que el archivo existe
            if (!Storage::disk('public')->exists($path)) {
                return response()->json([
                    'success' => false,
                    'message' => 'La imagen no existe'
                ], 404);
            }

            // Eliminar archivo
            Storage::disk('public')->delete($path);

            return response()->json([
                'success' => true,
                'message' => 'Imagen eliminada exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar imagen: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar imagen por URL
     */
    public function deleteImageByUrl(Request $request)
    {
        $request->validate([
            'url' => 'required|string',
        ]);

        try {
            $url = $request->input('url');

            // Extraer el path de la URL
            // URL format: http://127.0.0.1:8000/storage/images/product/filename.jpg
            // Path: images/product/filename.jpg
            $path = str_replace('/storage/', '', parse_url($url, PHP_URL_PATH));

            // Verificar que el archivo existe
            if (!Storage::disk('public')->exists($path)) {
                return response()->json([
                    'success' => false,
                    'message' => 'La imagen no existe'
                ], 404);
            }

            // Eliminar archivo
            Storage::disk('public')->delete($path);

            return response()->json([
                'success' => true,
                'message' => 'Imagen eliminada exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar imagen: ' . $e->getMessage()
            ], 500);
        }
    }
}
