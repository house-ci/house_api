<?php

namespace App\Tools;

use App\Models\Service;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class ImageTool
{
    const ALLOWED_IMAGES_EXTENSIONS = [
        1 => 'GIF',
        2 => 'JPEG',
        3 => 'PNG',
    ];

    public static function getImageHtml(Service $service): ?string
    {
        if (!empty($service->url_logo_serv) && self::checkImage($service->url_logo_serv)) {
            return sprintf('<img src="%s" class="rounded media-body mr-2 marchand-logo" alt="%s">', $service->url_logo_serv, $service->nom_serv);
        }
        return null;
    }

    public static function getImageExtension($imageUrl)
    {
        $extensionKey = @exif_imagetype($imageUrl) ?? 99;
        return array_key_exists($extensionKey, self::ALLOWED_IMAGES_EXTENSIONS) ? @self::ALLOWED_IMAGES_EXTENSIONS[$extensionKey] : '';
    }

    public static function checkImage($imageUrl): bool
    {
        if (empty($imageUrl)) {
            return false;
        }

        $imageTypeCheck = Cache::remember('get_image_extension' . Str::slug($imageUrl), config('cinetpay.short_ttl'), static function () use ($imageUrl) {
            return @exif_imagetype($imageUrl);
        });
        return $imageTypeCheck !== false;
    }
}
