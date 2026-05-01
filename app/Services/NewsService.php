<?php

namespace App\Services;

use App\Models\Update;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class NewsService
{
    /**
     * Obtiene noticias de la API de Steam para un AppID dado.
     *
     * @param string $appId
     * @param int $count
     * @return int Número de noticias nuevas insertadas
     */
    public function fetchSteamNews($appId = '3405340', $count = 10)
    {
        $url = "https://api.steampowered.com/ISteamNews/GetNewsForApp/v0002/?appid={$appId}&count={$count}&maxlength=500&format=json";

        try {
            $response = Http::get($url);

            if ($response->successful()) {
                $newsItems = $response->json('appnews.newsitems');
                
                if (!$newsItems) {
                    return 0;
                }

                $newCount = 0;

                foreach ($newsItems as $item) {
                    // Limpiar el contenido HTML
                    $cleanContent = strip_tags($item['contents'] ?? '');
                    
                    // Limitar a 200 caracteres para el resumen de la tarjeta
                    $excerpt = Str::limit($cleanContent, 200);

                    // Determinar el tipo de noticia basado en el título
                    $title = $item['title'] ?? '';
                    $type = 'event'; // Por defecto Evento/Noticia
                    if (Str::contains(strtolower($title), ['patch', 'update'])) {
                        $type = 'patch';
                    }

                    // Convertir el timestamp UNIX a DateTime
                    $publishedAt = isset($item['date']) ? Carbon::createFromTimestamp($item['date']) : now();

                    // Guardar en la base de datos si no existe
                    $update = Update::updateOrCreate(
                        ['external_id' => (string) $item['gid']],
                        [
                            'title' => $title,
                            'content' => $excerpt,
                            'url' => $item['url'] ?? null,
                            'type' => $type,
                            'source' => 'steam',
                            'published_at' => $publishedAt
                        ]
                    );

                    if ($update->wasRecentlyCreated) {
                        $newCount++;
                    }
                }

                return $newCount;
            } else {
                Log::error("Error al obtener noticias de Steam: " . $response->status());
                return 0;
            }
        } catch (\Exception $e) {
            Log::error("Excepción al obtener noticias de Steam: " . $e->getMessage());
            return 0;
        }
    }
}
