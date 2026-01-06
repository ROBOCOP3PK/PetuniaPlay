<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;
use Intervention\Image\Encoders\WebpEncoder;

class FileUploadController extends Controller
{
    private const MAX_WIDTH = 1200;
    private const MAX_HEIGHT = 1200;
    private const QUALITY = 80;

    /**
     * Optimiza y guarda una imagen
     */
    private function optimizeAndSave($file, string $type): array
    {
        $filename = time() . '_' . Str::random(10) . '.webp';
        $directory = "images/{$type}";
        $path = "{$directory}/{$filename}";

        // Asegurar que el directorio existe
        Storage::disk('public')->makeDirectory($directory);

        // Cargar imagen con Intervention
        $image = Image::read($file);

        // Redimensionar manteniendo proporción (solo si es más grande)
        $image->scaleDown(width: self::MAX_WIDTH, height: self::MAX_HEIGHT);

        // Codificar a WebP con calidad optimizada
        $encoded = $image->encode(new WebpEncoder(quality: self::QUALITY));

        // Guardar
        Storage::disk('public')->put($path, (string) $encoded);

        $url = Storage::url($path);
        $size = Storage::disk('public')->size($path);

        return [
            'path' => $path,
            'url' => $url,
            'filename' => $filename,
            'size' => $size,
            'mime_type' => 'image/webp',
        ];
    }

    /**
     * Upload de imagen
     */
    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240', // Max 10MB (se comprimirá)
            'type' => 'nullable|string|in:product,category,blog',
        ]);

        try {
            $file = $request->file('image');
            $type = $request->input('type', 'general');

            $imageData = $this->optimizeAndSave($file, $type);

            return response()->json([
                'success' => true,
                'data' => $imageData,
                'message' => 'Imagen subida y optimizada exitosamente'
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
            'images' => 'required|array|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'type' => 'nullable|string|in:product,category,blog',
        ]);

        try {
            $type = $request->input('type', 'general');
            $uploadedImages = [];

            foreach ($request->file('images') as $file) {
                $uploadedImages[] = $this->optimizeAndSave($file, $type);
            }

            return response()->json([
                'success' => true,
                'data' => $uploadedImages,
                'message' => count($uploadedImages) . ' imágenes subidas y optimizadas exitosamente'
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
