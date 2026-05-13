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
