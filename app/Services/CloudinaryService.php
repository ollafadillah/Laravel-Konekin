<?php

namespace App\Services;

use Cloudinary\Cloudinary;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class CloudinaryService
{
    public function upload(UploadedFile $file, array $options = []): string
    {
        $result = $this->client()->uploadApi()->upload(
            $file->getRealPath(),
            $options
        );

        return $result['secure_url'];
    }

    public function uploadDocument(UploadedFile $file, string $folder): string
    {
        $extension = strtolower($file->getClientOriginalExtension() ?: 'bin');
        $baseName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeBaseName = Str::slug($baseName) ?: 'proposal';
        $publicId = trim($folder, '/') . '/' . $safeBaseName . '-' . now()->format('YmdHis') . '-' . Str::random(8) . '.' . $extension;

        return $this->upload($file, [
            'resource_type' => 'raw',
            'public_id' => $publicId,
            'use_filename' => false,
            'unique_filename' => false,
            'overwrite' => false,
            'filename_override' => $file->getClientOriginalName(),
        ]);
    }

    public function signedDownloadUrl(string $deliveryUrl, ?string $fallbackFormat = null): ?string
    {
        $path = parse_url($deliveryUrl, PHP_URL_PATH);

        if (!$path || !str_contains($deliveryUrl, 'res.cloudinary.com')) {
            return null;
        }

        $segments = array_values(array_filter(explode('/', trim($path, '/'))));

        if (count($segments) < 4) {
            return null;
        }

        $resourceType = $segments[1] ?? 'image';
        $deliveryType = $segments[2] ?? 'upload';
        $assetSegments = array_slice($segments, 3);

        if (isset($assetSegments[0]) && preg_match('/^v\d+$/', $assetSegments[0])) {
            array_shift($assetSegments);
        }

        $assetPath = implode('/', $assetSegments);
        $format = strtolower(pathinfo($assetPath, PATHINFO_EXTENSION) ?: (string) $fallbackFormat);
        $publicId = $format ? substr($assetPath, 0, -1 * (strlen($format) + 1)) : $assetPath;

        if (!$publicId || !$format) {
            return null;
        }

        return $this->client()->uploadApi()->privateDownloadUrl($publicId, $format, [
            'resource_type' => $resourceType,
            'type' => $deliveryType,
            'expires_at' => now()->addMinutes(10)->timestamp,
        ]);
    }

    protected function client(): Cloudinary
    {
        $cloudinaryUrl = config('services.cloudinary.url');

        if (!empty($cloudinaryUrl)) {
            return new Cloudinary($cloudinaryUrl);
        }

        return new Cloudinary([
            'cloud' => [
                'cloud_name' => config('services.cloudinary.cloud_name'),
                'api_key' => config('services.cloudinary.api_key'),
                'api_secret' => config('services.cloudinary.api_secret'),
            ],
            'url' => [
                'secure' => true,
            ],
        ]);
    }
}
