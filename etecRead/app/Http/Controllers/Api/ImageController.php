<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ImageController extends Controller
{
    /**
     * Serve imagens de capas de livros
     */
    public function serveBookCover(Request $request, $filename)
    {
        // ✅ Responder OPTIONS (preflight)
        if ($request->isMethod('options')) {
            return response('', 200)
                ->header('Access-Control-Allow-Origin', '*')
                ->header('Access-Control-Allow-Methods', 'GET, HEAD, OPTIONS')
                ->header('Access-Control-Allow-Headers', '*')
                ->header('Access-Control-Max-Age', '86400');
        }
        
        $path = storage_path('app/public/books/' . $filename);
        
        if (!file_exists($path)) {
            return response()->json([
                'success' => false,
                'message' => 'Imagem não encontrada'
            ], 404);
        }
        
        // ✅ Responder GET - Usar readfile para não carregar tudo na memória
        $mimeType = mime_content_type($path);
        $fileSize = filesize($path);
        
        return response()->stream(function() use ($path) {
            $stream = fopen($path, 'rb');
            fpassthru($stream);
            fclose($stream);
        }, 200, [
            'Content-Type' => $mimeType,
            'Content-Length' => $fileSize,
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, HEAD, OPTIONS',
            'Access-Control-Allow-Headers' => '*',
            'Cache-Control' => 'public, max-age=31536000',
            'Accept-Ranges' => 'bytes',
        ]);
    }
    
    /**
     * Serve fotos de perfil de usuários
     */
    public function serveUserPhoto(Request $request, $filename)
    {
        // ✅ Responder OPTIONS
        if ($request->isMethod('options')) {
            return response('', 200)
                ->header('Access-Control-Allow-Origin', '*')
                ->header('Access-Control-Allow-Methods', 'GET, HEAD, OPTIONS')
                ->header('Access-Control-Allow-Headers', '*')
                ->header('Access-Control-Max-Age', '86400');
        }
        
        $path = storage_path('app/public/users/' . $filename);
        
        if (!file_exists($path)) {
            return response()->json([
                'success' => false,
                'message' => 'Foto não encontrada'
            ], 404);
        }
        
        $mimeType = mime_content_type($path);
        $fileSize = filesize($path);
        
        return response()->stream(function() use ($path) {
            $stream = fopen($path, 'rb');
            fpassthru($stream);
            fclose($stream);
        }, 200, [
            'Content-Type' => $mimeType,
            'Content-Length' => $fileSize,
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, HEAD, OPTIONS',
            'Access-Control-Allow-Headers' => '*',
            'Cache-Control' => 'public, max-age=31536000',
            'Accept-Ranges' => 'bytes',
        ]);
    }
}